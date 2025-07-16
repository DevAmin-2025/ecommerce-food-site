@extends('layout.master')
@section('title', 'Login')

@section('content')
    <section class="login_section layout_padding">
        <div class="container">
            <div class="card">
                <h5 class="card-header">ورود</h5>
                <div class="card-body">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                            <div class="form-text text-danger">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رمزعبور</label>
                            <input type="password" name="password" class="form-control">
                            <div class="form-text text-danger">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">ارسال</button>
                        <a href="{{ route('forget.password') }}" class="fs-6 ms-3" style="text-decoration: none; color: red;">رمزعبور را فراموش کرده اید؟</a>
                        <a href="{{ route('register') }}" class="fs-6 ms-3" style="text-decoration: none; color: red;">هنوز ثبت نام نکرده اید؟</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
