<?php

  include 'utils/Redirect.php';
  include 'utils/Config.php';
  //$config = new Config();
  //$config = $config->get();
  if($config['site_maintenance_mode']){
    require __DIR__ . $config['maintenance_url'];
    exit();
  }


  session_start(); // Start Session for all pages

  require 'utils/AltoRouter.php';
  require 'utils/Translate.php';

  // Initialize Router
  $router = new AltoRouter();

  // Select Timezone
  date_default_timezone_set($config['timezone']);



  // Pages
  /*
  $pages = [
    ['name'=>'Home', 'url'=>'/', 'file'=>'home.php'],
    ['name'=>'POS_Redemption', 'url'=>'/pos/redemption', 'file'=>'POS_Redemption.php']
  ];*/


  /*foreach ($pages as $page_key=>$page){

    $this->directory = '/views/';
    if (isset($page['directory'])){
      $directory = $page['directory'];
    }

    $router->map( 'GET', $page['url'], function() { 
      //if (isset($page['file'])){
        require __DIR__ . $directory . $page['file']; 
      //}
    }, $page['name'].'_GET');

    $router->map( 'POST', $page['url'], function() {
      //if (isset($page['file'])){
        require __DIR__ . $directory . $page['file']; 
      //} 
    }, $page['name'].'_POST');
  }*/


  // Create Paths

  $router->map( 'GET', '/', function() {  require __DIR__ . '/views/home.php'; }, 'Home_GET');
  $router->map( 'POST', '/', function() {  require __DIR__ . '/views/home.php'; }, 'Home_POST');
  
  // Login
  $router->map( 'GET', '/login', function() {require __DIR__ . '/views/Login.php';}, 'Login_GET');
  $router->map( 'POST', '/login', function() {require __DIR__ . '/views/Login.php';}, 'Login_POST');

  // Logout
  $router->map( 'GET', '/logout', function() {require __DIR__ . '/views/Logout.php';}, 'Logout_GET');
  $router->map( 'POST', '/logout', function() {require __DIR__ . '/views/Logout.php';}, 'Logout_POST');


  // Customers
  $router->map( 'GET', '/customers', function() { require __DIR__ . '/views/Customers.php'; }, 'Customers_GET');
  $router->map( 'POST', '/customers', function() { require __DIR__ . '/views/Customers.php'; }, 'Customers_POST');

  // Customers_Id
  $router->map( 'GET', '/customers/[i:customer_id]', function($customer_id) { require __DIR__ . '/views/Customers_Id.php'; }, 'Customers_Id_GET');
  $router->map( 'POST', '/customers/[i:customer_id]', function($customer_id) { require __DIR__ . '/views/Customers_Id.php'; }, 'Customers_Id_POST');

  // Customers_Id_Transactions
  $router->map( 'GET', '/customers/[i:customer_id]/transactions', function($customer_id) { require __DIR__ . '/views/Customers_Id_Transactions.php'; }, 'Customers_Id_Transactions_GET');

  // Customers_Id_Products
  $router->map( 'GET', '/customers/[i:customer_id]/products', function($customer_id) { require __DIR__ . '/views/Customers_Id_Products.php'; }, 'Customers_Id_Products_GET');

    // Customers_Id_Available_Gifts
  $router->map( 'GET', '/customers/[i:customer_id]/available_gifts', function($customer_id) { require __DIR__ . '/views/Customers_Id_Available_Gifts.php'; }, 'Customers_Id_Available_Gifts_GET');

  // Customers_Id_Redemption
  $router->map( 'GET', '/customers/[i:customer_id]/redemption', function($customer_id) { require __DIR__ . '/views/Customers_Id_Redemption.php'; }, 'Customers_Id_Redemption_GET');
  $router->map( 'POST', '/customers/[i:customer_id]/redemption', function($customer_id) { require __DIR__ . '/views/Customers_Id_Redemption.php'; }, 'Customers_Id_Redemption_POST');

  // Customers_Id_Topup
  $router->map( 'GET', '/customers/[i:customer_id]/topup', function($customer_id) { require __DIR__ . '/views/Customers_Id_Topup.php'; }, 'Customers_Id_Topup_GET');
    // Customers_Id_Topup
  $router->map( 'POST', '/customers/[i:customer_id]/topup', function($customer_id) { require __DIR__ . '/views/Customers_Id_Topup.php'; }, 'Customers_Id_Topup_POST');


  // Products
  $router->map( 'GET', '/products', function() { require __DIR__ . '/views/Products.php'; }, 'Products');

  // Gifts
  $router->map( 'GET', '/gifts', function() { require __DIR__ . '/views/Gifts.php'; }, 'Gifts');


  // Products_Id
  $router->map( 'GET', '/products/[i:good_id]', function($good_id) { require __DIR__ . '/views/Products_Id.php'; }, 'Products_Id_GET');
  $router->map( 'POST', '/products/[i:good_id]', function($good_id) { require __DIR__ . '/views/Products_Id.php'; }, 'Products_Id_POST');

  // Products_Id_Credit_Offers
  $router->map( 'GET', '/products/[i:good_id]/credit_offers', function($good_id) { require __DIR__ . '/views/Products_Id_Credit_Offers.php'; }, 'Products_Id_Credit_Offers_GET');
  $router->map( 'POST', '/products/[i:good_id]/credit_offers', function($good_id) { require __DIR__ . '/views/Products_Id_Credit_Offers.php'; }, 'Products_Id_Credit_Offers_POST');

  // Products_Id_Credit_Offers_Id
  $router->map( 'GET', '/products/[i:good_id]/credit_offers/[i:credit_offer_id]', function($good_id, $credit_offer_id) { require __DIR__ . '/views/Products_Id_Credit_Offers_Id.php'; }, 'Products_Id_Credit_Offers_Id_GET');
  $router->map( 'POST', '/products/[i:good_id]/credit_offers/[i:credit_offer_id]', function($good_id, $credit_offer_id) { require __DIR__ . '/views/Products_Id_Credit_Offers_Id.php'; }, 'Products_Id_Credit_Offers_Id_POST');

  // Products_Id_Portal
  $router->map( 'GET', '/products/[i:good_id]/portal', function($good_id) { require __DIR__ . '/views/Products_Id_Portal.php'; }, 'Products_Id_Portal_GET');
  $router->map( 'POST', '/products/[i:good_id]/portal', function($good_id) { require __DIR__ . '/views/Products_Id_Portal.php'; }, 'Products_Id_Portal_POST');

    // Products_Id_Statistics
  $router->map( 'GET', '/products/[i:good_id]/statistics', function($good_id) { require __DIR__ . '/views/Products_Id_Statistics.php'; }, 'Products_Id_Statistics_GET');


  // Stores
  $router->map( 'GET', '/stores', function() { require __DIR__ . '/views/Stores.php'; }, 'Stores');

  // Stores_Id
  $router->map( 'GET', '/stores/[i:store_id]', function($store_id) { require __DIR__ . '/views/Stores_Id.php'; }, 'Stores_Id_GET');
  $router->map( 'POST', '/stores/[i:store_id]', function($store_id) { require __DIR__ . '/views/Stores_Id.php'; }, 'Stores_Id_POST');

  $router->map( 'GET', '/signup', function() { require __DIR__ . '/views/SignUp.php'; }, 'SignUp_GET');
  $router->map( 'POST', '/signup', function() { require __DIR__ . '/views/SignUp.php'; }, 'SignUp_POST');

   // Stores_Id_Statistics
  $router->map( 'GET', '/stores/[i:store_id]/statistics', function($store_id) { require __DIR__ . '/views/Stores_Id_Statistics.php'; }, 'Stores_Id_Statistics_GET');

  // Card_Levels
  $router->map( 'GET', '/card_levels', function() { require __DIR__ . '/views/Card_Levels.php'; }, 'Card_Levels');

  // Card_Levels_Id
  $router->map( 'GET', '/card_levels/[i:card_level_id]', function($card_level_id) { require __DIR__ . '/views/Card_Levels_Id.php'; }, 'Card_Levels_Id_GET');
  $router->map( 'POST', '/card_levels/[i:card_level_id]', function($card_level_id) { require __DIR__ . '/views/Card_Levels_Id.php'; }, 'Card_Levels_Id_POST');

  // Card_Levels_Id_Portal
  $router->map( 'GET', '/card_levels/[i:card_level_id]/portal', function($card_level_id) { require __DIR__ . '/views/Card_Levels_Id_Portal.php'; }, 'Card_Levels_Id_Portal_GET');
  $router->map( 'POST', '/card_levels/[i:card_level_id]/portal', function($card_level_id) { require __DIR__ . '/views/Card_Levels_Id_Portal.php'; }, 'Card_Levels_Id_Portal_POST');


  // Cards
  $router->map( 'GET', '/cards', function() { require __DIR__ . '/views/Cards.php'; }, 'Cards');

  // Cards_Id
  $router->map( 'GET', '/cards/[i:card_id]', function($card_id) { require __DIR__ . '/views/Cards_Id.php'; }, 'Cards_Id_GET');
  $router->map( 'POST', '/cards/[i:card_id]', function($card_id) { require __DIR__ . '/views/Cards_Id.php'; }, 'Cards_Id_POST');


  // Achievements
  $router->map( 'GET', '/achievements', function() { require __DIR__ . '/views/Achievements.php'; }, 'Achievements');

  // Achevements_Id
  $router->map( 'GET', '/achievements/[i:achievement_id]', function($achievement_id) { require __DIR__ . '/views/Achievements_Id.php'; }, 'Achievements_Id_GET');
  $router->map( 'POST', '/achievements/[i:achievement_id]', function($achievement_id) { require __DIR__ . '/views/Achievements_Id.php'; }, 'Achievements_Id_POST');


  // Transactions
  $router->map( 'GET', '/transactions', function() { require __DIR__ . '/views/Transactions.php'; }, 'Transactions');

  // Transactions_Unsync
  $router->map( 'GET', '/transactions_unsync', function() { require __DIR__ . '/views/Transactions_Unsync.php'; }, 'Transactions_Unsync');

  // Transactions_Id
  $router->map( 'GET', '/transactions/[i:transaction_id]', function($transaction_id) { require __DIR__ . '/views/Transactions_Id.php'; }, 'Transactions_Id_GET');
  $router->map( 'POST', '/transactions/[i:transaction_id]', function($transaction_id) { require __DIR__ . '/views/Transactions_Id.php'; }, 'Transactions_Id_POST');


  // Devices
  $router->map( 'GET', '/devices', function() { require __DIR__ . '/views/Devices.php'; }, 'Devices');

  // Devices_Id
  $router->map( 'GET', '/devices/[i:device_id]', function($device_id) { require __DIR__ . '/views/Devices_Id.php'; }, 'Devices_Id_GET');
  $router->map( 'POST', '/devices/[i:device_id]', function($device_id) { require __DIR__ . '/views/Devices_Id.php'; }, 'Devices_Id_POST');


  // Sms_Marketings
  $router->map( 'GET', '/sms_marketings', function() { require __DIR__ . '/views/Sms_Marketings.php'; }, 'Sms_Marketings');

  // Sms_Marketings_Id
  $router->map( 'GET', '/sms_marketings/[i:sms_marketing_id]', function($sms_marketing_id) { require __DIR__ . '/views/Sms_Marketings_Id.php'; }, 'Sms_Marketings_Id_GET');
  $router->map( 'POST', '/sms_marketings/[i:sms_marketing_id]', function($sms_marketing_id) { require __DIR__ . '/views/Sms_Marketings_Id.php'; }, 'Sms_Marketings_Id_POST');

  // Sms_Marketings_Id_Customers
  $router->map( 'GET', '/sms_marketings/[i:sms_marketing_id]/customers', function($sms_marketing_id) { require __DIR__ . '/views/Sms_Marketings_Id_Customers.php'; }, 'Sms_Marketings_Id_Customers_GET');
  $router->map( 'POST', '/sms_marketings/[i:sms_marketing_id]/customers', function($sms_marketing_id) { require __DIR__ . '/views/Sms_Marketings_Id_Customers.php'; }, 'Sms_Marketings_Id_Customers_POST');

  /*
  // Email_Marketings
  $router->map( 'GET', '/email_marketings', function() { require __DIR__ . '/views/Sms_Marketings.php'; }, 'Email_Marketings');

  // Email_Marketings_Id
  $router->map( 'GET', '/email_marketings/[i:email_marketing_id]', function($email_marketing_id) { require __DIR__ . '/views/Email_Marketings_Id.php'; }, 'Email_Marketings_Id_GET');
  $router->map( 'POST', '/email_marketings/[i:email_marketing_id]', function($email_marketing_id) { require __DIR__ . '/views/Email_marketings_Id.php'; }, 'Email_Marketinsg_Id_POST');
  */

  // Credit_Offers
  $router->map( 'GET', '/credit_offers', function() { require __DIR__ . '/views/Credit_Offers.php'; }, 'Credit_Offers');

  // Credit_Offers_Id
  $router->map( 'GET', '/credit_offers/[i:credit_offer_id]', function($credit_offer_id) { require __DIR__ . '/views/Credit_Offers_Id.php'; }, 'Credit_Offers_Id_GET');
  $router->map( 'POST', '/credit_offers/[i:credit_offer_id]', function($credit_offer_id) { require __DIR__ . '/views/Credit_Offers_Id.php'; }, 'Credit_Offers_Id_POST');


  // Portals
  $router->map( 'GET', '/portals', function() { require __DIR__ . '/views/Portals.php'; }, 'Portals_GET');

  // Portals_Id
  $router->map( 'GET', '/portals/[i:portal_id]', function($portal_id) { require __DIR__ . '/views/Portals_Id.php'; }, 'Portals_Id_GET');
  $router->map( 'POST', '/portals/[i:portal_id]', function($portal_id) { require __DIR__ . '/views/Portals_Id.php'; }, 'Portals_Id_POST');

  // Portals_Id_Marketing
  $router->map( 'GET', '/portals/[i:portal_id]/marketing', function($portal_id) { require __DIR__ . '/views/Portals_Id_Marketing.php'; }, 'Portals_Id_Marketing_GET');
  $router->map( 'POST', '/portals/[i:portal_id]/marketing', function($portal_id) { require __DIR__ . '/views/Portals_Id_Marketing.php'; }, 'Portals_Id_Marketing_POST');

  // Portals_Id_Settings
  $router->map( 'GET', '/portals/[i:portal_id]/settings', function($portal_id) { require __DIR__ . '/views/Portals_Id_Settings.php'; }, 'Portals_Id_Settings_GET');
  $router->map( 'POST', '/portals/[i:portal_id]/settings', function($portal_id) { require __DIR__ . '/views/Portals_Id_Settings.php'; }, 'Portals_Id_Settings_POST');

  // Portals_Id_Content
  $router->map( 'GET', '/portals/[i:portal_id]/content', function($portal_id) { require __DIR__ . '/views/Portals_Id_Content.php'; }, 'Portals_Id_Content_GET');
  $router->map( 'POST', '/portals/[i:portal_id]/content', function($portal_id) { require __DIR__ . '/views/Portals_Id_Content.php'; }, 'Portals_Id_Content_POST');



  // Managers
  $router->map( 'GET', '/managers', function() { require __DIR__ . '/views/Managers.php'; }, 'Managers_GET');

  // Managers_Id
  $router->map( 'GET', '/managers/[i:manager_id]', function($manager_id) { require __DIR__ . '/views/Managers_Id.php'; }, 'Managers_Id_GET');
  $router->map( 'POST', '/managers/[i:anager_id]', function($manager_id) { require __DIR__ . '/views/Managers_Id.php'; }, 'Managers_Id_POST');

  // Managers_Id_Statistics
    $router->map( 'GET', '/managers/[i:manager_id]/statistics', function($manager_id) { require __DIR__ . '/views/Managers_Id_Statistics.php'; }, 'Managers_Id_Statistics_GET');



  // Statistics_Sales
  $router->map( 'GET', '/statistics/sales', function() { require __DIR__ . '/views/Statistics_Sales.php'; }, 'Statistics_Sales_GET');

  // Statistics_Customers
  $router->map( 'GET', '/statistics/customers', function() { require __DIR__ . '/views/Statistics_Customers.php'; }, 'Statistics_Customers_GET');

  // Statistics_Levels
  $router->map( 'GET', '/statistics/levels', function() { require __DIR__ . '/views/Statistics_Levels.php'; }, 'Statistics_Levels_GET');


   // Statistics_Customer_Visits
  $router->map( 'GET', '/statistics/customer_visits', function() { require __DIR__ . '/views/Statistics_Customer_Visits.php'; }, 'Statistics_Customer_Visits_GET');
     // Statistics_Customer_Visits
  $router->map( 'POST', '/statistics/customer_visits', function() { require __DIR__ . '/views/Statistics_Customer_Visits.php'; }, 'Statistics_Customer_Visits_POST');



  // Dev
  $router->map( 'GET', '/dev'.date('Ymd'), function() { require __DIR__ . '/views/dev.php'; }, 'dev_GET');

  // About
  $router->map( 'GET', '/about', function() {  require __DIR__ . '/views/About.php'; }, 'About_GET');



  // Match Routes and Load file
  $match = $router->match();
  if( $match && is_callable( $match['target'] ) ) {
	   call_user_func_array( $match['target'], $match['params'] );
  } else {
	   // no route was matched
	   //header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
     require __DIR__ . '/views/error-404.php';
  }


?>
