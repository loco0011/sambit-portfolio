<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppController extends Controller
{
    // Serves the admin SPA for every /admin URL; the SPA shows the lock screen when logged out.
    public function __invoke(): View
    {
        return view('admin', ['user' => Auth::user()?->only('name', 'email')]);
    }
}
