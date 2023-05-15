<!-- Topbar Start -->
            <div class="navbar-custom">
                   
    
                    <!-- LOGO -->
                    <div class="logo-box">
                        
                    </div>

                    <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
                        <li>
                            <button class="button-menu-mobile disable-btn waves-effect">
                                <i class="fe-menu"></i>
                            </button>
                        </li>
    
                        <li>
                            <h4 class="page-title-main"><?= $page_title ?></h4>
                        </li>
            
                    </ul>

                    <div class="clearfix"></div> 
               
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="h-100" data-simplebar>

                     <!-- User box -->
                    <div class="user-box text-center">


                        <p class="text-muted left-user-info"><?= session()->get('username') ?></p>

                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href="#" class="text-muted left-user-info">
                                    <i class="mdi mdi-cog"></i>
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a href="#">
                                    <i class="mdi mdi-power"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul id="side-menu">

                            
                
                            

                            <li>
                                <a href="#contacts" data-bs-toggle="collapse">
                                    <i class="mdi mdi-book-open-page-variant-outline"></i>
                                    <span> Posts </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="contacts">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="<?= base_url('all_post') ?>">All Posts</a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url('new_post') ?>">New Post</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
							<li>
                                <a href="<?= base_url('users') ?>">
                                    <i class="mdi mdi-account-multiple-plus-outline"></i>
                                    <span> Personnel </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                
                            </li>

                            

                            

                            

                            

                            
                            

                            
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->
            