<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Locale;
use App\Dish;
use App\CategoryTranslation;
use App\TagTranslation;
use App\IngredientTranslation;
use App\Category;
use App\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class DishController extends Controller
{
    public function index()
    {
        $locales = Locale::distinct('locale')->pluck('locale');
        $tags = TagTranslation::where('locale', app()->getLocale())->get(); 
        $ingredients = IngredientTranslation::where('locale', app()->getLocale())->get();
        $categories = CategoryTranslation::where('locale', app()->getLocale())->get();
     
        return view('jela.index', compact('locales','tags', 'ingredients', 'categories'));
    }

    public function show(Request $request)
    {
        if($request->lang)
        {
            app()->setLocale($request->lang);
        }

        $allDishes = Dish::get();
        $category = $request->category;
        $ingredients = NULL;
        $categoryShow = NULL;
        $tagShow = NULL;
       
        function allTags($tags, $allDishes, $category)
        {
            foreach($tags as $tag)
            {
                foreach($allDishes as $dish)
                {
                    $dishes[] = Tag::where('id', $tag)->firstOrFail()->dishes->where('category_id', $category);
                }
            }

            return $dishes;
                    
        }

        if(!$request->tags)
        {
            return redirect('/jela');
        }

        if($category === "!NULL")
        {
            foreach($request->tags as $tag)
            {
                foreach($allDishes as $dish)
                {
                    $dishes[] = Tag::where('id', $tag)->firstOrFail()->dishes;
                }
            }
        }
        elseif($category === "NULL")
        {
            foreach($allDishes as $dish)
            {
                $dishes[] = $dish->where('category_id', NULL)->get();
            }
            
        }
        else
        {
            $dishes = allTags($request->tags, $allDishes, $category);
        }

        if($request->ingredientShow)
        {
            foreach($dishes as $item)
            {
                foreach($item as $dish)
                {
                    $ingredients = $dish->ingredients()->get();
                }
            }
        }

        if($request->categoryShow)
        {
            foreach($dishes as $item)
            {
                foreach($item as $dish)
                {
                    $categoryShow = Category::where('id', $dish->category_id)->get();               
                }
            }
        }

        if($request->tagShow)
        {
            foreach($dishes as $item)
            {
                foreach($item as $dish)
                {
                    $tagShow = $dish->tags()->get() ;
                }
            }
        }

        $dishes = array_unique($dishes);

        $dishesCollection = collect($dishes);
        $perPage = 25;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $dishesCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($dishesCollection), $perPage);
        $paginatedItems->setPath($request->url());
 
        return view('jela.show')->with(compact('dishesCollection', 'ingredients', 'categoryShow', 'tagShow', 'paginatedItems'));


    }
}
