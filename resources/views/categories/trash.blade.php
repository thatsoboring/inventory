@extends('layouts.app')


@section('content')
        <category-trash-view
                base-url="{{ url('/') }}"
                back-link="{{ url('/category') }}"></category-trash-view>
@endsection
