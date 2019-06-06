@extends('layouts.app')


@section('content')

    <category-view link-trash="{{ url('/category/trash') }}" base-url="{{ url('/') }}"
        ></category-view>
@endsection