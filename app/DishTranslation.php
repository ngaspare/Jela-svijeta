<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
