@extends('layouts.auth')

@section('title', 'パスワード確認') <!-- ページのタイトルを指定 -->

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>パスワード確認</h1>
        <p>続行するには、パスワードを確認してください。</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- パスワード入力フィールド -->
            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- ボタン -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">パスワードを確認</button>
                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">パスワードをお忘れですか？</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
