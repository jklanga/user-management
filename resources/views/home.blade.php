@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#interests">Interests</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane container active" id="home">
                            {!! Form::model($user, ['method' => 'POST','route' => ['user.update'], 'role'=>'form','class'=>'form-horizontal']) !!}
                            @csrf

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <input type="email" readonly="true" class="form-control" value="{{ $user->email }}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    {{ Form::text('name', Request::old('name'), array('class'=>'form-control ng-untouched ng-dirty ng-valid ng-valid-required', 'placeholder'=>'name', 'required'=>'required') ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    {{ Form::text('surname', Request::old('surname'), array('class'=>'form-control ng-untouched ng-dirty ng-valid ng-valid-required', 'placeholder'=>'surname', 'required'=>'required') ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    {{ Form::text('mobile_number', Request::old('mobile_number'), array('class'=>'form-control ng-untouched ng-dirty ng-valid ng-valid-required', 'placeholder'=>'Mobile number', 'required'=>'required') ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    {{ Form::text('id_number', Request::old('id_number'), array('class'=>'form-control ng-untouched ng-dirty ng-valid ng-valid-required', 'placeholder'=>'ID number', 'required'=>'required') ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    {{ Form::date('dob', Request::old('dob'), array('class'=>'form-control date-picker ng-untouched ng-dirty ng-valid ng-valid-required', 'placeholder'=>'Date Of Birth', 'required'=>'required') ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <select name="language_id" class="form-control">
                                        <option value="">--Select Language--</option>
                                        @foreach($languages as $language)
                                            <option @if(isset($user) && (int)$user->language_id === $language->id) selected @endif value="{{ $language->id }}">
                                                {{ $language->language }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="tab-pane container fade" id="interests">
                            @if (isset($user->interests))
                                {{ Form::model($user->interests, array('route' => array('user.interests','id='.$user->id), 'id' => 'userInterests', 'role'=>'form','class'=>'form-horizontal')) }}
                            @else
                                {{ Form::open(array('route' => 'user.interests', 'id' => 'UserInterests', 'role'=>'form','class'=>'form-horizontal')) }}
                            @endif
                                @csrf

                            <div class="form-group">
                                <ul class="list-group">
                                    @foreach($interests as $interest)
                                        <li class="list-group-item">
                                            <input id="interestId{{$interest->id}}"
                                                   @if((Request::old("user_interests")) && in_array($interest->id,Request::old("user_interests")) || array_key_exists($interest->id, $userInterests))
                                                   checked
                                                   @endif
                                                   type="checkbox" name="user_interests[]" value="{{ $interest->id }}" >
                                            {{ $interest->interest }}
                                        </li>
                                    @endforeach
                                    </ul>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
