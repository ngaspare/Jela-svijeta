<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Category extends Model
{
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function dishes()
    {
        return $this->hasMany(Dish::class)->withTimestamps();
    }

//     public function translations()
//     {
//         return $this->hasMany(CategoryTranslations::class);
//     }
}
