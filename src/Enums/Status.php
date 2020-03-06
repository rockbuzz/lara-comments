<?php

namespace Rockbuzz\LaraComments\Enums;

use BenSampo\Enum\Enum;

class Status extends Enum
{
    const PENDING = 1;
    const APPROVED = 5;
    const DISAPPROVED = 9;
}
