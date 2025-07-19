@extends('layout.master')
@section('title', 'Orders')

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
                                    <th>شماره سفارش</th>
                                    <th>آدرس</th>
                                    <th>وضعیت</th>
                                    <th>وضعیت پرداخت</th>
                                    <th>قیمت کل</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <th>
                                            {{ $order->id }}
                                        </th>
                                        <td>{{ $order->address->title }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>
                                            <span
                                                class="{{ $order->getRawOriginal('payment_status') == 0 ? 'text-danger' : 'text-success' }}">{{ $order->payment_status }}</span>
                                        </td>
                                        <td>{{ $order->paying_amount }} تومان</td>
                                        <td>{{ jdate($order->created_at)->format('Y/m/d') }}</td>

                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-{{ $order->id }}">
                                                محصولات
                                            </button>
                                            <div class="modal fade" id="modal-{{ $order->id }}">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">محصولات سفارش
                                                                شماره {{ $order->id }}</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
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
                                                                    @foreach ($order->orderItems()->with('product')->get() as $item)
                                                                        <tr>
                                                                            <th>
                                                                                <img class="rounded" src="{{ imageUrl(path:config('app.product_image_path'), img:$item->product->primary_image) }}"
                                                                                    width="80" alt="Product Image"/>
                                                                            </th>
                                                                            <td class="fw-bold">{{ $item->product->name }}</td>
                                                                            <td>{{ $item->price }} تومان</td>
                                                                            <td>
                                                                                {{ $item->quantity }}
                                                                            </td>
                                                                            <td>{{ $item->subtotal }} تومان</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
