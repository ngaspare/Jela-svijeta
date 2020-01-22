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

    protected $visible = ['id', 'title', 'description', 'status', 'category', 'ingredients', 'tags'];

    public function category()
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

    public function scopeBy($query, $with, $page)
    {
        return $query->when(in_array("category", $with), function($query)
                                                {
                                                    return $query->with('category');
                                                })
                            ->when(in_array("tags", $with), function($query)
                                                {
                                                    return $query->with('tags');
                                                })
                            ->when(in_array("ingredients", $with), function($query)
                                                {
                                                    return $query->with('ingredients');
                                                })
                            ->paginate($page);
    }
}
