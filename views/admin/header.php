<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="<?php echo public_url("admin_assets/vendor/jquery/jquery.min.js") ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <?php if(current_route() === "admin"): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo public_url("admin_assets/css/month-picker.min.css"); ?>"> 
        <link rel="stylesheet" href="<?php echo public_url("admin_assets/css/year-picker.css"); ?>"> 
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script src="<?php echo public_url("admin_assets/js/month-picker.min.js") ?>"></script>
        <script src="<?php echo public_url("admin_assets/js/year-picker.js") ?>"></script>
    <?php endif; ?>
    <link href="<?php echo public_url("admin_assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo public_url("admin_assets/css/style.css") ?>">
    <?php if(strpos(current_route(),"coupon") !== false): ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
    <?php endif; ?>
    <?php if(current_route() === "admin/product/add" || strpos(current_route(),"admin/product/edit") !== false): ?>
        <script src="<?php echo public_url("client_assets/js/notifIt.js") ?>"></script>
        <link rel="stylesheet" href="<?php echo public_url("client_assets/css/notifIt.css"); ?>">    
    <?php endif; ?>
    <script src="<?php echo public_url("client_assets/js/notifIt.js") ?>"></script>
    <link rel="stylesheet" href="<?php echo public_url("client_assets/css/notifIt.css"); ?>">    
    <script>
        const csrf_token = "<?php echo csrf_token(); ?>";
    </script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo url("/") ?>">
                <div class="mx-3">Xem trang web</div>
            </a>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo current_route() === "admin" ? "active" : "" ?>">
                <a class="nav-link" href="<?php echo url("admin") ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Thống kê</span>
                </a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item <?php echo strpos(current_route(),"category") !== false ? "active" : "" ?>">
                <a class="nav-link" href="<?php echo url("admin/category"); ?>">
                    <i class="fas fa-folder"></i>
                    <span>Danh mục</span>
                </a>
            </li>

            <li class="nav-item <?php echo strpos(current_route(),"product") !== false ? "active" : "" ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                    aria-expanded="true" aria-controls="collapseProducts">
                    <i class="fas fa-shirt"></i>
                    <!-- <i class="fas fa-fw fa-wrench"></i> -->
                    <span>Sản phẩm</span>
                </a>
                <div id="collapseProducts" class="collapse <?php echo strpos(current_route(),"product") !== false ? "show" : "" ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php echo strpos(current_route(),"admin/product") !== false && current_route() !== "admin/product/add" ? "active" : "" ?>" href="<?php echo url("admin/product") ?>">Danh sách sản phẩm</a>
                        <a class="collapse-item <?php echo current_route() === "admin/product/add" ? "active" : "" ?>" href="<?php echo url("admin/product/add") ?>">Thêm sản phẩm</a>
                    </div>
                </div>
            </li>

            <li class="nav-item <?php echo strpos(current_route(),"coupon") !== false ? "active" : "" ?>">
                <a class="nav-link" href="<?php echo url("admin/coupon"); ?>">
                    <i class="fas fa-ticket"></i>
                    <span>Mã giảm giá</span>
                </a>
            </li>

            <li class="nav-item <?php echo strpos(current_route(),"order") !== false ? "active" : "" ?>">
                <a class="nav-link" href="<?php echo url("admin/order"); ?>">
                    <i class="fas fa-receipt"></i>
                    <span>Đơn hàng</span>
                </a>
            </li>

              <li class="nav-item <?php echo strpos(current_route(),"user") !== false ? "active" : "" ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
                    aria-expanded="true" aria-controls="collapseUsers">
                   <i class="fas fa-solid fa-user"></i>
                    <span>Người dùng</span>
                </a>
                <div id="collapseUsers" class="collapse <?php echo strpos(current_route(),"user") !== false ? "show" : "" ?>" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php echo current_route() === "admin/user" ? "active" : "" ?>" href="<?php echo url("admin/user") ?>">Danh sách người dùng</a>
                        <a class="collapse-item <?php echo current_route() === "admin/user/add" ? "active" : "" ?>" href="<?php echo url("admin/user/add") ?>">Thêm người dùng</a>
                    </div>
                </div>
            </li>
         
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav
                  style="background-color: #3c434a;" class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow py-0"
                >
                  <!-- Topbar Navbar -->
                  <ul class="navbar-nav ml-auto py-1">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                      <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                      <?php
                          $current_user = getUser("admin");
                      ?>
                        <span style="color:#fff" class="mr-2 d-none d-lg-inline small"
                          >Welcome, <?php echo $current_user->name ?></span
                        >
                        <img
                          class="img-profile rounded-circle"
                          src="<?php echo public_url("admin_assets/img/avatar.png") ?>"
                        />
                      </a>
                      <!-- Dropdown - User Information -->
                      <div
                        class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown"
                      >
                        <a
                          class="dropdown-item"
                          href="<?php echo url("admin/logout"); ?>"
                        >
                          <i
                            class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                          ></i>
                          Logout
                        </a>
                      </div>
                    </li>
                  </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">