<?php

namespace Rockbuzz\LaraComments\Tests\Models;

use Rockbuzz\LaraComments\Traits\Commenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Commenter;
    use HasFactory;

    protected $guarded = [];
}