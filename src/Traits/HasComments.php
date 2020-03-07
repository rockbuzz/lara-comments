<?php

namespace Rockbuzz\LaraComments\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(
            config('comments.models.comment'),
            config('comments.tables.morph_names.commenter')
        );
    }
}
