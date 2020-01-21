<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Dish;


class Category extends Model
{
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $hidden = ['translations', 'created_at', 'updated_at'];

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}
