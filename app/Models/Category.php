<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'balance',
        'color',
        'transactions',
        'icon',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    
        'transactions'=>'array'
    ];


}
