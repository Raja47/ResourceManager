@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-briefcase"></i> {{ $pageTitle }}</h1>
            <p>{{ $subTitle }}</p>
        </div>
        <a href="{{ route('admin.couponAndDeals.create') }}" class="btn btn-primary pull-right">Add Coupon and Deal</a>
    </div>
    @include('admin.partials.flash')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                        <tr>
                           
                            <th> # </th>
                            <th> Type </th>
                            <th> Title </th>
                            <th> Discount Type </th>
                            <th> Discount Value </th>   
                            <th> Expire At </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($couponAndDeals as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->type }}</td>
                                <td>{{ $coupon->title }}</td>
                                <td>{{ $coupon->discount_type }}</td>
                                <td>{{ $coupon->value }}</td>
                                <td class="text-center">
                                        @if ( $coupon->expire_at )
                                            @if ( strtotime($coupon->expire_at) > time()  )
                                            <span class="badge badge-success">
                                                {{ date('Y-m-d', strtotime($coupon->expire_at)) }}</span>
                                            @else
                                                <span class="badge badge-danger">Expired</span>
                                            @endif
                                        @else
                                                <span class="badge badge-warning">Not Set</span>
                                        @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Second group">
                                        <a href="{{ route('admin.couponAndDeals.edit', $coupon->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('admin.couponAndDeals.delete', $coupon->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush

