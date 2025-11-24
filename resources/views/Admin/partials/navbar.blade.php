<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar p-0 fixed-top d-flex flex-row" style="background: #0F172A">

    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="ms-auto d-flex align-items-center justify-content-center">
            <a class="sidebar-brand brand-logo text-decoration-none ms-5 fs-2" href="{{ route('admin.dashboard') }}"
                style="color: white;">
                <span class="align-middle">قدراتو</span>
                    <img src="{{ asset('defaults/logo.png') }}" alt="Logo" class="d-inline-block align-middle me-2" style="width:48px; height:auto;">
                </a>
        </div>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>
