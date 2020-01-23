<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Dish;
use App\Category;
use App\Tag;
use App\Ingredient;

class CustomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $locales = ['hr', 'en', 'de'];

        $categories = factory(App\Category::class, 5)->create();

        $ingredients = factory(App\Ingredient::class, 10)->create();

        $tags = factory(App\Tag::class, 10)->create();

        foreach($locales as $locale)
        {
            factory(App\Locale::class)->create(['locale' => $locale]);
        }

        for($i = 0; $i < 20; $i++)
        {                
            $dishes = factory(App\Dish::class)->create([
                'category_id' => $faker->boolean(70) ? Category::all()->random()->id : null,
                'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = '-1 years')
                ]);
        }

        foreach(Dish::withTrashed()->get() as $dish)
        {
            $dish->status = $dish->deleted_at ? 'deleted' : ($dish->updated_at ? 'modified' : 'created');
            $dish->save();        
        }

        foreach(Dish::withTrashed()->get() as $dish)
        {
            $dish->ingredients()->sync(Ingredient::pluck('id')->random(rand(1,5)));
            $dish->save();
        }

        foreach(Dish::withTrashed()->get() as $dish)
        {
            $dish->tags()->sync(Tag::pluck('id')->random(rand(1,5)));
            $dish->save();
        }

        $dishes = Dish::withTrashed()->get();
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
