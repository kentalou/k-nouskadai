@extends('layouts.auth')

@section('title', 'パスワードリセット')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>パスワードリセット</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">新しいパスワード</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password-confirm">パスワード確認</label>
                <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">パスワードをリセット</button>
            </div>
        </form>
    </div>
</div>
@endsection
