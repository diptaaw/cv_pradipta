<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class BaseAdminController extends Controller
{
    public function __construct()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            redirect()->route('admin.login')->send();
        }
    }
}
