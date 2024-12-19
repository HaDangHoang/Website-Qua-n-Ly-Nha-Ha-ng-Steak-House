<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TraitCRUD;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    use TraitCRUD;

    public function index()
    {
        return view('admin.dashboard.index');
    }

    /**
     * Display the login page.
     */
    public function logon()
    {
        return view('admin.logon');
    }
}
