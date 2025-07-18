@extends('layout.master')
@section('title', 'Cart Page')

@section('content')
    @if ($cart)
        <section class="single_page_section layout_padding">
            <div class="container" x-data="{ addressId: '' }">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="row gy-5">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>محصول</th>
                                                <th>نام</th>
                                                <th>قیمت</th>
                                                <th>تعداد</th>
                                                <th>قیمت کل</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalPrice = 0;
                                            @endphp
                                            @foreach ($cart as $key => $item)
                                                <tr>
                                                    <th>
                                                        <img class="rounded"
                                                            src="{{ imageUrl($item['image'], config('app.product_image_path')) }}"
                                                            width="100" alt="product image" />
                                                    </th>
                                                    <td class="fw-bold">{{ $item['name'] }}</td>
                                                    <td>
                                                        @if ($item['is_sale'])
                                                            <div>
                                                                <del>{{ number_format($item['price']) }}</del>
                                                                {{ number_format($item['sale_price']) }}
                                                                تومان
                                                            </div>
                                                            <div class="text-danger">
                                                                {{ calDiscountPercentage($item['price'], $item['sale_price']) }}%
                                                                تخفیف
                                                            </div>
                                                        @else
                                                            <div>
                                                                {{ number_format($item['price']) }}
                                                                تومان
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="input-counter">
                                                            <a href="{{ route('cart.increment', ['product_id' => $key]) }}"
                                                                class="plus-btn">
                                                                +
                                                            </a>
                                                            <div class="input-number">{{ $item['qty'] }}</div>
                                                            <a href="{{ route('cart.decrement', ['product_id' => $key]) }}"
                                                                class="minus-btn">
                                                                -
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $price = $item['is_sale']
                                                                ? $item['sale_price']
                                                                : $item['price'];

                                                            $totalPrice += $price * $item['qty'];
                                                        @endphp
                                                        <span>{{ number_format($item['qty'] * $price) }}</span>
                                                        <span class="ms-1">تومان</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('cart.remove', ['product_id' => $key]) }}">
                                                            <i class="bi bi-x text-danger fw-bold fs-4 cursor-pointer"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary mb-4">پاک کردن سبد خرید</button>
                                </form>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 col-md-6">
                                <form action="{{ route('cart.check.coupon') }}">
                                    <div class="input-group mb-3">
                                        <input type="text" name="code" class="form-control" placeholder="کد تخفیف" />
                                        <button type="submit" class="input-group-text" id="basic-addon2">اعمال کد
                                            تخفیف</button>
                                    </div>
                                    @error('code')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </form>
                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-end align-items-baseline">
                                @if ($addresses->isNotEmpty())
                                    <div>
                                        انتخاب آدرس
                                    </div>
                                    <select x-model="addressId" style="width: 200px;" class="form-select ms-3">
                                        <option value="" disabled>انتخاب آدرس</option>
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}">{{ $address->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('address_id')
                                        <div class="form-text text-danger" style="margin-right: 5px;">{{ $message }}</div>
                                    @enderror
                                @else
                                    <a href="{{ route('profile.address.create') }}" class="btn btn-primary">
                                        ایجاد آدرس
                                    </a>
                                @endif
                            </div>
                            @php
                                $hasCoupon = session()->has('coupon') ? true : false;
                            @endphp
                            @if ($hasCoupon)
                                <form action="{{ route('cart.destroy.coupon') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary col-3">
                                        حذف کد تخفیف
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-12 col-md-6">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <h5 class="card-title fw-bold">مجموع سبد خرید</h5>
                                        <ul class="list-group mt-4">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <div>مجموع قیمت :</div>
                                                <div>
                                                    {{ number_format($totalPrice) }} تومان
                                                </div>
                                            </li>
                                            @php
                                                $discountAmount = session()->has('coupon')
                                                    ? $totalPrice * (session('coupon')['percent'] / 100)
                                                    : 0;
                                            @endphp
                                            @if ($hasCoupon)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <div>تخفیف :
                                                        <span
                                                            class="text-danger ms-1">{{ session('coupon')['percent'] }}%</span>
                                                    </div>
                                                    <div class="text-danger">
                                                        {{ number_format($discountAmount) }}
                                                        تومان
                                                    </div>
                                                </li>
                                            @endif
                                            <li class="list-group-item d-flex justify-content-between">
                                                <div>قیمت پرداختی :</div>
                                                <div>
                                                    {{ isset($hasCoupon) ? number_format($totalPrice - $discountAmount) : number_format($totalPrice) }}
                                                    تومان
                                                </div>
                                            </li>
                                        </ul>
                                        <form action="{{ route('payment.send') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="coupon_code" value="{{ session()->has('coupon') ? session('coupon')['code'] : null }}">
                                            <input type="hidden" name="address_id" :value="addressId">
                                            <button class="user_option btn-auth mt-4">پرداخت</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="cart-empty">
            <div class="text-center">
                <div>
                    <i class="bi bi-basket-fill" style="font-size:80px"></i>
                </div>
                <h4 class="text-bold">سبد خرید شما خالی است</h4>
                <a href="{{ route('product.menu') }}" class="btn btn-outline-dark mt-3">
                    مشاهده محصولات
                </a>
            </div>
        </div>
    @endif
@endsection
