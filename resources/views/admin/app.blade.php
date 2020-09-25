<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/main.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/fontawesome-free/css/all.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/sb-admin-2.min.css') }}" />
    <link rel="shortcut icon" href="{{  asset('storage/'.config('settings.site_favicon') ) }}" />
    @yield('styles')
</head>
<body  class="app sidebar-mini rtl" >

    <!-- Page Wrapper -->
  <div id="wrapper">
    
      @include('admin.partials.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        @include('admin.partials.header')
        <!-- Topbar -->
      
        <!-- End of Topbar -->        <!-- Begin Page Content -->
        <div class="container-fluid" id="app">

             @yield('content')

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

     

    </div>
    <!-- End of Content Wrapper -->

  </div>

    <script src="{{ asset('backend/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/main.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/pace.min.js') }}"></script>
    @stack('scripts')

  
</body >
</html>
