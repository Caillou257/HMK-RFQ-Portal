<?php
// Email Configuration
define('ORDER_EMAIL', 'export@hmkas.com');
define('COMPANY_NAME', 'HMKAS');

// Optional: SMTP Configuration (if you want to use SMTP instead of PHP mail())
define('USE_SMTP', false);
define('SMTP_HOST', 'smtp.yourprovider.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@hmkas.com');
define('SMTP_PASSWORD', 'your-password');
define('SMTP_SECURE', 'tls');

// Logging Configuration
define('LOG_ORDERS', true);
define('LOG_FILE', 'order_log.txt');

// Security Configuration
define('ALLOWED_ORIGINS', ['*']); // Change to your domain for production
?> 