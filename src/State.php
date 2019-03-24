<?php

namespace Rockbuzz\LaraComments;

use BenSampo\Enum\Enum;

final class State extends Enum
{
    const PENDING = 0;
    const APPROVED = 1;
    const UNAPPROVED = 2;
}
