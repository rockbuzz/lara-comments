<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Traits\Commentable;
use Rockbuzz\LaraComments\Contracts\Commentable as CommentableInterface;

class Post extends Model implements CommentableInterface
{
    use Commentable;

    protected $fillable = ['title', 'content'];
}
