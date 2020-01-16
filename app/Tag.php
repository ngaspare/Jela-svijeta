<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Dish;

class Tag extends Model
{
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
    }
}
