<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->hasAnyRole(Role::all())) {
            $user = User::all()->count();
            $roleName = ($user < 1) ? 'Admin' : 'User';
            Auth::user()->assignRole($roleName);
        }

        return view('home');
    }
}
