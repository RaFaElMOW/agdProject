<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Support\Auth;

class DashboardController
{
    public function index(Request $request): void
    {
        View::output('admin/dashboard', [
            'user' => Auth::user(),
        ], 'admin/layout');
    }
}
