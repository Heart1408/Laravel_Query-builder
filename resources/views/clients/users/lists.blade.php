@include('sweetalert::alert')
@extends('layouts.clients')

@section('content')
    @if (session('msg'))
        <div class="alert alert-success">{{session('msg')}}</div>
    @endif
    <h1>List user</h1>
    <a href="{{route('users.add')}}" class="btn btn-primary">Add user</a>
    <hr>
    <form action="" method="GET" class="mb-3">
        <div class="row">
            <div class="col-3">
                <select class="form-control" name="status">
                    <option value="0">All status</option>
                    <option value="active" {{ request()->status=='active'?'selected':false }}>Active</option>
                    <option value="inactive" {{ request()->status=='inactive'?'selected':false }}>Inactive</option>
                </select>
            </div>
            <div class="col-3">
                <select class="form-control" name="group_id">
                    <option value="0">All group</option>
                    @if (!empty(getAllGroups()))
                        @foreach (getAllGroups() as $item)
                            <option value="{{ $item->id }}" {{ request()->group_id==$item->id?'selected':false }}>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-4">
                <input type="text" name="keyword" class="form-control" 
                placeholder="Key search..." value="{{ request()->keyword }}">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btn-block">Search</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th><a href="?sort-by=name&sort-type={{$sortType}}">Name</a></th>
                <th><a href="?sort-by=email&sort-type={{$sortType}}">Email</a></th>
                <th>Group</th>
                <th>Status</th>
                <th width="10%"><a href="?sort-by=created_at&sort-type={{$sortType}}">Time</a></th>
                <th width="5%">Edit</th>
                <th width="5%">Delete</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($usersList))
                @foreach ($usersList as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->getGroup($item->id)}}</td>
                    <td>{!! $item->status==0?'<button class="btn btn-danger btn-sm">Inactive</button>':
                        '<button class="btn btn-success btn-sm">Active</button>' !!}</td>
                    <td>{{$item->created_at}}</td>
                    <td><a href="{{ route('users.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-sm">Edit</a></td>
                    <td><a onclick="return confirm('Are you sure you want to delete!')" href="{{ route('users.delete', ['id'=>$item->id]) }}" class="btn btn-danger btn-sm">Delete</a></td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="6">Not user</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        {{$usersList->links()}}
    </div>
@endsection
