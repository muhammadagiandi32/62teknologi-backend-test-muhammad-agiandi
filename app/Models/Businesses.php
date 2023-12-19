<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Businesses extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'alias',
        'image_url',
        'display_phone',
        'is_closed',
        'review_count',
        'rating',
        'price',
        'phone',
        'distance',
        'transactions',
    ];
    protected $keyType = 'string';
    protected $casts = [
        'transactions' => 'array'
    ];
    protected $hidden = ['pivot', 'location_id', 'image_path', 'categories_id', 'coordinates_id', 'created_at', 'updated_at'];

    public function coordinates(): BelongsTo
    {
        return $this->belongsTo(coordinates::class, 'coordinates_id', 'id');
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(location::class, 'location_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(categories::class)->select(array('alias', 'title'));
    }
}
