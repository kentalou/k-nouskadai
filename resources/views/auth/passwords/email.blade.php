@extends('layouts.auth')

@section('title', 'パスワードリセット') <!-- ページのタイトルを指定 -->

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>パスワードリセット</h1>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">リセットリンクを送信</button>
            </div>
        </form>
    </div>
</div>
@endsection
