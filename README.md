# RFQ Web Application - Email Setup

## 📧 Email Configuration

This application now sends order emails directly to your inbox at `export@hmkas.com`.

### 🚀 Quick Setup

1. **Upload Files**: Upload all files to your web server
2. **Configure Email**: Edit `config.php` to set your email address
3. **Test**: Try submitting an order to verify emails are received

### 📁 Required Files

- `RFQ.html` - Main application
- `send-order.php` - Email processing script
- `config.php` - Configuration file
- `products.csv` - Product database

### ⚙️ Configuration

Edit `config.php` to customize settings:

```php
// Email Configuration
define('ORDER_EMAIL', 'export@hmkas.com');  // Your email address
define('COMPANY_NAME', 'HMKAS');            // Your company name

// Logging (optional)
define('LOG_ORDERS', true);                 // Enable order logging
define('LOG_FILE', 'order_log.txt');        // Log file name
```

### 🔧 Server Requirements

- **PHP 7.0+** with mail() function enabled
- **Web server** (Apache, Nginx, etc.)
- **File permissions** for writing log files

### 📧 How It Works

1. **User submits order** → JavaScript sends data to `send-order.php`
2. **PHP processes order** → Formats email and sends to your inbox
3. **Confirmation** → User gets success message
4. **Backup** → Order details saved to log file

### 🛠️ Troubleshooting

#### Emails not received?
1. Check spam folder
2. Verify `ORDER_EMAIL` in `config.php`
3. Check server mail configuration
4. Review `order_log.txt` for errors

#### Server errors?
1. Check PHP error logs
2. Verify file permissions
3. Test PHP mail() function
4. Check CORS settings

### 🔒 Security Notes

- Change `ALLOWED_ORIGINS` in production
- Use HTTPS for secure data transmission
- Consider rate limiting for production use

### 📊 Order Log

Orders are logged to `order_log.txt` with format:
```
2024-01-15 14:30:25 - Order ORD-1705323025000 from Company Name sent to export@hmkas.com
```

### 🆘 Support

If you need help:
1. Check the log files
2. Verify server configuration
3. Test with a simple order
4. Contact your hosting provider about mail settings 