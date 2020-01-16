<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}
