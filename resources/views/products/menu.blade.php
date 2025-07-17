@extends('layout.master')
@section('title', 'Products')

@section('content')
    <section class="food_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-3">
                    <form action="{{ url()->current() }}">
                        @foreach (request()->except('search', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="form-label">جستجو</label>
                        @if (request()->has('search'))
                            <a href="{{ request()->fullUrlWithoutQuery('search') }}"
                                class="bi bi-x text-danger fs-5 cursor-pointer"></a>
                        @endif
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="نام محصول ..." />
                            <button type="submit" class="input-group-text">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <hr />
                    <div class="filter-list">
                        <div class="form-label">
                            دسته بندی
                            @if (request()->has('category'))
                                <a href="{{ request()->fullUrlWithoutQuery('category') }}"
                                    class="bi bi-x text-danger fs-5 cursor-pointer"></a>
                            @endif
                        </div>
                        <ul>
                            @foreach ($categories as $category)
                                @php
                                    $query = array_merge(request()->except('page'), ['category' => $category->id]);
                                @endphp
                                <a href="{{ url()->current() . '?' . http_build_query($query) }}" style="color: inherit">
                                    <li
                                        class="my-2 cursor-pointer {{ request()->category == $category->id ? 'filter-list-active' : '' }}">
                                        {{ $category->name }}</li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <hr />
                    <div>
                        <label class="form-label">مرتب سازی</label>
                        @if (request()->has('sortBy'))
                            <a href="{{ request()->fullUrlWithoutQuery('sortBy') }}"
                                class="bi bi-x text-danger fs-5 cursor-pointer"></a>
                        @endif
                        <div class="form-check my-2">
                            <form action="{{ url()->current() }}">
                                @foreach (request()->except('sortBy', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input class="form-check-input" type="radio" name="sortBy" value="max"
                                    onchange="this.form.submit()" {{ request()->sortBy == 'max' ? 'checked' : '' }} />
                                <label class="form-check-label cursor-pointer">
                                    بیشترین قیمت
                                </label>
                            </form>
                        </div>
                        <div class="form-check my-2">
                            <form action="{{ url()->current() }}">
                                @foreach (request()->except('sortBy', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input class="form-check-input" type="radio" name="sortBy" value="min"
                                    onchange="this.form.submit()" {{ request()->sortBy == 'min' ? 'checked' : '' }} />
                                <label class="form-check-label cursor-pointer">
                                    کمترین قیمت
                                </label>
                            </form>
                        </div>
                        <div class="form-check my-2">
                            <form action="{{ url()->current() }}">
                                @foreach (request()->except('sortBy', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input class="form-check-input" type="radio" name="sortBy" value="bestseller"
                                    onchange="this.form.submit()" {{ request()->sortBy == 'bestseller' ? 'checked' : '' }} />
                                <label class="form-check-label cursor-pointer">
                                    پرفروش ترین
                                </label>
                            </form>
                        </div>
                        <div class="form-check my-2">
                            <form action="{{ url()->current() }}">
                                @foreach (request()->except('sortBy', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input class="form-check-input" type="radio" name="sortBy" value="sale"
                                    onchange="this.form.submit()" {{ request()->sortBy == 'sale' ? 'checked' : '' }} />
                                <label class="form-check-label cursor-pointer">
                                    با تخفیف
                                </label>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-9">
                    @if ($products->isEmpty())
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <h5>محصولی یافت نشد</h5>
                        </div>
                    @endif
                    <div class="row gx-3">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-lg-4">
                                <div class="box">
                                    <div>
                                        <div class="img-box">
                                            <img class="img-fluid" style="border-radius: 0"
                                                src="{{ imageUrl($product->primary_image, config('app.product_image_path')) }}"
                                                alt="product-image">
                                        </div>
                                        <div class="detail-box">
                                            <h5>
                                                <a href="{{ route('product.show', $product->slug) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </h5>
                                            <p>
                                                {{ $product->description }}
                                            </p>

                                            <div class="options">
                                                @if ($product->is_sale)
                                                    <h6>
                                                        <del>{{ number_format($product->price) }}</del>
                                                        <span>
                                                            <span
                                                                class="text-danger">({{ calDiscountPercentage($product->price, $product->sale_price) }}%)</span>
                                                            {{ number_format($product->sale_price) }}
                                                            <span>تومان</span>
                                                        </span>
                                                    </h6>
                                                @else
                                                    <h6>
                                                        {{ number_format($product->price) }}
                                                        <span>تومان</span>
                                                    </h6>
                                                @endif

                                                <div class="d-flex">
                                                    <a class="me-2" href="">
                                                        <i class="bi bi-cart-fill text-white fs-6"></i>
                                                    </a>
                                                    <a href="{{ route('profile.add.wishlist', ['product_id' => $product->id]) }}">
                                                        <i class="bi bi-heart-fill text-white fs-6"></i>
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
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </section>
@endsection
