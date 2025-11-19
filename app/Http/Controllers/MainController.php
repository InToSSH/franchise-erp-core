<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;

class MainController extends Controller
{
    public function dashboard()
    {
        if (
            auth()->user()->can('sales.orders.view')
        ) {
            return redirect()->route('sales.orders.index');
        }

        return Inertia::render('Dashboard');
    }
}
