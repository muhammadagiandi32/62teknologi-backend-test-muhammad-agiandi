<?php

namespace App\Http\Controllers;

use App\Models\Businesses;
use App\Models\categories;
use App\Models\coordinates;
use App\Models\location;
use App\Models\PivotModels;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpParser\ErrorHandler\Throwing;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Validation\Rules\File;
use Throwable;

class BusinessesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
            // validaton for bussiness
            'name' => 'required|max:255',
            'image_url' => [
                'required',
                File::image()
                    // ->min(1024)
                    ->max(12 * 1024)
            ],
            'is_closed' => 'required|boolean',
            'review_count' => 'required|numeric',
            'rating' => 'required|decimal:1',
            'price' => 'required|numeric',
            'phone' => 'required',
            'distance' => 'required|decimal:1,99',
            'transactions' => 'required|array',
            'transactions.*' => 'required|string',
            // validation for location
            'address1'  => 'required|string',
            'city'      => 'required|string',
            'zip_code'  => 'required|numeric',
            'country'   => 'required|string',
            'state'     => 'required|string',
            'latitude'     => 'required|string',
            'longitude'     => 'required|string',


        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag()->all(), 400);
        }

        try {
            DB::beginTransaction();
            $coordinates = new coordinates;
            $coordinates->id = Str::uuid();
            $coordinates->latitude = $request->latitude;
            $coordinates->longitude = $request->longitude;
            $coordinates->save();

            $location = new location;
            $location->id = Str::uuid();
            $location->address1  = $request->address1;
            $location->address2  = $request->address2;
            $location->address3  = $request->address3;
            $location->city      = $request->city;
            $location->zip_code = $request->zip_code;
            $location->country  = $request->country;
            $location->state   = $request->state;
            $location->display_address = [
                $request->address1,
                $request->address2,
                $request->address3,
            ];
            $location->save();

            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $name = time() . '-' . Str::uuid() . $file->getClientOriginalName();
                $filePath = 'images/' . $name;
                $img_url = 'https://62-teknologi.s3.ap-southeast-1.amazonaws.com/' . $filePath;
                $aws =   Storage::disk('s3')->put($filePath, file_get_contents($file));
            }
            for ($i = 0; $i < count($request->alias); $i++) {
                $categoris[] = [
                    'id' => Str::uuid(),
                    'alias' => $request->alias[$i],
                    'title' => $request->title[$i]
                ];
            }
            $return_categoris = categories::insert($categoris);

            $businesses = new Businesses;
            $businesses->id = Str::uuid();
            $businesses->alias = Str::slug($request->name, '-');
            $businesses->name = $request->name;
            $businesses->image_url =  $img_url;
            $businesses->image_path =  $filePath;
            $businesses->is_closed = $request->is_closed;
            $businesses->review_count = $request->review_count;
            $businesses->categories_id = $categoris[0]['id'];
            $businesses->rating = $request->rating;
            $businesses->coordinates_id = $coordinates->id;
            $businesses->transactions = json_encode($request->transactions);
            $businesses->price = $request->price;
            $businesses->location_id = $location->id;
            $businesses->phone = $request->phone;
            $businesses->distance = $request->distance;
            $businesses->display_phone = $request->phone;
            $businesses->save();

            //     // categoris

            foreach ($categoris as $key => $value) {
                $pivot_categoris[] = [
                    'categories_id' => $value['id'],
                    'businesses_id' => $businesses->id
                ];
            }
            $return_pivot = PivotModels::insert($pivot_categoris);

            DB::commit();
            return response()->json($aws, 201);
        } catch (QueryException $th) {
            // throw $th;
            DB::rollBack();
            return response()->json($th, 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Businesses $businesses, Request $request)
    {
        //
        $limit = (isset($request->limit)) ?  (int)$request->limit : 20;
        $price = (isset($request->price)) ? 'CHAR_LENGTH(price) =' . (int) $request->price : 'CHAR_LENGTH(price) <= 5';
        $data = $businesses->with('location')->with('coordinates')->with('categories')
            ->whereHas('location', function ($query) use ($request) {
                $query
                    ->where('state', 'like', '%' . $request->location . '%')
                    ->where('country', 'like', '%' . $request->locale . '%')
                    ->orWhere('address1', 'like', '%' . $request->location . '%')
                    ->orWhere('address2', 'like', '%' . $request->location . '%')
                    ->orWhere('address3', 'like', '%' . $request->location . '%');
            })
            ->whereHas('coordinates', function ($query) use ($request) {
                $query->where('latitude', 'like', '%' . $request->latitude . '%')
                    ->where('longitude', 'like', '%' . $request->longitude . '%');
            })
            ->whereHas('categories', function ($query) use ($request) {
                (isset($request->categories)) ? $query->whereIn('alias',  $request->categories) : $query;
            })
            ->where('name', 'like', '%' . $request->term . '%')
            ->where('is_closed', '=', intval($request->open_now))
            ->whereRaw($price)
            ->offset($request->offset)
            ->limit($limit)
            ->get();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Businesses $businesses, $id)
    {
        //
        $data = $businesses->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Businesses $businesses)
    {
        //
        // dd($request);
        try {
            //code...
            $businesses = new Businesses;
            $businesses->exists = true;
            $businesses->id = $request->id;
            $businesses->alias = Str::slug($request->name, '-');
            $businesses->name = $request->name;
            // $businesses->image_url =  $img_url;
            // $businesses->image_path =  $filePath;
            $businesses->is_closed = $request->is_closed;
            $businesses->review_count = $request->review_count;
            // $businesses->categories_id = $categoris[0]['id'];
            $businesses->rating = $request->rating;
            // $businesses->coordinates_id = $coordinates->id;
            $businesses->transactions = json_encode($request->transactions);
            $businesses->price = $request->price;
            // $businesses->location_id = $location->id;
            $businesses->phone = $request->phone;
            $businesses->distance = $request->distance;
            $businesses->display_phone = $request->phone;
            $businesses->save();
        } catch (QueryException $th) {
            return response()->json($th, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Businesses $businesses)
    {
        //
    }
}
