@extends('admin.app')

@section('title') {{ $pageTitle }} @endsection

@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-cogs"></i> {{ $pageTitle }}</h1>
        </div>
    </div>
    @include('admin.partials.flash')
    <div class="row user">
        <div class="col-md-3">
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li>
                    <li class="nav-item"><a class="nav-link" href="#site-logo" data-toggle="tab">Site Logo & Images</a></li>
                    <li class="nav-item"><a class="nav-link" href="#footer-seo" data-toggle="tab">Footer &amp; SEO</a></li>
                    <li class="nav-item"><a class="nav-link" href="#social-links" data-toggle="tab">Social Links</a></li>
                    <li class="nav-item"><a class="nav-link" href="#analytics" data-toggle="tab">Analytics</a></li>
                    <li class="nav-item"><a class="nav-link" href="#image_uploads" data-toggle="tab">Image Uploads Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#payments" data-toggle="tab">Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#newyork_tax" data-toggle="tab">New York Tax</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shippings" data-toggle="tab">Shipping</a></li>
                    
                    
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    @include('admin.settings.includes.general')
                </div>
                <div class="tab-pane fade" id="site-logo">
                    @include('admin.settings.includes.logo')
                </div>
                <div class="tab-pane fade" id="footer-seo">
                    @include('admin.settings.includes.footer_seo')
                </div>
                <div class="tab-pane fade" id="social-links">
                    @include('admin.settings.includes.social_links')
                </div>
                <div class="tab-pane fade" id="analytics">
                    @include('admin.settings.includes.analytics')
                </div>
                <div class="tab-pane" id="image_uploads">
                    @include('admin.settings.includes.image_uploads')
                </div>
                <div class="tab-pane fade" id="payments">
                    @include('admin.settings.includes.payments')
                </div>
                <div class="tab-pane fade" id="newyork_tax">
                     @include('admin.settings.includes.newyork_tax')
                </div>
                <div class="tab-pane fade" id="shippings">
                     @include('admin.settings.includes.shippings')
                </div>
            </div>
        </div>
    </div>
@endsection
