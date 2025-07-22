@extends('layouts.category_game')

@php
    $backUrl = '/scenario';
    $mainUrl = '/main';
@endphp


@section('content')
    @include('layouts.scenario.show')

    @include('layouts.scenario.script.show')
@endsection

