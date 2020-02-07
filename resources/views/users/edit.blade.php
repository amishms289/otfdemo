@extends('layouts.app')

@section('title', '| Edit User')

@section('content')

    <div class='col-lg-4 col-lg-offset-4'>

        <h1><i class='fa fa-user-plus'></i> Edit User: {{$user->first_name.' '.$user->last_name}}</h1>
        <hr>

        {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT','files'=>'true')) }}{{-- Form model binding to automatically populate our fields with user data --}}

        <div class="form-group">
            {{ Form::label('first_name', 'First Name') }}
            {{ Form::text('first_name', old('first_name'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('last_name', 'Last Name') }}
            {{ Form::text('last_name', old('last_name'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('phone', 'Phone') }}
            {{ Form::text('phone', old('phone'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', null, array('class' => 'form-control')) }}
        </div>

        <h5><b>Give Role</b></h5>

        <div class='form-group'>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>

            @endforeach
        </div>

       {{-- <div class="form-group">
            {{ Form::label('password', 'Password') }}<br>
            {{ Form::password('password', array('class' => 'form-control')) }}

        </div>

        <div class="form-group">
            {{ Form::label('password', 'Confirm Password') }}<br>
            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

        </div>--}}

        <div class="form-group {{ $errors->has('profile_photo') ? ' has-error' : '' }}">
            <label for="profile_photo" class="control-label">Profile Photo</label>
            <input id="profile_photo" type="file" class="form-control" name="profile_photo">

            @if ($errors->has('profile_photo'))
                <span class="help-block">
                    <strong>{{ $errors->first('profile_photo') }}</strong>
                </span>
            @endif

            @if(isset($user->profile_photo) && $user->profile_photo && file_exists(public_path('profile_photo/').$user->profile_photo))
                <img src="{{url('/').'/profile_photo/'.$user->profile_photo}}" alt="" width="150" height="150" style="margin-top: 10px; border-radius: 50%" />
            @endif
        </div>

        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>

@endsection