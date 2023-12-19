<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotModels extends Model
{
    use HasFactory;
    protected $fillable = ["categories_id", "businesses_id"];
    protected $table = 'businesses_categories';
}
