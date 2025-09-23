# EmailJS Setup Guide for RFQ System

## 🚀 **Step-by-Step EmailJS Setup**

### **Step 1: Sign Up for EmailJS**
1. Go to [https://www.emailjs.com/](https://www.emailjs.com/)
2. Click "Sign Up" and create a free account
3. Verify your email address

### **Step 2: Create Email Service**
1. In EmailJS dashboard, go to "Email Services"
2. Click "Add New Service"
3. Choose your email provider:
   - **Gmail** (recommended for personal use)
   - **Outlook/Hotmail**
   - **Yahoo**
   - **Custom SMTP** (for business emails)
4. Follow the setup instructions for your provider
5. **Save the Service ID** (you'll need this)

### **Step 3: Create Email Template**
1. Go to "Email Templates"
2. Click "Create New Template"
3. Use this template code:

```html
Subject: Order Request {{order_number}} from {{customer_company}}

NEW ORDER REQUEST
=================
Order Number: {{order_number}}
Date: {{order_date}}

CUSTOMER INFORMATION:
--------------------
Company: {{customer_company}}
Contact: {{customer_contact}}
Email: {{customer_email}}
Phone: {{customer_phone}}

ORDER DETAILS:
--------------
{{order_details}}

ORDER SUMMARY:
--------------
Total Products: {{total_products}}
Total Quantity: {{total_quantity}}

ADDITIONAL NOTES:
-----------------
{{customer_notes}}

---
This order was submitted through the RFQ system.
```

4. **Save the Template ID** (you'll need this)

### **Step 4: Get Your User ID**
1. Go to "Account" → "API Keys"
2. Copy your **Public Key** (this is your User ID)

### **Step 5: Update Configuration**
1. Open `emailjs-config.js`
2. Replace the placeholder values:

```javascript
const EMAILJS_CONFIG = {
    serviceId: 'YOUR_SERVICE_ID',        // From Step 2
    templateId: 'YOUR_TEMPLATE_ID',      // From Step 3
    userId: 'YOUR_USER_ID',              // From Step 4
    toEmail: 'export@hmkas.com'          // Your email
};
```

### **Step 6: Test the System**
1. Upload all files to your server
2. Open `RFQ.html` in your browser
3. Add some products to basket
4. Fill out customer form
5. Submit order
6. Check your inbox at `export@hmkas.com`

## 📧 **Email Provider Setup Guides**

### **Gmail Setup:**
1. Enable 2-factor authentication on your Gmail
2. Generate an "App Password"
3. Use your Gmail address and app password in EmailJS

### **Outlook/Hotmail Setup:**
1. Enable 2-factor authentication
2. Generate an app password
3. Use your Outlook email and app password

### **Custom SMTP Setup:**
1. Get SMTP settings from your hosting provider
2. Use SMTP server, port, username, and password
3. Configure in EmailJS

## 🔧 **Troubleshooting**

### **Common Issues:**

#### **"EmailJS not configured" Error:**
- Check that you've updated all three IDs in `emailjs-config.js`
- Make sure the file is uploaded to your server

#### **"Service not found" Error:**
- Verify your Service ID is correct
- Check that your email service is properly connected

#### **"Template not found" Error:**
- Verify your Template ID is correct
- Check that your template is saved and published

#### **Emails not received:**
- Check spam folder
- Verify your email service is working
- Test with a simple email first

## 📊 **EmailJS Free Plan Limits**

- **200 emails per month** (free)
- **2 email services** (free)
- **5 email templates** (free)
- **No credit card required**

## 💰 **Upgrading (If Needed)**

If you need more emails:
- **Personal Plan**: $15/month - 1,000 emails
- **Business Plan**: $25/month - 10,000 emails
- **Enterprise Plan**: Custom pricing

## 🛡️ **Security Notes**

- EmailJS is secure and trusted by millions of users
- Your email credentials are encrypted
- No sensitive data is stored on EmailJS servers
- All communication is over HTTPS

## 📞 **Support**

If you need help:
1. Check EmailJS documentation: [https://www.emailjs.com/docs/](https://www.emailjs.com/docs/)
2. Contact EmailJS support
3. Check the troubleshooting section above

## ✅ **Success Checklist**

- [ ] EmailJS account created
- [ ] Email service configured
- [ ] Email template created
- [ ] Configuration file updated
- [ ] Test order sent successfully
- [ ] Email received in inbox

Once you complete these steps, your RFQ system will send emails reliably to `export@hmkas.com`! 