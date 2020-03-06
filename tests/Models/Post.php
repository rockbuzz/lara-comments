<?php

namespace Tests\Models;

use Rockbuzz\LaraComments\Commentable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Commentable;

    protected $fillable = ['title', 'content'];
}
