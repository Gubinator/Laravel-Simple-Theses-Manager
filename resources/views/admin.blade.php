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

                    @if (Auth::user()->name)
                        {{ __('You are logged in ') }} {{ Auth::user()->name }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex flex-column align-items-center pt-5">
<h1>Admin panel</h1>
@foreach ($users as $user)
<div class=" card container__user mt-2 mb-2 pt-2 pb-2" style="display: flex; flex-flow: column wrap; width:40%; align-items:start; padding-left: 8px;">
    <span class="user__label--name pb-1 pl-2">Name: {{$user->name}}</span>
    <span class="user__label--role pb-1 pl-2">Role: {{$user->role}}</span>
    <span class="user__label--input pl-2">Change to: </span>
    @if ($user->role == "student")
    <form action="{{url('admin/'.$user->id)}}" method="POST">
        @csrf
        @method('PUT')
        <input type="submit" value="professor" name="role" class="btn btn-secondary"/>
    </form>
    @else
    <form action="{{url('admin/'.$user->id)}}" method="POST">
        @csrf
        @method('PUT')
        <input type="submit" class="btn btn-secondary" value="student" name="role"/>
    </form>
    @endif
</div>
@endforeach
</div>
@endsection