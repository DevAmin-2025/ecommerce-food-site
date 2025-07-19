@extends('layout.master')
@section('title', 'Transactions')

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
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th>شماره پیگیری</th>
                                    <th>تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <th>
                                            {{ $transaction->id }}
                                        </th>
                                        <td>{{ $transaction->amount }} تومان</td>
                                        <td>
                                            <span class="{{ $transaction->getRawOriginal('status') == 0 ? 'text-danger' : 'text-success' }}">{{ $transaction->status }}</span>
                                        </td>
                                        <td>{{ $transaction->ref_number }}</td>
                                        <td>{{ jdate($transaction->created_at)->format('Y/m/d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
