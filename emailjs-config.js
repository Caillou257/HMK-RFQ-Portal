// EmailJS Configuration
// This file contains the EmailJS setup for reliable email sending

// EmailJS Service Configuration
const EMAILJS_CONFIG = {
    // You'll need to sign up at https://www.emailjs.com/ and get these IDs
    serviceId: 'service_nh40o3a', // Replace with your EmailJS service ID
    templateId: 'template_qtof928', // Replace with your EmailJS template ID
    userId: 'RwozcS96hTyagYQq_', // Replace with your EmailJS user ID
    toEmail: 'export@hmkas.com'
};

// Initialize EmailJS
(function() {
    // Load EmailJS library
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js';
    script.onload = function() {
        try {
            // Initialize EmailJS
            emailjs.init(EMAILJS_CONFIG.userId);
            console.log('EmailJS loaded and initialized successfully');
            console.log('User ID:', EMAILJS_CONFIG.userId);
        } catch (error) {
            console.error('Error initializing EmailJS:', error);
        }
    };
    script.onerror = function() {
        console.error('Failed to load EmailJS library');
    };
    document.head.appendChild(script);
})();

// Function to send email via EmailJS
async function sendEmailViaEmailJS(orderData) {
    try {
        console.log('Starting EmailJS send...');
        console.log('Service ID:', EMAILJS_CONFIG.serviceId);
        console.log('Template ID:', EMAILJS_CONFIG.templateId);
        
        // Prepare email template parameters
        const templateParams = {
            to_email: EMAILJS_CONFIG.toEmail,
            order_number: orderData.orderNumber,
            order_date: orderData.orderDate,
            customer_company: orderData.customer.company,
            customer_contact: orderData.customer.contact,
            customer_email: orderData.customer.email,
            customer_phone: orderData.customer.phone || 'Not provided',
            customer_notes: orderData.customer.notes || 'No additional notes',
            order_details: formatOrderDetailsForEmail(orderData.items),
            total_products: orderData.items.length,
            total_quantity: orderData.items.reduce((sum, item) => sum + parseInt(item.quantity), 0)
        };

        console.log('Template Parameters:', templateParams);

        // Send email using EmailJS
        const response = await emailjs.send(
            EMAILJS_CONFIG.serviceId,
            EMAILJS_CONFIG.templateId,
            templateParams
        );

        console.log('EmailJS Response:', response);

        return {
            success: true,
            message: 'Order sent successfully via EmailJS!',
            orderNumber: orderData.orderNumber
        };

    } catch (error) {
        console.error('EmailJS Error:', error);
        console.error('Error Status:', error.status);
        console.error('Error Text:', error.text);
        
        // Provide more specific error messages
        let errorMessage = 'Failed to send email via EmailJS: ';
        
        if (error.status === 400) {
            errorMessage += 'Bad request - check template parameters';
        } else if (error.status === 401) {
            errorMessage += 'Unauthorized - check your EmailJS credentials';
        } else if (error.status === 404) {
            errorMessage += 'Service or template not found - check your IDs';
        } else if (error.status === 429) {
            errorMessage += 'Too many requests - try again later';
        } else if (error.text) {
            errorMessage += error.text;
        } else {
            errorMessage += 'Unknown error occurred';
        }
        
        throw new Error(errorMessage);
    }
}

// Function to format order details for email template
function formatOrderDetailsForEmail(items) {
    let details = '';
    
    items.forEach((item, index) => {
        const itemNumber = index + 1;
        details += `${itemNumber}. Product: ${item.productCode} - ${item.description}\n`;
        details += `   Category: ${item.category}\n`;
        details += `   Quantity: ${item.quantity}\n`;
        details += `   Color: ${item.color}\n`;
        details += `   MOQ: ${item.moq}\n`;
        details += `   Packing Qty: ${item.packingQty}\n`;
        details += `   Status: ${item.status}\n`;
        
        // Add alternative requests if any
        if (item.requestedAlternatives && item.requestedAlternatives.length > 0) {
            const alternatives = item.requestedAlternatives.map(alt => {
                const labels = {
                    'color': 'Different Color',
                    'size': 'Different Size',
                    'similar-size': 'Similar Size',
                    'similar-product': 'Similar Product & Solution',
                    'remove': 'Remove if Unavailable'
                };
                return labels[alt] || alt;
            }).join(', ');
            
            details += `   ⚠️  ALTERNATIVE REQUESTED: ${alternatives}\n`;
        }
        
        details += `   -------------------------\n`;
    });
    
    return details;
}

// Export for use in main application
window.EMAILJS_CONFIG = EMAILJS_CONFIG;
window.sendEmailViaEmailJS = sendEmailViaEmailJS; 