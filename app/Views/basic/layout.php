<!DOCTYPE html>
<html lang="<?= smarty_current_lang(); ?>" dir="<?= smarty_current_lang_direction(); ?>">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>
        <?= $this->renderSection('title') ?>
    </title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="<?= $this->renderSection('title') ?>">
    <meta name="author" content="Extendy Team">
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard">
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.1.0/styles/overlayscrollbars.min.css"
        integrity="sha256-LWLZPJ7X1jJLI5OG5695qDemW1qQ7lNdbTfQ64ylbUY=" crossorigin="anonymous">
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css"
        integrity="sha256-BicZsQAhkGHIoR//IB2amPN5SrRb3fHB8tFsnqRAwnk=" crossorigin="anonymous">
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= smarty_cdn() ?>css/adminlte<?= smarty_current_lang_dotdirection(); ?>.min.css">
    <link rel="stylesheet" href="<?= smarty_cdn() ?>css/smarty<?= smarty_current_lang_dotdirection(); ?>.css">
    <!--end::Required Plugin(AdminLTE)-->
    <!--BEGIN: cssheaderarea css header area contains any specific javascript files that view files may load -->
    <?= $this->renderSection('cssheaderarea') ?>
    <!--END: cssheaderarea css header area contains any specific javascript files that view files may load -->

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>

                    <?php
                    if (auth()->user()->can('url.new', 'super.admin')) {
                        ?>
                        <li class="nav-item d-none d-md-block">
                            <a href="/url/new" class="btn btn-primary"><i class="bi bi-plus-lg"></i>
                                <?= lang('Url.addNewURLMainbtn'); ?>
                            </a>
                        </li>
                        <?php
                    }
?>

                    <!--begin: +URL on small screen only -->
                    <li class="nav-item d-block d-sm-none d-md-none d-lg-none d-xl-none">
                        <a href="/url/new" class="btn btn-primary"><i class="bi bi-plus-lg"></i>
                            <?= lang('Url.addNewURLMainShortbtn'); ?>
                        </a>
                    </li>
                    <!--end: +URL on small screen only -->
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">



                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">


                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
                                </path>
                            </svg>


                        </a>
                        <ul class="smarty-user-top dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="smarty-user-header text-bg-primary">

                                <p>
                                    <?= esc(auth()->user()->username); ?> -
                                    <?= esc(auth()->user()->email); ?>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-12 text-center">

                                        <a class="btn btn-secondary btn-sm"
                                            href="<?= site_url('account/changepwd'); ?>">
                                            <i class="bi bi-person-fill-gear"></i>
                                            <?= lang('Common.accountSettingsLnk'); ?>
                                        </a>
                                    </div>


                                </div>
                                <!--end::Row-->
                            </li>
                            <!--end::Menu Body-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">

                                <a href="/account/logout" class="btn btn-light float-end"><i
                                        class="bi bi-box-arrow-right"></i>
                                    <?= lang('Common.accountLogoutLnk'); ?>
                                </a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->


                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="<?= site_url('/dashboard'); ?>" class="brand-link">

                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">
                        <?= setting('smartyurl.siteName'); ?>
                    </span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->

                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="<?= site_url('/dashboard'); ?>"
                                class="nav-link <?= (url_is('/dashboard')) ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-house-door"></i>
                                <p>
                                    <?= lang('Common.dashboardLnk'); ?>
                                </p>
                            </a>

                        </li>


                        <li
                            class="nav-item <?= (url_is('/url') || url_is('/url/user/*') || url_is('/url/tag/*') || url_is('/urltags')) ? 'menu-open' : '' ?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-database"></i>
                                <p>
                                    <?= lang('Url.urlsLink'); ?>
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('/url/user/' . user_id()); ?>"
                                        class="nav-link <?= (url_is('/url/user/' . user_id())) ? 'active' : '' ?>">
                                        <i class="nav-icon bi bi-person"></i>
                                        <p>
                                            <?= lang('Url.urlsMyLink'); ?>
                                        </p>
                                    </a>
                                </li>
                                <?php
            if (auth()->user()->can('admin.manageotherurls', 'super.admin')) {
                ?>
                                    <li class="nav-item">
                                        <a href="<?= site_url('/url'); ?>"
                                            class="nav-link <?= (url_is('/url/')) ? 'active' : '' ?>">
                                            <i class="nav-icon bi bi-people"></i>
                                            <p>
                                                <?= lang('Url.urlsAllLink'); ?>
                                            </p>
                                        </a>
                                    </li>
                                    <?php
            } ?>

                                <li class="nav-item">
                                    <a href="<?= url_to('url-tags'); ?>"
                                        class="nav-link <?= (url_is('/urltags')) ? 'active' : '' ?>">
                                        <i class="nav-icon bi bi-cloud"></i>
                                        <p>
                                            <?= lang('Url.TagsCloud'); ?>
                                        </p>
                                    </a>
                                </li>


                            </ul>
                        </li>


                        <?php
                        if (auth()->user()->can('users.list', 'users.manage', 'super.admin')) {
                            ?>

                            <li class="nav-item <?= (url_is('/users') || url_is('/users/*')) ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link ">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>
                                        <?= lang('Users.SystemUserMenu'); ?>
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <?php
                                if (auth()->user()->can('users.list', 'super.admin')) {
                                    ?>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= site_url('/users'); ?>"
                                                class="nav-link <?= (url_is('/users')) ? 'active' : '' ?>">
                                                <i class="nav-icon bi bi-people"></i>
                                                <p>
                                                    <?= lang('Users.SystemUsersList'); ?>
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                    <?php
                                }
                            ?>
                                <?php
                            if (auth()->user()->can('users.list', 'super.admin')) {
                                ?>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= site_url('/users/addnew'); ?>"
                                                class="nav-link <?= (url_is('/users/addnew')) ? 'active' : '' ?>">
                                                <i class="nav-icon bi bi-person-plus"></i>
                                                <p>
                                                    <?= lang('Users.UsersAddNewUser'); ?>
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                    <?php
                            }
                            ?>
                            </li>

                            <?php
                        }
?>


                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin: noscript -->
            <noscript>
                <div class="col-12 container mt-2">
                    <!-- Alert Box for noscript -->
                    <div class="alert alert-danger" role="alert">
                        <?= lang('Common.javascriptNotEnabledNotice'); ?>
                    </div>
                </div>
            </noscript>
            <!--end: noscript -->

            <?= $this->renderSection('main') ?>

        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">
                <?= config('Smarty')->smarty_name; ?> <a target="_blank" rel="nofollow"
                    href="<?= config('Smarty')->smarty_online_repo; ?>">v
                    <?= config('Smarty')->smarty_version; ?>
                </a>
            </div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2023
                <a target="_blank" rel="external" href="https://extendy.net">Extendy</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.1.0/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-NRZchBuHZWSXldqrtAOeCZpucH/1n1ToJ3C8mSK95NU=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?= smarty_cdn() ?>js/adminlte.js"></script>
    <script src="<?= smarty_include_jquery(); ?>"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };

        document.addEventListener("DOMContentLoaded", function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--begin::SmartyUL Globals js -->
    <script src="<?= site_url('assist/smartyurl') ?>"></script>
    <!--end::SmartyUL Globals js-->
    <!--end::OverlayScrollbars Configure-->
    <!--begin::jsfooterarea contains any specific javascript files that view files may load -->
    <?= $this->renderSection('jsfooterarea') ?>
    <!--end::jsfooterarea contains any specific javascript files that view files may load -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--end::Script-->
</body><!--end::Body-->

</html>