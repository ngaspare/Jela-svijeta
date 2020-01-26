<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Dish;

class DishController extends Controller
{
    public function index(Dish $dish, Request $request)
    {
        $validatedData = $request->validate([
            'per_page' => 'sometimes|nullable',
            'page' => 'sometimes|nullable',
            'category' => 'sometimes|nullable|max:5',
            'tags' => 'sometimes|nullable',
            'with' => 'sometimes|nullable',
            'lang' => 'required',
            'diff_time' => 'sometimes|nullable'
        ]);

        //postavimo željeni jezik
        if(array_key_exists('lang', $validatedData))
        {
            app()->setLocale($validatedData['lang']);
        }
        else
        {
            return 'Morate unjeti jezik po kojem želite izlistati jela!';
        }

        if(!array_key_exists('per_page', $validatedData))
        {
            $validatedData['per_page'] = NULL;
        }

        if(array_key_exists('tags', $validatedData))
        {
            $tags = explode(',', $validatedData['tags']);
        }

        if(array_key_exists('with', $validatedData))
        {
            $with = explode(',', $validatedData['with']);
        }
        else
        {
            $with = [];
        }

        if(array_key_exists('diff_time', $validatedData))
        {
            $date = $validatedData['diff_time'];
        }
        else
        {
            $date = 0;
        }

        if(array_key_exists('category', $validatedData))
        {
            $category = (strtoupper($validatedData['category']));
        }

        //Pretraživamo po tagovima i kategorijama
        if(array_key_exists('tags', $validatedData) && array_key_exists('category', $validatedData))
        {
            if($category == 'NULL')
            {
                return $dish->byTags($tags)->whereNull('category_id')->byDate($date)->byWith($with, $validatedData['per_page']);
            }
            elseif($category == '!NULL')
            {
                return $dish->byTags($tags)->whereNotNull('category_id')->byDate($date)->byWith($with, $validatedData['per_page']);
            }
            else
            {
                return $dish->byTags($tags)->where('category_id', $category)->byDate($date)->byWith($with, $validatedData['per_page']);
            }
        }

        //Pretraživamo po tagovima
        elseif(array_key_exists('tags', $validatedData) && !array_key_exists('category', $validatedData))
        {
            return $dish->byTags($tags)->byDate($date)->byWith($with, $validatedData['per_page']);
        }

        //Pretraživamo po kategoriji
        elseif(array_key_exists('category', $validatedData) && !array_key_exists('tags', $validatedData))
        {
            if($category == 'NULL')
            {
                return $dish->whereNull('category_id')->byDate($date)->byWith($with, $validatedData['per_page']);
            }
            elseif($category == '!NULL')
            {
                return $dish->whereNotNull('category_id')->byDate($date)->byWith($with, $validatedData['per_page']);
            }
            else
            {
                return $dish->where('category_id', $category)->byDate($date)->byWith($with, $validatedData['per_page']);
            }
        }

        //Izlistamo sva jela
        else
        {
            return $dish->byDate($date)->byWith($with, $validatedData['per_page']);
        }
    }
}