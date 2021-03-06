<?php

namespace Tests\Models;

use Rockbuzz\LaraComments\Traits\Commenter;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Commenter;

    protected $guarded = [];
}
