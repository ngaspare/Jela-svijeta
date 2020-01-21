<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Dish;
use App\Tag;
use App\Category;
use App\noWith;


class DishController extends Controller
{
    public function index(Request $request)
    {
        // $name = $request->input('name');
        // return $name;

        $validatedData = $request->validate([
            'per_page' => 'nullable',
            'page' => 'nullable',
            'category' => 'nullable|max:5',
            'tags' => 'nullable',
            'with' => 'nullable',
            'lang' => 'required',
            'diff_time' => 'nullabe'
        ]);
        
        if(!array_key_exists('per_page', $validatedData))
        {
            $validatedData['per_page'] = NULL;
        }

        if(array_key_exists('lang', $validatedData))
        {
            app()->setLocale($validatedData['lang']);
        }

        if(!array_key_exists('with', $validatedData))
        {
            $validatedData['with'] = NULL;
        }

        if(array_key_exists('tags', $validatedData))
        {
            $tags = explode(',', $validatedData['tags']);
        }
        
        $dishes = new Collection();

        //ako se traže i sastojci i kategorija i tagovi
        if( ($validatedData['with'] == 'tags,ingredients,category') || ($validatedData['with'] == 'tags,category,ingredients') ||
            ($validatedData['with'] == 'ingredients,category,tags') || ($validatedData['with'] == 'ingredients,tags,category') ||
            ($validatedData['with'] == 'category,tags,ingredients') || ($validatedData['with'] == 'category,ingredients,tags'))
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['category', 'tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['category', 'tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['category', 'tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['category', 'tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['category', 'tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['category', 'tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['category', 'tags', 'ingredients'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['category', 'tags', 'ingredients'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traže sastojci i kategorija
        elseif( ($validatedData['with'] == 'ingredients,category') || ($validatedData['with'] == 'category,ingredients'))
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['category', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['category', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['category', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['category', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['category', 'ingredients'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['category', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['category', 'ingredients'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['category', 'ingredients'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traže kategorija i tagovi
        elseif( ($validatedData['with'] == 'tags,category') || ($validatedData['with'] == 'category,tags'))
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['category', 'tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['category', 'tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['category', 'tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['category', 'tags'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['category', 'tags'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['category', 'tags'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['category', 'tags'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['category', 'tags'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traže sastojci i tagovi
        if( ($validatedData['with'] == 'tags,ingredients') || ($validatedData['with'] == 'ingredients,tags'))
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['tags', 'ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['tags', 'ingredients'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['tags', 'ingredients'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['tags', 'ingredients'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traži samo kategorija
        if($validatedData['with'] == 'category')
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['category'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['category'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['category'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['category'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['category'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['category'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['category'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['category'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traže samo tagovi
        if($validatedData['with'] == 'tags')
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['tags'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['tags'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['tags'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['tags'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['tags'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['tags'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se traže samo sastojci
        if($validatedData['with'] == 'ingredients')
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->with(['ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->with(['ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->with(['ingredients'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->with(['ingredients'])
                    ->paginate($validatedData['per_page']);
                }
                
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->with(['ingredients'])
                    ->paginate($validatedData['per_page']);

                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->with(['ingredients'])
                    ->paginate($validatedData['per_page']);
                    
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->with(['ingredients'])
                ->paginate($validatedData['per_page']);

                return $dishes;
            }

            else
            {
                return Dish::with(['ingredients'])
                ->paginate($validatedData['per_page']);

            } 
        }

        //ako se ne traže ni sastojci ni katogrije ni tagovi
        else
        {
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();

                if($validatedData['category'] == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', NULL)
                        ->paginate($validatedData['per_page']);
                    }
                }

                elseif($validatedData['category'] == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', '!=', NULL)
                        ->paginate($validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category'])
                        ->paginate($validatedData['per_page']);
                    }
                }
    
                return $dishes;
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                $tags = explode(',', $validatedData['tags']);
                $dishes = new Collection();
                foreach($tags as $tag)
                {
                    $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->paginate($validatedData['per_page']);
                }

                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if($validatedData['category'] == "NULL")
                {
                    $dishes = Dish::where('category_id', NULL)->paginate($validatedData['per_page']);
                    return $dishes;
                }

                if($validatedData['category'] == "!NULL")
                {
                    $dishes = Category::has('dishes')->paginate($validatedData['per_page']);
                    return $dishes;
                }

                $dishes = Dish::where('category_id', $validatedData['category'])->paginate($validatedData['per_page']);
                return $dishes;
            }

            else
            {
                return Dish::paginate($validatedData['per_page']);
            }       
        } 
    }
}