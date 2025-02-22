@extends('layouts.auth')

@section('title', 'メールアドレス確認')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1>メールアドレス確認</h1>
        <p>メールに送信されたリンクをクリックしてメールアドレスを確認してください。</p>
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">確認メールを再送信</button>
            </div>
        </form>
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                新しい確認リンクがメールアドレスに送信されました。
            </div>
        @endif
    </div>
</div>
@endsection
