@extends('layouts.full')

@section('title',' - Page not found')
@section('bg_color','0,0,0,.85')
@section('content_class','xs-w300 sm-w500 md-w700 text-center c-fff')
@section('fh', 'f-height')

@section('content')
<div class="ab-wrap">
    <div class="content text-center c-fff">
        <i class="fa fa-ban fa-5x c-c00"></i>

        <h2 class="text-uppercase mb20">
            404, Page not found on {{config('app.name')}}
        </h2>

        <p>
            <a href="{{ URL::previous() }}" class="btn btn-default" title="Go back to previous page"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Back</a>
            <a href="{{ route('home') }}" class="btn btn-default" title="Go to homepage"><i class="fa fa-home fa-fw"></i>&nbsp; Home</a>
        </p>
    </div>
</div>
@endSection