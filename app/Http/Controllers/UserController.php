<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;
use File;
class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //Get all users and pass it to the view
        $users = User::all();
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //Get all roles and pass it to the view
        $roles = Role::get();
        return view('users.create', ['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Validate name, email and password fields
        $this->validate($request, [
            'first_name'=>'required|max:120',
            'last_name'=>'required|max:120',
            'phone'=>'nullable|numeric|max:15',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = new User($request->input());
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
//        $user->password = $request->get('password');

        if($request->hasFile('profile_photo')) {
            $path = public_path('profile_photo/');

//            //remove old file
            if($user && $user->profile_photo && file_exists($path.'/'.$user->profile_photo)) {
                unlink($path.'/'.$user->profile_photo);
            }

            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            $profile_photo_name = $request->file('profile_photo');
            $filename = $profile_photo_name->getClientOriginalName();
            $new_name = time().'_profile_'.$filename;
            $user->profile_photo = $new_name;
            $profile_photo_name->move($path, $new_name);
        }

        $user->save();

//        $user = User::create($request->only('email', 'first_name', 'last_name', 'phone', 'password')); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }
        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
                'User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles

        return view('users.edit', compact('user', 'roles')); //pass user and roles data to view

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

        //Validate name, email and password fields
        $this->validate($request, [
            'first_name'=>'required|max:120',
            'last_name'=>'required|max:120',
            'phone'=>'nullable|numeric|max:15',
            'email'=>'required|email|unique:users,email,'.$id,
//            'password'=>'required|min:6|confirmed',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
//        $user->password = $request->get('password');

        if($request->hasFile('profile_photo')) {
            $path = public_path('profile_photo/');

//            //remove old file
            if($user && $user->profile_photo && file_exists($path.'/'.$user->profile_photo)) {
                unlink($path.'/'.$user->profile_photo);
            }

            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }

            $profile_photo_name = $request->file('profile_photo');
            $filename = $profile_photo_name->getClientOriginalName();
            $new_name = time().'_profile_'.$filename;
            $user->profile_photo = $new_name;
            $profile_photo_name->move($path, $new_name);
        }

//        $input = $request->only(['first_name', 'last_name', 'phone', 'email', 'password']); //Retreive the name, email and password fields
//        $user->fill($input)->save();

        $userArr = json_decode(json_encode($user), true);

        $user->update($userArr);

        $roles = $request['roles']; //Retreive all roles
        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        }
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
                'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //Find a user with a given id and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
                'User successfully deleted.');
    }
}