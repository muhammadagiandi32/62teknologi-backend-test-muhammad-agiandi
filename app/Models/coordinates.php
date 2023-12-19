<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class coordinates extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = ["*"];
    protected $keyType = 'string';
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
