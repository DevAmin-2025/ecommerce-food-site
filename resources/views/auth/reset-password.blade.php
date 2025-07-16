@extends('layout.master')
@section('title', 'Reset Password')

@section('content')
    <section class="register_section layout_padding">
        <div class="container">
            <div class="card">
                <h5 class="card-header">تغییر رمزعبور</h5>
                <div class="card-body">
                    <form action="{{ route('reset.password.post') }}" method="POST">
                        @csrf
                        <input type="hidden", name="token", value="{{ $token }}">
                        <div class="mb-3">
                            <label class="form-label">رمزعبور جدید</label>
                            <input type="password" name="password" class="form-control">
                            <div class="form-text text-danger">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تکرار رمزعبور</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-secondary">ارسال</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
