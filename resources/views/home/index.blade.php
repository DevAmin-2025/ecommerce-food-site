@extends('layout.master')
@section('title', 'Home Page')

@section('link')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
        crossorigin=""></script>
@endsection

@section('script')
    <script>
        var map = L.map('map').setView([35.700105, 51.400394], 14);
        var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);
        var marker = L.marker([35.700105, 51.400394]).addTo(map)
            .bindPopup('<b>webprog</b>').openPopup();
    </script>
@endsection

@section('content')
    <section class="card-area layout_padding">
        @php
            $features = App\Models\Feature::all();
        @endphp
        <div class="container">
            <div class="row gy-5">
                @foreach ($features as $feature)
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="card-icon-wrapper">
                                    <i class="bi {{ $feature->icon }} fs-2 text-white card-icon"></i>
                                </div>
                                <p class="card-text fw-bold">{{ $feature->title }}</p>
                                <p class="card-text">{{ $feature->body }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="food_section layout_padding-bottom">
        @php
            $categories = App\Models\Category::where('status', 1)->get();
            $pizza_cat_id = App\Models\Category::where('name', 'پیتزا')->first()->id;
            $burger_cat_id = App\Models\Category::where('name', 'برگر')->first()->id;
            $salad_cat_id = App\Models\Category::where('name', 'سالاد و پیش غذا')->first()->id;

            $pizzas = App\Models\Product::where('status', 1)
                ->where('category_id', $pizza_cat_id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
            $burgers = App\Models\Product::where('status', 1)
                ->where('category_id', $burger_cat_id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
            $salads = App\Models\Product::where('status', 1)
                ->where('category_id', $salad_cat_id)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        @endphp
        <div class="container" x-data="{ tab: 1 }">
            <div class="heading_container heading_center">
                <h2>
                    منو محصولات
                </h2>
            </div>

            <ul class="filters_menu">
                @foreach ($categories as $category)
                    <li :class="tab === {{ $loop->index + 1 }} ? 'active' : ''" @click="tab = {{ $loop->index + 1 }}">
                        {{ $category->name }}</li>
                @endforeach
            </ul>

            <div class="filters-content">
                <div x-show="tab === 1">
                    <div class="row grid">
                        @foreach ($pizzas as $pizza)
                            <div class="col-sm-6 col-lg-4">
                                <div class="box">
                                    <div>
                                        <div class="img-box">
                                            <img class="img-fluid"
                                                src="{{ imageUrl($pizza->primary_image, config('app.product_image_path')) }}"
                                                alt="Product Image">
                                        </div>
                                        <div class="detail-box">
                                            <h5>
                                                <a href="{{ route('product.show', $pizza->slug) }}">
                                                    {{ $pizza->name }}
                                                </a>
                                            </h5>
                                            <p>
                                                {{ $pizza->description }}
                                            </p>
                                            <div class="options">
                                                @if ($pizza->is_sale)
                                                    <h6>
                                                        <del>{{ number_format($pizza->price) }}</del>
                                                        <span>
                                                            <span
                                                                class="text-danger">({{ calDiscountPercentage($pizza->price, $pizza->sale_price) }}%)</span>
                                                            {{ number_format($pizza->sale_price) }}
                                                            <span>تومان</span>
                                                        </span>
                                                    </h6>
                                                @else
                                                    <h6>
                                                        {{ $pizza->price }}
                                                        <span>تومان</span>
                                                    </h6>
                                                @endif
                                                <div class="d-flex">
                                                    <a class="me-2" href="">
                                                        <i class="bi bi-cart-fill text-white fs-6"></i>
                                                    </a>
                                                    <a href="">
                                                        <i class="bi bi-heart-fill  text-white fs-6"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="tab === 2">
                    <div class="row grid">
                        @foreach ($burgers as $burger)
                            <div class="col-sm-6 col-lg-4">
                                <div class="box">
                                    <div>
                                        <div class="img-box">
                                            <img class="img-fluid" src="{{ imageUrl($burger->primary_image, config('app.product_image_path')) }}" alt="Product Image">
                                        </div>
                                        <div class="detail-box">
                                            <h5>
                                                {{ $burger->name }}
                                            </h5>
                                            <p>
                                                {{ $burger->description }}
                                            </p>
                                            <div class="options">
                                                @if ($burger->is_sale)
                                                    <h6>
                                                        <del>{{ number_format($burger->price) }}</del>
                                                        <span>
                                                            <span
                                                                class="text-danger">({{ calDiscountPercentage($burger->price, $burger->sale_price) }}%)</span>
                                                            {{ number_format($burger->sale_price) }}
                                                            <span>تومان</span>
                                                        </span>
                                                    </h6>
                                                @else
                                                    <h6>
                                                        {{ $burger->price }}
                                                        <span>تومان</span>
                                                    </h6>
                                                @endif
                                                <div class="d-flex">
                                                    <a class="me-2" href="">
                                                        <i class="bi bi-cart-fill text-white fs-6"></i>
                                                    </a>
                                                    <a href="">
                                                        <i class="bi bi-heart-fill  text-white fs-6"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="tab === 3">
                    <div class="row grid">
                        @foreach ($salads as $salad)
                            <div class="col-sm-6 col-lg-4">
                                <div class="box">
                                    <div>
                                        <div class="img-box">
                                            <img class="img-fluid" src="{{ imageUrl($salad->primary_image, config('app.product_image_path')) }}" alt="Product Image">
                                        </div>
                                        <div class="detail-box">
                                            <h5>
                                                {{ $salad->name }}
                                            </h5>
                                            <p>
                                                {{ $salad->description }}
                                            </p>
                                            <div class="options">
                                                @if ($salad->is_sale)
                                                    <h6>
                                                        <del>{{ number_format($salad->price) }}</del>
                                                        <span>
                                                            <span
                                                                class="text-danger">({{ calDiscountPercentage($salad->price, $salad->sale_price) }}%)</span>
                                                            {{ number_format($salad->sale_price) }}
                                                            <span>تومان</span>
                                                        </span>
                                                    </h6>
                                                @else
                                                    <h6>
                                                        {{ $salad->price }}
                                                        <span>تومان</span>
                                                    </h6>
                                                @endif
                                                <div class="d-flex">
                                                    <a class="me-2" href="">
                                                        <i class="bi bi-cart-fill text-white fs-6"></i>
                                                    </a>
                                                    <a href="">
                                                        <i class="bi bi-heart-fill  text-white fs-6"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="btn-box">
                <a href="">
                    مشاهده بیشتر
                </a>
            </div>
        </div>
    </section>

    <section class="about_section layout_padding">
        @php
            $item = App\Models\AboutUs::first();
        @endphp
        <div class="container">
            <div class="row">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="{{ imageUrl($item->image_address, config('app.about_image_path')) }}"
                            alt="About-us Image" />
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

    <section class="book_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2>
                    تماس با ما
                </h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form_container">
                        <x-contact-us.form />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="map_container ">
                        <div id="map" style="height: 345px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
