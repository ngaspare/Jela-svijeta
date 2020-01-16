<?php

use Illuminate\Database\Seeder;
use App\Dish;

class CustomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = ['hr', 'en', 'de'];

        $categories = factory(App\Category::class, 10)->create();

        $ingredients = factory(App\Ingredient::class, 10)->create();

        $tags = factory(App\Tag::class, 10)->create();

        foreach($locales as $locale)
        {
            factory(App\Locale::class)->create(['locale' => $locale]);
        }

        foreach($categories as $category)
        {
            $dishes = factory(App\Dish::class)->create([
                'category_id' => $category->id,
                'created_at' => $category->created_at
                ]);
        }

        $dishes = Dish::get();

        foreach($ingredients as $ingredient)
        {
            foreach($dishes as $dish)
            {
                $dish->ingredients()->attach($ingredient->id);
            }
        }
   
        foreach($tags as $tag)
        {
            foreach($dishes as $dish)
            {
                $dish->tags()->attach($tag->id);
            }
        }

        foreach($locales as $locale){
            foreach($categories as $category)
            {
                factory(App\CategoryTranslation::class)->create([
                    'category_id' => $category->id,
                    'locale' => $locale,
                    'created_at' => $category->created_at
            ]);
            }
            foreach($dishes as $dish)
            {
                factory(App\DishTranslation::class)->create([
                    'dish_id' => $dish->id,
                    'locale' => $locale,
                    'created_at' => $dish->created_at
                ]);
            }

            foreach($tags as $tag)
            {
                factory(App\TagTranslation::class)->create([
                    'tag_id' => $tag->id,
                    'locale' => $locale,
                    'created_at' => $tag->created_at
                ]);
            }

            foreach($ingredients as $ingredient)
            {
                factory(App\IngredientTranslation::class)->create([
                    'ingredient_id' => $ingredient->id,
                    'locale' => $locale,
                    'created_at' => $ingredient->created_at
                ]);
            }
        }

        

    }
}
