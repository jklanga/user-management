@extends('layouts.app')

@section('content')
    <a href="{{URL::to('/user/list')}}" class="btn btn-default float-right">Back</a>
    <div class="container">
        <h3>{{ $user->name }}'s <small>interests</small></h3>
        <ul class="list-group">
            @if (!$user->interests->count())
                <p>no interests</p>
            @else
                @foreach($user->interests as $interest)
                    <li class="list-group-item">
                        <label>{{ $interest->interest }}</label>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
@endsection
