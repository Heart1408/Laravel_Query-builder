@extends('layouts.clients')

@section('content')
    @if (session('msg'))
        <div class="alert alert-success">{{session('msg')}}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">Input invalidate!</div>
    @endif
    <h1>Edit user</h1>
    <form action="{{ route('users.post-edit') }}" method="POST">
    @csrf
        <div class="mb-3">
            <label for="">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name" 
              value="{{ old('name') ?? $userDetail->name }}">
            @error('name')
                <span style="color: red;">{{$message}}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="">Email</label>
            <input type="text" class="form-control" name="email" placeholder="Enter email" 
            value="{{old('email') ?? $userDetail->email }}">
            @error('email')
                <span style="color: red;">{{$message}}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="">Group</label>
            <select class="form-control" name="group_id" id="">
                <option value="0">Select group</option>
                @if (!empty($allGroups))
                    @foreach ($allGroups as $item)
                        <option value="{{ $item->id }}" {{ old('group_id') 
                            ==$item->id || $userDetail->group_id==$item->id?'selected':false }}>{{ $item->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('group_id')
                <span style="color: red;">{{$message}}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="">Status</label>
            <select class="form-control" name="status" id="">
                <option value="0" {{ old('status') == 0 || $userDetail->status==0?'selected':false }}>Inactive</option>
                <option value="1" {{ old('status') == 1 || $userDetail->status==1?'selected':false }}>Active</option>
            </select>
            @error('status')
                <span style="color: red;">{{$message}}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{route('users.index')}}" class="btn btn-warning">Cancel</a>
    </form>
    <hr>
@endsection
