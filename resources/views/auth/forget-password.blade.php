@extends('layout.master')
@section('title', 'Forget Password')

@section('content')
    <section class="login_section layout_padding">
        <div class="container">
            <div class="card">
            <h5 class="card-header">بازیابی رمزعبور</h5>
            <div class="card-body">
                <form action="{{ route('forget.password.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">ایمیل</label>
                        <input type="email" name="email" class="form-control">
                        <div class="form-text text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">ارسال</button>
                </form>
            </div>
        </div>
        </div>
    </section>
@endsection
