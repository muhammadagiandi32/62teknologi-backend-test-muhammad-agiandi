<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Businesses;
use App\Models\categories;
use App\Models\coordinates;
use App\Models\location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Categories seeder
        for ($i = 0; $i < 20; $i++) {
            $title = $faker->foodName();
            categories::create([
                'id' => fake()->uuid(),
                'alias' => Str::remove('-', Str::slug($title)),
                'title' => $title
            ]);
        }
        $categories_id = categories::pluck('id');

        // coordinates seeder
        for ($i = 0; $i < 20; $i++) {
            coordinates::create([
                'id' => fake()->uuid(),
                'latitude' => fake()->latitude($min = -90, $max = 90),
                'longitude' => fake()->longitude($min = -180, $max = 180)
            ]);
        }
        $coordinates_id = coordinates::pluck('id');

        // location seeder
        for ($i = 0; $i < 20; $i++) {
            location::create([
                'id' => fake()->uuid(),
                'address1' => fake()->address(),
                'address2' => fake()->address(),
                'address3' => fake()->address(),
                'city' => fake()->city(),
                'zip_code' => fake()->postcode(),
                'country' => fake()->countryCode(),
                'state' => fake()->stateAbbr(),
                'display_address' => fake()->randomElements(array(fake()->streetAddress(), fake()->streetAddress(), fake()->streetAddress(), fake()->streetAddress()), 3),
            ]);
        }
        $location_id = location::pluck('id');
        // Businesses seeder
        for ($i = 0; $i < 10; $i++) {
            $food_name = $faker->foodName();
            Businesses::create([
                'id' => fake()->uuid(),
                'alias' => Str::slug($food_name, "-"),
                'name' => $food_name,
                'image_url' => fake()->imageUrl($width = 640, $height = 480),
                'is_closed' => fake()->boolean(),
                'review_count' => fake()->numberBetween($min = 0, $max = 9999),
                'categories_id' => fake()->randomElement($categories_id),
                'rating' => fake()->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 5),
                'coordinates_id' => fake()->randomElement($coordinates_id),
                'transactions' => fake()->randomElements(array('pickup', 'delivery', 'restaurant_reservation'), $count = 3),
                'price' => trim(json_encode(fake()->randomElement(array('$', '$$', '$$$', '$$$$', '$$$$$'))), '"'),
                'location_id' => fake()->randomElement($location_id),
                'phone' => fake()->e164PhoneNumber(),
                'display_phone' => fake()->tollFreePhoneNumber(),
                'distance' => fake()->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL)
            ]);
        }

        // pivot tables
        $categori = categories::all();
        Businesses::all()->each(function ($businesses) use ($categori) {
            $businesses->categories()->attach(
                $categori->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
