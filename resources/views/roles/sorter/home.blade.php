@extends('layouts.app')

@section('content')
    <registers-component :role="'sorter'" :registers="{{$students}}" :userid="{{auth()->user()->id}}"></registers-component>
@endsection