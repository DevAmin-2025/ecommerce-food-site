@extends('layout.master')
@section('title', 'Change Password')

@section('content')
    <section class="profile_section layout_padding">
        <div class="container">
            <div class="row">
                <x-profile.sidebar />
                <div class="col-sm-12 col-lg-9">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">تغییر رمز عبور</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">رمزعبور فعلی</label>
                                    <input type="password" name="current_password" class="form-control"
                                        placeholder="رمز فعلی را وارد کنید">
                                    @error('current_password')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">رمزعبور جدید</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="رمز جدید را وارد کنید">
                                    @error('password')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">تکرار رمزعبور</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control" placeholder="رمز را دوباره وارد کنید">
                                </div>
                                <button type="submit" class="btn btn-secondary">ارسال</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
