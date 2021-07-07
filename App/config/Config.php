<?php

define('DEBUG', true);

define('DB_NAME', 'digitmoni_app'); // database name
define('DB_USER', 'root'); // database user
define('DB_PASSWORD', ''); // database password
define('DB_HOST', '127.0.0.1'); // database host *** use IP address to avoid DNS lookup

define('DEFAULT_CONTROLLER', 'Home'); // default controller if there isn't one defined in the url
define('DEFAULT_LAYOUT', 'default'); // if no layout is set in the controller use this layout.


// define('PROOT', '/Otalu/'); // set this to '/' for a live server.

define('SITE_TITLE', 'Otalu MVC Framework'); // This will be used if no site title is set
// define('MENU_BRAND', 'Otalu'); //This is the Brand text in the menu

// define('CURRENT_USER_SESSION_NAME', 'kwXeusqldkiIKjehsLQZJFKJ'); //session name for logged in user
// define('REMEMBER_ME_COOKIE_NAME', 'JAJEI6382LSJVlkdjfh3801jvD'); // cookie name for logged in user remember me
// define('REMEMBER_ME_COOKIE_EXPIRY', 2592000); // time in seconds for remember me cookie to live (30 days)

define('ACCESS_RESTRICTED', 'Restricted'); //controller name for the restricted redirect



define('GET_MSG', 'Only GET requests are allowed.');
define('ACL_MSG', 'Page not found! Go back to previous page.');
define('POST_MSG', 'Only POST requests are allowed.');
define('NOT_FOUND_MSG', '');
define('ALL_INV_MSG', 'Here is a list of our offers.');
define('WNT_WRNG_MSG', 'Ah! Something went wrong, our team is on it.');
define('JSON_MSG', 'Input is not a valid json format.');
define('SERVER_MSG', 'There is a problem. We cant initiate a transaction at the moment.');
