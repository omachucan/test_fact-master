<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Metrica - Responsive Bootstrap 4 Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A premium admin dashboard template by Mannatthemes" name="description" />
        <meta content="Mannatthemes" name="author" />

        <link rel="shortcut icon" href="{{ asset('templates/metrica/images/favicon.ico') }}">

        <link href="{{ asset('templates/metrica/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('templates/metrica/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('templates/metrica/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('templates/metrica/css/style.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="topbar">
            <div class="navbar-custom-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <ul class="navigation-menu">
                            <li class="has-submenu">
                                <a href="/" class="p-0">
                                    @if($vc_company->logo)
                                        <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}" alt="Logo"  class="logo-sm" style="height: 45px" />
                                    @else
                                        <img src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
                                    @endif
                                </a>
                            </li>
                            <li class="has-submenu">
                                <a href="{{route('tenant.pos.index')}}">
                                    <i class="fa fa-user"></i>
                                    <span>Inicio</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../projects/projects-index.html"><i class="dripicons-view-thumb"></i>Dashboard</a></li>
                                    <li><a href="../projects/projects-clients.html"><i class="dripicons-user-id"></i>Clients</a></li>
                                    <li><a href="../projects/projects-calendar.html"><i class="dripicons-calendar"></i>Calendar</a></li>
                                    <li><a href="../projects/projects-team.html"><i class="dripicons-trophy"></i>Team</a></li>
                                    <li><a href="../projects/projects-project.html"><i class="dripicons-jewel"></i>Project</a></li>
                                    <li><a href="../projects/projects-task.html"><i class="dripicons-checklist"></i>Tasks</a></li>
                                    <li><a href="../projects/projects-kanban-board.html"><i class="dripicons-move"></i>Kanban Board</a></li>
                                    <li><a href="../projects/projects-invoice.html"><i class="dripicons-document"></i>Invoice</a></li>
                                    <li><a href="../projects/projects-chat.html"><i class="dripicons-conversation"></i>Chat</a></li>
                                    <li><a href="../projects/projects-users.html"><i class="dripicons-user-group"></i>Users</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">
                                    <svg class="nav-svg" version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <ellipse class="svg-primary" transform="matrix(0.9998 -1.842767e-02 1.842767e-02 0.9998 -7.7858 3.0205)" cx="160" cy="424" rx="24" ry="24"/>
                                            <ellipse class="svg-primary" transform="matrix(2.381651e-02 -0.9997 0.9997 2.381651e-02 -48.5107 798.282)" cx="384.5" cy="424" rx="24" ry="24"/>
                                            <path d="M463.8,132.2c-0.7-2.4-2.8-4-5.2-4.2L132.9,96.5c-2.8-0.3-6.2-2.1-7.5-4.7c-3.8-7.1-6.2-11.1-12.2-18.6
                                                c-7.7-9.4-22.2-9.1-48.8-9.3c-9-0.1-16.3,5.2-16.3,14.1c0,8.7,6.9,14.1,15.6,14.1c8.7,0,21.3,0.5,26,1.9c4.7,1.4,8.5,9.1,9.9,15.8
                                                c0,0.1,0,0.2,0.1,0.3c0.2,1.2,2,10.2,2,10.3l40,211.6c2.4,14.5,7.3,26.5,14.5,35.7c8.4,10.8,19.5,16.2,32.9,16.2h236.6
                                                c7.6,0,14.1-5.8,14.4-13.4c0.4-8-6-14.6-14-14.6H189h-0.1c-2,0-4.9,0-8.3-2.8c-3.5-3-8.3-9.9-11.5-26l-4.3-23.7
                                                c0-0.3,0.1-0.5,0.4-0.6l277.7-47c2.6-0.4,4.6-2.5,4.9-5.2l16-115.8C464,134,464,133.1,463.8,132.2z"/>
                                        </g>
                                    </svg>
                                    <span>Ecommerce</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="../ecommerce/ecommerce-index.html"><i class="dripicons-device-desktop"></i>Dashboard</a></li>
                                    <li><a href="../ecommerce/ecommerce-products.html"><i class="dripicons-view-apps"></i>Products</a></li>
                                    <li><a href="../ecommerce/ecommerce-product-list.html"><i class="dripicons-list"></i>Product List</a></li>
                                    <li><a href="../ecommerce/ecommerce-product-detail.html"><i class="dripicons-article"></i>Product Detail</a></li>
                                    <li><a href="../ecommerce/ecommerce-cart.html"><i class="dripicons-cart"></i>Cart</a></li>
                                    <li><a href="../ecommerce/ecommerce-checkout.html"><i class="dripicons-card"></i>Checkout</a></li>
                                </ul><!--end submenu-->
                            </li>
                            <li class="has-submenu">
                                <a href="#" class="nav-user">
                                    <i class="fa fa-user"></i>
                                    <span>{{ $vc_user->name }}</span><br>
                                    {{-- <small>{{ $vc_user->email }}</small> --}}
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="../crm/crm-index.html"><i class="dripicons-monitor"></i>Salir</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Metrica</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                        <li class="breadcrumb-item active">Starter</li>
                                    </ol><!--end breadcrumb-->
                                </div><!--end /div-->
                                <h4 class="page-title">Starter</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                       

                </div><!-- container -->
            </div>
            <!-- end page content -->
            <footer class="footer text-center text-sm-left">
               <div class="boxed-footer">
                    &copy; 2019 Metrica <span class="text-muted d-none d-sm-inline-block float-right">Crafted with <i class="mdi mdi-heart text-danger"></i> by Mannatthemes</span>
               </div>
            </footer><!--end footer-->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="{{ asset('templates/metrica/js/jquery.min.js') }}"></script>
        <script src="{{ asset('templates/metrica/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('templates/metrica/js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('templates/metrica/js/waves.min.js') }}"></script>
        <script src="{{ asset('templates/metrica/js/jquery.slimscroll.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('templates/metrica/js/app.js') }}"></script>
       
    </body>
</html>