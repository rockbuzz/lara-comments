<?php

namespace Rockbuzz\LaraComments\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasComments
{
    public function comments(): MorphMany;
}
