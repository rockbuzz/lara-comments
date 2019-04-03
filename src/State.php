<?php

namespace Rockbuzz\LaraComments;

use BenSampo\Enum\Enum;

class State extends Enum
{
    const PENDING = 1;
    const APPROVED = 2;
    const DISAPPROVED = 3;
}
