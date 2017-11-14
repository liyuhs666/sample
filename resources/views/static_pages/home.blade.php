@extends('layouts.default')

@section('content')
  <div class="jumbotron"  >
    <h1> Laravel</h1>
    <p class="lead">
     What you're seeing right now <a href="https://fsdhub.com/books/laravel-essential-training-5.1">A primer on Laravel</a> Sample project home page.
    </p>
    <p>
      Every thing will be begin Here!
    </p>
    <p>
      <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
    </p>
  </div>
@stop