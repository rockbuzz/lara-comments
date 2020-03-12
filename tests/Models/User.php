<?php

namespace Tests\Models;

use Rockbuzz\LaraComments\Traits\HasComments;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Rockbuzz\LaraComments\Contracts\HasComments as HasCommentsInterface;

class User extends Authenticatable implements HasCommentsInterface
{
    use HasComments;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
