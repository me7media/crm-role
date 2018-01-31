@extends('layouts.app')

@section('title', '| Users')
@if(!$month) $month = date('m') @endif
@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h2><i class="fa fa-users"></i> User Administration</h2>
    <a href="{{ route('admin.create') }}" class="btn btn-success btn-xs pull-right">Add User</a>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Time for Month</th>
                    <th>User Roles</th>
                    <th>Operations</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                @if ($user->hasRole('admin'))
                @else
                @php
                    $sum = 0;
                    $u = $user->datas()->whereMonth('created_at', $month)->get();
                    foreach($u as $t){
                        $sum = $sum + $t->time;
                    }
                @endphp
                <tr>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $sum }}</td>
                    <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}

                    <td>
                    <a href="{{ route('admin.profile', $user->id) }}" class="btn btn-success btn-xs">Watch</a>

                    <a href="{{ route('admin.edit', $user->id) }}" class="btn btn-info btn-xs ">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.destroy', $user->id] ]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs ']) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>

        </table>
    </div>

    

</div>
<input type="hidden" class="ajax" />

@endsection