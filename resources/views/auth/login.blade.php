@extends('layouts.auth')

@section('title', 'ユーザーログイン画面')
@section('route', route('login'))

@php
    $fields = [
        'password' => 'パスワード',
        'email' => 'アドレス'
    ];

    $btnOrange = [
        'href' => route('register'),
        'label' => '新規登録'
    ];

    $btnSkyblue = [
        'type' => 'submit',
        'label' => 'ログイン'
    ];
@endphp
