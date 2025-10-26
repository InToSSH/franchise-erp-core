<?php

namespace App\Http\Controllers;

use App\Traits\DatatableTrait;
use App\Traits\DestroyModelTrait;

abstract class Controller
{
    use DatatableTrait, DestroyModelTrait;
}
