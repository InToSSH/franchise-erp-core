<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function setDarkMode(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->dark_mode = $request->input('enabled');
        $user->save();

        return response()->json(['enabled' => $user->dark_mode]);
    }
}
