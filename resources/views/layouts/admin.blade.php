<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- AdminLTE CSS (Bootstrap 4 + AdminLTE UI) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS (optional) -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    
    <!-- Navbar -->
    @include('layouts.partials.admin-navbar')

    <!-- Main Sidebar Container -->
    @include('layouts.partials.admin-sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content pt-3">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>



</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@yield('js')

</body>

</html>
