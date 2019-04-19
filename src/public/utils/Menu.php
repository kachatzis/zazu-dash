<?php



class Menu {

  public $links;

  public function __construct() {
    $links = [];
  }

  public function add_link() {
    $links = [];
  }

  public function show_menu(){

    include( 'Config.php'); ?>

    <!-- Left Sidebar  -->
    <div class="left-sidebar">

      <div id="nav-logo" style="z-index: 100; cursor: pointer" onclick="window.location='/';" class="nav-logo"></div>

        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">

                  <br>
                  <li> <a class="has-arrow  " href="" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu"><?php t('Dashboard');?></span></a>
                      <ul aria-expanded="false" class="collapse">
                        <li><a href="/" aria-expanded="true"><i class="fa fa-tachometer"></i><span class="hide-menu">  <?php t('Main Page');?></span></a></li>
                        <li><a href="/customers?&page=1" aria-expanded="true"><i class="fa fa-users"></i><span class="hide-menu">  <?php t('Customers');?></span></a></li>
                        <li><a href="/products" aria-expanded="true"><i class="fa fa-cart-plus"></i><span class="hide-menu">  <?php t('Products');?></span></a></li>
                        <li><a href="/gifts" aria-expanded="true"><i class="fa fa-cart-plus"></i><span class="hide-menu">  <?php t('Gifts');?></span></a></li>
                        <li><a href="/stores" aria-expanded="true"><i class="fa fa-map-marker"></i><span class="hide-menu">  <?php t('Stores');?></span></a></li>
                        <li><a href="/managers" aria-expanded="true"><i class="fa fa-user-circle-o"></i><span class="hide-menu">  <?php t('Managers');?></span></a></li>
                        <li><a href="/card_levels" aria-expanded="true"><i class="fa fa-bars"></i><span class="hide-menu">  <?php t('Card Levels');?></span></a></li>
                        <li><a href="/achievements" aria-expanded="true"><i class="fa fa-star"></i><span class="hide-menu">  <?php t('Achievements');?></span></a></li>
                        <li><a href="/transactions?&page=1" aria-expanded="true"><i class="fa fa-shopping-cart"></i><span class="hide-menu">  <?php t('Transactions');?></span></a></li>
                        <li><a href="/devices" aria-expanded="true"><i class="fa fa-tablet"></i><span class="hide-menu">  <?php t('Devices');?></span></a></li>
                        <!--<li><a href="/credit_offers" aria-expanded="true"><i class="fa fa-calculator"></i><span class="hide-menu">  <?php t('Credits');?></span></a></li>-->
                        <!--<li><a href="/portals" aria-expanded="true"><i class="fa fa-tachometer"></i><span class="hide-menu">  <?php t('Portal');?></span></a></li>-->
                      </ul>
                  </li>


                  <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-phone"></i><span class="hide-menu"><?php t('Marketing');?></span></a>
                      <ul aria-expanded="false" class="collapse">
                          <li><a href="/sms_marketings" aria-expanded="true"><i class="fa fa-phone"></i><span class="hide-menu">  <?php t('SMS Marketing');?></span></a></li>

                      </ul>
                  </li>

                  <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-bar-chart"></i><span class="hide-menu"><?php t('Statistics');?></span></a>
                      <ul aria-expanded="false" class="collapse">
                        <li><a href="/statistics/sales" aria-expanded="true"><i class="fa fa-shopping-cart"></i><span class="hide-menu">  <?php t('Sales');?></span></a></li>
                        <li><a href="/statistics/customers" aria-expanded="true"><i class="fa fa-users"></i><span class="hide-menu">  <?php t('Customers');?></span></a></li>
                        <li><a href="/statistics/levels" aria-expanded="true"><i class="fa fa-bars"></i><span class="hide-menu">  <?php t('Levels');?></span></a></li>
                        <li><a href="/statistics/customer_visits" aria-expanded="true"><i class="fa fa-users"></i><span class="hide-menu">  <?php t('Customer Visits');?></span></a></li>
                      </ul>
                  </li>

                  <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-gear"></i><span class="hide-menu"><?php t('Admin');?></span></a>
                      <ul aria-expanded="false" class="collapse">
                        <li><a href="<?php  //echo $config['admin_dashboard_url']; ?>" aria-expanded="true"><span class="hide-menu"><?php t('Admin Dashboard');?></span></a></li>
                      </ul>
                  </li>-->

                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->

      <div class="nav-bottom-split"></div>
      <a href="/about"><div class="nav-author-logo"></div></a>
    </div>
    <!-- End Left Sidebar  -->
    <?php
  }

}

 ?>
