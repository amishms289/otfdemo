<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::all()->count();
        if (!($user == 1)) {
            if (!Auth::user()->hasPermissionTo('View User')) //If user does //not have this permission
            {
//                abort('401');
                return redirect('/home');
            }
        }

        if ($request->is('users/create'))
        {
            if (!Auth::user()->hasPermissionTo('Add User'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('users'))
        {
            if (!Auth::user()->hasPermissionTo('View User'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('users/*/edit'))
        {
            if (!Auth::user()->hasPermissionTo('Edit User'))
            {
                return redirect('/home');
            }
        }

        if ($request->isMethod('Delete'))
        {
            if (!Auth::user()->hasPermissionTo('Delete User'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('permissions/create'))
        {
            if (!Auth::user()->hasPermissionTo('Add Permission'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('permissions'))
        {
            if (!Auth::user()->hasPermissionTo('View Permission'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('permissions/*/edit'))
        {
            if (!Auth::user()->hasPermissionTo('Edit Permission'))
            {
                return redirect('/home');
            }
        }

        if ($request->isMethod('Delete'))
        {
            if (!Auth::user()->hasPermissionTo('Delete Permission'))
            {
                return redirect('/home');
            }
        }

        if ($request->is('roles/create'))
        {
            if (!Auth::user()->hasPermissionTo('Add Role'))
            {
                return redirect('/home');
            }
        }

//        if ($request->is('roles'))
//        {
//            if (!Auth::user()->hasPermissionTo('View Role'))
//            {
//                return redirect('/home');
//            }
//        }

        if ($request->is('roles/*/edit'))
        {
            if (!Auth::user()->hasPermissionTo('Edit Role'))
            {
                return redirect('/home');
            }
        }

        if ($request->isMethod('Delete'))
        {
            if (!Auth::user()->hasPermissionTo('Delete Role'))
            {
                return redirect('/home');
            }
        }

        return $next($request);
    }
}