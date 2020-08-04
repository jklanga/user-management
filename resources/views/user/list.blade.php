@extends('layouts.app')

@section('content')
    <h3>List of users</h3>
    @canany(['isAdmin', 'isManager'])
    <a href="{{url('user/edit')}}" class="btn btn-primary float-right">New User</a>
    @endcan
    <table class="table jquery-table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Role</th>
            <th>Interests</th>
            @can ('isAdmin')
                <th>Action</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr class="cm-row-status-a">
            <td>
                @canany(['isAdmin', 'isManager'])
                    <a href="{{ URL::to('user/edit?userId=') }}{{ $user->id }}">#{{ $user->id }}&nbsp;</a>
                @else
                    {{ $user->id }}
                @endcanany
            </td>
            <td>
                {{ $user->name }}
            </td>
            <td>
                {{ $user->mobile_number ?? 'missing' }}
            </td>
            <td>
                {{ $user->email }}
            </td>
            <td>
                {{ $user->role }}
            </td>
            <td>
                <a href="{{ URL::to('interests/list?userId=') }}{{ $user->id }}">interests&nbsp;</a>
            </td>
            @can ('isAdmin')
                <td>
                    <a class="deleteUser" name="{{ $user->name }}" id="{{ $user->id }}" href="#">delete</a>
                </td>
            @endcan
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection
