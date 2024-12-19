`@extends('admin.master')

@section('title', 'Trang Chủ')

@section('main-content')
    <!-- Row start -->
    <div class="row">
        <div class="col-xxl-3 col-sm-6 col-12">
            <div class="stats-tile">
                <div class="sale-icon shade-red">
                    <i class="bi bi-pie-chart"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-red">250k</h3>
                    <p>Sales</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12">
            <div class="stats-tile">
                <div class="sale-icon shade-blue">
                    <i class="bi bi-emoji-smile"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-blue">24m</h3>
                    <p>Customers</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12">
            <div class="stats-tile">
                <div class="sale-icon shade-yellow">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-yellow">15k</h3>
                    <p>Products</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 col-12">
            <div class="stats-tile">
                <div class="sale-icon shade-green">
                    <i class="bi bi-handbag"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-green">180m</h3>
                    <p>Revenue</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Row end -->
@endsection
