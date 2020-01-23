<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Tag;
use App\Ingredient;
use Carbon\Carbon;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model
{
    use softDeletes;
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
    

    public function scopeByDate($query, $date) 
    {
        if ($date === 0)
        {
            return $query;
        }

        $date = Carbon::createFromTimestamp($date)->toDateTimeString();
        $checks = ['created_at', 'updated_at', 'deleted_at'];
        return $query->withTrashed()->where(function($query) use ($date, $checks) 
        {
            foreach($checks as $check) 
            {
                $query->orWhere(function($query) use($date, $check) 
                {
                    return $query->whereDate($check, '>=', $date);
                });
            }
            return $query;
        });
    }
}
