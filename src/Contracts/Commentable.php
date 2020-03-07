<?php

namespace Rockbuzz\LaraComments\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Commentable
{
    public function comments(): MorphMany;

    public function asPending($comment): void;

    public function approve($comment): void;

    public function disapprove($comment): void;
}
