<?php

namespace Rockbuzz\LaraComments\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HaveComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(
            config('comments.models.comment'),
            config('comments.tables.morph_names.commenter')
        );
    }

    public function hasComments()
    {
        return $this->comments()->exists();
    }
}
