<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Commentable;

class Post extends Model
{
    use Commentable;

    protected $fillable = ['title', 'content'];
}