@extends('layout.master')
@section('title', 'Product Page')

@section('content')
    <section class="single_page_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row gy-5">
                        <div class="col-sm-12 col-lg-6">
                            <h3 class="fw-bold mb-4">{{ $product->name }}</h3>

                            @if ($product->is_sale)
                                <h5 class="mb-3">
                                    <del>{{ $product->price }}</del>
                                    {{ $product->sale_price }}
                                    تومان
                                    <div class="text-danger fs-6">
                                        {{ calDiscountPercentage($product->price, $product->sale_price) }}% تخفیف
                                    </div>
                                </h5>
                            @else
                                <h5>
                                    {{ $product->price }}
                                    تومان
                                </h5>
                            @endif

                            <p>{{ $product->description }}</p>

                            <form x-data="{ quantity: 1 }" action="" class="mt-5 d-flex">
                                <button type="submit" class="btn-add">افزودن به سبد خرید</button>
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="qty" :value="quantity">
                                <div class="input-counter ms-4">
                                    <span @click="quantity < {{ $product->quantity }} && quantity++" class="plus-btn">
                                        +
                                    </span>
                                    <div class="input-number" x-text="quantity"></div>
                                    <span @click="quantity > 1 && quantity--" class="minus-btn">
                                        -
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                        class="active"></button>
                                    @foreach ($product->images as $image)
                                        <button type="button" data-bs-target="#carouselExampleIndicators"
                                            data-bs-slide-to="{{ $loop->index + 1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="{{ imageUrl($product->primary_image, config('app.product_image_path')) }}" class="d-block w-100"
                                            alt="product-image" />
                                    </div>
                                    @foreach ($product->images as $image)
                                        <div class="carousel-item">
                                            <img src="{{ imageUrl($image->image, config('app.product_image_path')) }}" class="d-block w-100"
                                                alt="product-image" />
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr>
    <section class="food_section my-5">
        <div class="container">
            <div class="row gx-3">
                @foreach ($randomProducts as $randomProduct)
                    <div class="col-sm-6 col-lg-3">
                        <div class="box">
                            <div>
                                <div class="img-box">
                                    <img class="img-fluid" src="{{ imageUrl($randomProduct->primary_image, config('app.product_image_path')) }}"
                                        alt="product-image" />
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        <a href="{{ route('product.show', $randomProduct->slug) }}">
                                            {{ $randomProduct->name }}
                                        </a>
                                    </h5>
                                    <p>
                                        {{ $randomProduct->description }}
                                    </p>
                                    <div class="options">
                                        @if ($randomProduct->is_sale)
                                            <span>
                                                <span
                                                    class="text-danger">({{ calDiscountPercentage($randomProduct->price, $randomProduct->sale_price) }}%)</span>
                                                {{ $randomProduct->sale_price }}
                                                <span>تومان</span>
                                            </span>
                                        @else
                                            <h6>
                                                {{ $randomProduct->price }}
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
    </section>
@endsection
