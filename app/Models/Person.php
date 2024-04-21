<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    
    // protected $keyType = 'string';
    // public $incrementing = false;

    protected $table = 'person';
    protected $fillable =[
        'id',
        'image',
        'name',
        'phone',
        'email',
        'address'
    ];
}
