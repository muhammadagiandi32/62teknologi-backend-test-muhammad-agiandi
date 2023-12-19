<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = ["id", "alias", "title"];
    protected $keyType = 'string';
    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function businesses()
    {
        return $this->belongsToMany(Businesses::class);
    }
}
