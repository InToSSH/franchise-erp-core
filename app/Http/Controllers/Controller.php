<?php

namespace App\Http\Controllers;

use App\Traits\DatatableTrait;
use App\Traits\DestroyModelTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use DatatableTrait, DestroyModelTrait, AuthorizesRequests;
}
