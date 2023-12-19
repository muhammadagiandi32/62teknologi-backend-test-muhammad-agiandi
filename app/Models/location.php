<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class location extends Model
{
    use HasFactory;
    use HasUuids;
    public $incrementing = false;
    protected $fillable = ['address1', 'address2', 'address3', 'city', 'zip_code', 'country', 'state', 'display_address'];
    protected $keyType = 'string';
    protected $casts = [
        'display_address' => 'array'
    ];
    protected $hidden = ['id', 'created_at', 'updated_at'];
    public function bussiness(): HasOne
    {
        return $this->hasOne(Businesses::class, 'id', 'location_id');
    }
}
