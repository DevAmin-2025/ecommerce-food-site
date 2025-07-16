@extends('layout.master')
@section('title', 'Register')

@section('content')
    <section class="register_section layout_padding">
        <div class="container">
            <div class="card">
                <h5 class="card-header">ثبت نام</h5>
                <div class="card-body">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">نام</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                            <div class="form-text text-danger">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
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
                        <div class="mb-3">
                            <label class="form-label">تکرار رمزعبور</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-secondary">ارسال</button>
                        <a href="{{ route('login') }}" class="fs-6 ms-3" style="text-decoration: none; color: red;">از قبل حساب کاربری دارید؟</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
