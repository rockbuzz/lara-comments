<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Traits\HaveComments;

class Commenter extends Model
{
    use HaveComments;

    protected $guarded = [];
}
