@extends('layouts.product') <!-- 親テンプレートを継承 -->

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/productJr.css') }}"> <!-- 子テンプレート専用CSS -->
@endsection

@section('content')
<div class="content-wrapper border rounded p-4 bg-light">
    <!-- 各画面固有の追加コンテンツ -->
    <div class="row">
        @yield('additional-content')
    </div>
</div>
@endsection
