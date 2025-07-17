@extends('layout.master')
@section('title', 'Wishlist')

@section('content')
    <section class="profile_section layout_padding">
        <div class="container">
            <div class="row">
                <x-profile.sidebar />
                <div class="col-sm-12 col-lg-9">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>محصول</th>
                                    <th>نام</th>
                                    <th>قیمت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wishlist as $item)
                                    <tr>
                                        <th>
                                            <img class="rounded" src="{{ imageUrl($item->product->primary_image, config('app.product_image_path')) }}"
                                                width="100" alt="Product Image" />
                                        </th>
                                        <td class="fw-bold">
                                            <a href=""
                                                style="text-decoration: none; color: inherit;">
                                                {{ $item->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($item->product->is_sale)
                                                <div>
                                                    <del>{{ $item->product->price }}</del>
                                                    {{ $item->product->sale_price }}
                                                    تومان
                                                </div>
                                                <div class="text-danger">
                                                    {{ calDiscountPercentage($item->product->price, $item->product->sale_price) }}%
                                                    تخفیف
                                                </div>
                                            @else
                                                <div>
                                                    {{ $item->product->price }}
                                                    تومان
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('profile.remove.wishlist', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary">
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
