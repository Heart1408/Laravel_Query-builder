@extends('layouts.clients')

@section('sidebar')
    @parent
    <p>Home</p>
@endsection

@section('content')
    <h3>Trang chur</h3>
    @include('clients.contents.slide')
@endsection

@section('css')
  <style>
    
  </style>
@endsection

@section('js')
    <script>

    </script>
@endsection
