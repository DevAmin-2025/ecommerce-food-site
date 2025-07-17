@extends('layout.master')
@section('title', 'Profile')

@section('content')
    <section class="profile_section layout_padding">
        <div class="container">
            <div class="row">
                <x-profile.sidebar />
                <div class="col-sm-12 col-lg-9">
                    <form class="vh-70" method="POST" action="{{ route('profile.update', Auth::id()) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col col-md-6">
                                <label class="form-label">نام و نام خانوادگی</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ Auth::user()->name }}" />
                                <div class="form-text text-danger">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="col col-md-6">
                                <label class="form-label">ایمیل</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ Auth::user()->email }}" />
                                <div class="form-text text-danger">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">ویرایش</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
