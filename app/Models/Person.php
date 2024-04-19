<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Person extends Model
{
    use HasFactory;
    
    protected $table = 'person';
    protected $fillable = [
        'id',
        'image',
        'name',
        'address',
        'phone',
        'email',
    ];
    
    /**
     * image
     *
     * @return Attribute
     */

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('public/image'.$image),
        );
    }
}
