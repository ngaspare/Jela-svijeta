<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Dishes;

class Ingredient extends Model
{
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function tags()
    {
        return $this->belongsToMany(Dish::class);
    }
}
