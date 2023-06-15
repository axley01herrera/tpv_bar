<div class="vertical-menu">
    <div class="navbar-brand-box">
        <a href="" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo base_url("assets/images/logo-sm.png");?>" alt="axley herrera portafolio" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo base_url("assets/images/logo-dark.png");?>" alt="axley herrera portafolio" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    
    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <!-- DASHBOARD -->
                <li class="<?php if($menu_ative == "dashboard"){echo "mm-active";}?>">
                    <a href="<?php echo base_url('Dashboard'); ?>">
                        <i class="icon nav-icon <?php if($menu_ative == "home"){echo "active";}?>" data-feather="home"></i> 
                        <span class="menu-item">
                            Tablero
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>