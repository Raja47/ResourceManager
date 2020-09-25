@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-bar-chart"></i> {{ $pageTitle }}</h1>
            <p>{{ $subTitle }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <section class="invoice">
                    <div class="row mb-4">
                        <div class="col-6">
                            <h2 class="page-header"><i class="fa fa-globe"></i> {{ $user->fullName }}</h2>
                        </div>
                        <div class="col-6">
                            <h5 class="text-right">Date: {{ $user->created_at->toFormattedDateString() }}</h5>
                        </div>
                    </div>
                    <div class="row invoice-info">
                        <div class="col-4">
                            <address><strong>{{ $user->fullName }}</strong><br>Email: {{ $user->email }}</address>
                        </div>
                        <div class="col-4">Address 1
                            <address><strong>{{ $user->address }} </strong><br>{{ $user->city }}, {{ $user->country }} {{ $user->post_code }}<br>{{ $user->phone_number }}<br></address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Product</th>
                                    <th>SKU #</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                 

                                         <tr>
                                            <td>{{ 'User Orders' }}</td>
                                            <td>{{ 'User Orders' }}</td>
                                            <td>{{ 'User Orders' }}</td>
                                            <td>{{ 'User Orders' }}</td>
                                            <td>{{ 'User Orders' }}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
