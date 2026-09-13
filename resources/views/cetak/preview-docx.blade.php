@extends('layouts.print')

@section('title', $title)

@section('kembali_url', $kembali_url)

@section('unduh_url', $unduh_url)

@section('content')
    {!! $html !!}
@endsection