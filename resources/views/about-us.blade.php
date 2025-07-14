@extends('layout.master')
@section('title', 'About Us')

@section('content')
@php
    $item = App\Models\AboutUs::first();
@endphp
<section class="about_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6 ">
                <div class="img-box">
                    <img src="{{ imageUrl($item->image_address) }}" alt="About-us Image" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-box">
                    <div class="heading_container">
                        <h2>
                            {{ $item->title }}
                        </h2>
                    </div>
                    <p>
                        {{ $item->body }}
                    </p>
                    <a href="{{ $item->link_address }}">
                        {{ $item->link_text }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
