<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'barang';

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
    ];
}
