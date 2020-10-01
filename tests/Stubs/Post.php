<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Traits\Commentable;

class Post extends Model
{
    use Commentable;

    protected $guarded = [];
}
