<?php

namespace Rockbuzz\LaraComments\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Traits\Commentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use Commentable;

    protected $guarded = [];
}