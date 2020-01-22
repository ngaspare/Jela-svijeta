<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Dish;
use App\Tag;
use App\Category;
use App\noWith;
use Illuminate\Support\Facades\DB;

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

        if(array_key_exists('with', $validatedData))
        {
            $with = explode(',', $validatedData['with']);
        }
        
            if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
            {
                if(strtoupper($validatedData['category']) == "NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->whereNull('category_id');
                        return $dishes->by($with, $validatedData['per_page']);
                    }
                }

                elseif(strtoupper($validatedData['category']) == "!NULL")
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->whereNotNull('category_id');
                        return $dishes->by($with, $validatedData['per_page']);
                    }
                }

                else
                {
                    foreach($tags as $tag)
                    {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes()->where('category_id', $validatedData['category']);
                        return $dishes->by($with, $validatedData['per_page']);
                    }
                }
            }

            elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
            {
                foreach($tags as $tag)
                {
                        $dishes = Tag::where('id', $tag)->firstOrFail()->dishes();
                        return $dishes->by($with, $validatedData['per_page']);
                }
                return $dishes;
            }

            elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
            {
                if(strtoupper($validatedData['category']) == "NULL")
                {
                    $dishes = Dish::whereNull('category_id');
                    return $dishes->by($with, $validatedData['per_page']);
                }

                if(strtoupper($validatedData['category']) == "!NULL")
                {
                    $dishes = Dish::whereNotNull('category_id');
                    return $dishes->by($with, $validatedData['per_page']);
                }

                $dishes = Dish::where('category_id', $validatedData['category']);
                return $dishes->by($with, $validatedData['per_page']);

            }

            else
            {   
                $dishes = (new Dish)->newQuery();
                return $dishes->by($with, $validatedData['per_page']);
            }       
    }
}