<?php

namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Traits\HasComments;

class Commenter extends Model
{
    use HasComments;

    protected $guarded = [];
}
