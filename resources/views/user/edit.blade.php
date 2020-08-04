@extends('layouts.app')

@section('content')
@if (isset($user))
	{{ Form::model($user, ['route' => ['user.edit','id='.$user->id], 'id' => 'userEdit', 'role'=>'form','class'=>'form-horizontal']) }}
@else
	{{ Form::open(array('route' => 'user.edit', 'id' => 'userNew', 'role'=>'form','class'=>'form-horizontal')) }}
@endif
<h3>User: <small> @if (isset($user)) {{ $user->name }} @else New @endif</small></h3>

<div class="col-sm-12 row">
    <div class="col-sm-6">
        <div class="form-group">
            {{ Form::label('name', 'Name', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::text('name', Request::old('name'), ['class' => 'form-control'] ) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('surname', 'Surname', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::text('surname', Request::old('surname'), ['class' => 'form-control'] ) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('id_number', 'ID number', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::text('id_number', Request::old('id_number'), ['id'=>'id-number', 'class' => 'form-control'] ) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('mobile_number', 'Mobile number', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::text('mobile_number', Request::old('mobile_number'), ['class' => 'form-control'] ) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('dob', 'Date of birth', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::date('dob', Request::old('dob'), ['class' => 'datepicker form-control'] ) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('language_id', 'Language', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
                <select name="language_id" class="form-control">
                    <option value="">--Select--</option>
                    @foreach($languages as $language)
                        <option @if((isset($user) && $user->language_id == $language->id) || Request::old('language_id') == $language->id) selected @endif value="{{ $language->id }}">
                            {{ $language->language }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('role', 'Role', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
                @foreach(['user', 'manager', 'admin'] as $key => $value)
                <label class="radio-inline">
                    <input type="radio" @if((Request::old('role') == $value) || (isset($user) && $user->role == $value)) checked @endif name="role" value="{{ $value }}"> {{ $value }}
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            {{ Form::label('email', 'Email', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::email('email', Request::old('email'), ['class' => 'form-control'] ) }}
            </div>
        </div>
        @if (!isset($user))
        <div class="form-group">
            {{ Form::label('new-password', 'Password', ['class' => 'cm-required col-sm-6']) }}
            <div class="col-sm-8">
            {{ Form::password('new-password', ['readonly' => true, 'class' => 'form-control new-password-field'] ) }}
            </div>
        </div>
        @endif
    </div>
</div>
    <div align="center" class="form-group">
        @can('isAdmin')
        <button class="btn btn-md btn-success" type="submit">Submit</button>
        @endcan
        <a href="{{URL::to('/user/list')}}" class="btn btn-md btn-default">Cancel</a>
    </div>
@if (isset($user))
<input type="hidden" name="userId" value="{{ $user->id }}">
@endif
{{ Form::close() }}
@endsection
