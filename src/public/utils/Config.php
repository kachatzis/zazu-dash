<?php

$config = [
      'api_base_url' =>  getenv(API_BASE_URI),
      'api_secret_key' =>  getenv(API_KEY),
      'is_enabled_manager_signup' =>  false,
      'site_maintenance_mode' =>  getenv(MAINTENANCE_MODE),
      'maintenance_url' =>  '/views/under_maintenance/index.php',
      'admin_dashboard_url' =>  '',
      'timezone' =>  'Europe/Athens',
      'seconds_between_login_tries' =>  getenv(SECONDS_BETWEEN_LOGIN_TRIES),
      'seconds_first_login_try' => getenv(SECONDS_FIRST_LOGIN_TRY),
      'version' => 'v19.03.16',
      'header_title' => 'Kotoulas Energy S.A.',
      'footer_title' => 'by Loginet',
      'developer_info' => 'K. Chatzis <kachatzis@ece.auth.gr>',
      'general_christmas_mode'=>false,
      'password_salt_1'=>getenv(PASSWORD_SALT),
      'password_salt_2'=>getenv(PASSWORD_SALT),
      'info_salt'=>getenv(INFO_SALT),
    ];

 ?>
