<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Tag;
use App\Ingredient;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Dish extends Model
{
    use Translatable;
    public $translatedAttributes = ['title', 'description'];
    //protected $guarded = ['id'];
    public $timestamps = false;

    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
