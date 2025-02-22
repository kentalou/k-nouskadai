@extends('layouts.auth')

@section('title', 'ユーザー新規登録画面')
@section('route', route('register'))

@php
    $fields = [
        'name' => 'ユーザー名',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認用）',
        'email' => 'アドレス'
    ];

    $btnOrange = [
        'type' => 'submit',
        'label' => '新規登録'
    ];

    $btnSkyblue = [
        'href' => route('login'),
        'label' => '戻る'
    ];
@endphp
