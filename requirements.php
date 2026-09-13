<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Build Your Requirements';

$websiteTypes = db()->fetchAll("SELECT * FROM website_types WHERE is_active = 1 ORDER BY display_order");

$pageOptions = [
    'Home', 'About', 'Services', 'Products', 'Team', 'Faculty', 'Departments',
    'Courses', 'Admissions', 'Contact', 'FAQ', 'Blog', 'News', 'Events',
    'Gallery', 'Testimonials', 'Portfolio', 'Pricing', 'Careers', 'Downloads',
    'Privacy Policy', 'Terms & Conditions', 'Sitemap'
];

$projectPurposes = [
    'business' => 'Business / Corporate',
    'education' => 'Education / Academy',
    'portfolio' => 'Portfolio / Personal Brand',
    'personal' => 'Personal Website',
    'blog-media' => 'Blog / Media',
    'ecommerce' => 'E-commerce / Online Store',
    'restaurant' => 'Restaurant / Food',
    'hotel' => 'Hotel / Hospitality',
    'real-estate' => 'Real Estate',
    'healthcare' => 'Healthcare / Medical',
    'ngo' => 'NGO / Non-profit',
    'government' => 'Government / Public',
    'travel' => 'Travel / Tourism',
    'finance' => 'Finance / Banking',
    'saas' => 'SaaS / Software',
    'marketplace' => 'Marketplace / Directory',
    'community' => 'Community / Social',
    'event' => 'Event / Conference'
];

$designStyles = [
    'modern' => 'Modern',
    'minimal' => 'Minimal',
    'corporate' => 'Corporate',
    'professional' => 'Professional',
    'colorful' => 'Colorful',
    'luxury' => 'Luxury',
    'creative' => 'Creative',
    'clean' => 'Clean',
    'bold' => 'Bold',
    'government' => 'Government',
    'academic' => 'Academic',
    'technology' => 'Technology'
];

$existingWebsiteProblems = [
    'outdated-design' => 'Outdated Design',
    'slow' => 'Slow Loading',
    'not-mobile-friendly' => 'Not Mobile Friendly',
    'difficult-to-manage' => 'Difficult to Manage',
    'poor-seo' => 'Poor SEO',
    'security-problems' => 'Security Problems',
    'no-admin' => 'No Admin Panel',
    'missing-features' => 'Missing Features',
    'poor-ux' => 'Poor User Experience'
];

$cmsItems = [
    'pages' => 'Pages',
    'text' => 'Text Content',
    'images' => 'Images',
    'blog' => 'Blog Posts',
    'news' => 'News',
    'events' => 'Events',
    'products' => 'Products',
    'services' => 'Services',
    'users' => 'Users',
    'orders' => 'Orders',
    'bookings' => 'Bookings',
    'testimonials' => 'Testimonials',
    'gallery' => 'Gallery',
    'notices' => 'Notices',
    'downloads' => 'Downloads',
    'contact-messages' => 'Contact Messages',
    'forms' => 'Forms',
    'seo' => 'SEO Settings',
    'website-settings' => 'Website Settings'
];

$userFeatures = [
    'user-registration' => 'User Registration',
    'user-login' => 'User Login',
    'user-dashboard' => 'User Dashboard',
    'social-login' => 'Social Login',
    'email-verification' => 'Email Verification',
    'phone-verification' => 'Phone Verification',
    'forgot-password' => 'Forgot Password',
    'profile-management' => 'Profile Management',
    'notifications' => 'Notifications',
    'favorites' => 'Favorites / Wishlist'
];

$userRoles = [
    'super-admin' => 'Super Admin',
    'admin' => 'Admin',
    'manager' => 'Manager',
    'staff' => 'Staff',
    'teacher' => 'Teacher',
    'student' => 'Student',
    'customer' => 'Customer',
    'vendor' => 'Vendor',
    'seller' => 'Seller',
    'moderator' => 'Moderator',
    'editor' => 'Editor',
    'accountant' => 'Accountant'
];

$ecommerceFeatures = [
    'product-categories' => 'Product Categories',
    'product-variations' => 'Product Variations (Size, Color)',
    'inventory' => 'Inventory Management',
    'shopping-cart' => 'Shopping Cart',
    'wishlist' => 'Wishlist',
    'checkout' => 'Checkout System',
    'order-management' => 'Order Management',
    'coupons' => 'Coupons & Discounts',
    'product-reviews' => 'Product Reviews',
    'shipping' => 'Shipping Management',
    'tax-management' => 'Tax Management',
    'invoice' => 'Invoice Generation',
    'refunds' => 'Refund System',
    'order-tracking' => 'Order Tracking'
];

$paymentMethods = [
    'esewa' => 'eSewa',
    'khalti' => 'Khalti',
    'connectips' => 'ConnectIPS',
    'bank-transfer' => 'Bank Transfer',
    'credit-debit-card' => 'Credit/Debit Card',
    'paypal' => 'PayPal',
    'stripe' => 'Stripe'
];

$bookingTypes = [
    'appointment' => 'Appointment',
    'hotel-room' => 'Hotel Room',
    'restaurant-table' => 'Restaurant Table',
    'event-ticket' => 'Event Ticket',
    'consultation' => 'Consultation',
    'service' => 'Service Booking',
    'vehicle' => 'Vehicle Rental',
    'facility' => 'Facility Booking',
    'course' => 'Course Enrollment'
];

$bookingFeatures = [
    'calendar' => 'Calendar View',
    'availability' => 'Availability Check',
    'time-slots' => 'Time Slots',
    'booking-confirmation' => 'Booking Confirmation',
    'cancellation' => 'Cancellation System',
    'booking-approval' => 'Booking Approval'
];

$communicationFeatures = [
    'contact-form' => 'Contact Form',
    'live-chat' => 'Live Chat',
    'whatsapp' => 'WhatsApp Integration',
    'email' => 'Email System',
    'sms-integration' => 'SMS Integration',
    'push-notifications' => 'Push Notifications',
    'automated-emails' => 'Automated Emails'
];

$seoLevels = [
    'none' => 'No SEO',
    'basic' => 'Basic SEO',
    'standard' => 'Standard SEO',
    'advanced' => 'Advanced SEO'
];

$seoFeaturesList = [
    'meta-tags' => 'Meta Tags Management',
    'sitemap' => 'XML Sitemap',
    'structured-data' => 'Structured Data / Schema',
    'canonical-urls' => 'Canonical URLs',
    'open-graph' => 'Open Graph Tags',
    'robots-txt' => 'Robots.txt',
    'page-speed' => 'Page Speed Optimization',
    'image-seo' => 'Image Optimization',
    'keyword-tracking' => 'Keyword Tracking'
];

$analyticsFeatures = [
    'google-analytics' => 'Google Analytics',
    'search-console' => 'Google Search Console',
    'meta-pixel' => 'Meta Pixel / Facebook',
    'conversion-tracking' => 'Conversion Tracking'
];

$securityFeatures = [
    'ssl' => 'SSL Certificate',
    '2fa' => 'Two-Factor Authentication',
    'captcha' => 'CAPTCHA Protection',
    'activity-logs' => 'Activity Logs',
    'auto-backups' => 'Automatic Backups'
];

$mapFeatures = [
    'gmaps' => 'Google Maps',
    'openstreetmap' => 'OpenStreetMap',
    'location-search' => 'Location Search',
    'nearby-search' => 'Nearby Search'
];

$maintenanceFeatures = [
    'security-updates' => 'Security Updates',
    'content-updates' => 'Content Updates',
    'tech-support' => 'Technical Support',
    'monthly-maintenance' => 'Monthly Maintenance Plan'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root{--primary:#2563eb;--primary-dark:#1d4ed8;--bg:#f8fafc;--surface:#fff;--border:#e2e8f0;--text:#1e293b;--text-muted:#64748b;--radius:6px;--success:#059669;}
*{box-sizing:border-box;}
body{margin:0;font-family:system-ui,-apple-system,'Segoe UI',sans-serif;background:var(--bg);color:var(--text);font-size:15px;line-height:1.5;display:flex;flex-direction:column;min-height:100vh;}
body>.top-bar{flex:0 0 auto;}
body>.progress-container{flex:0 0 auto;}
.main-content{padding:24px 0 60px;flex:1 1 auto;}
.top-bar{background:#0f172a;color:#fff;padding:10px 0;font-size:13px;}
.top-bar .brand{font-weight:600;font-size:14px;}
.progress-container{background:var(--surface);border-bottom:1px solid var(--border);padding:16px 0;}
.progress-bar-wrapper{max-width:900px;margin:0 auto;}
.step-indicators{display:flex;align-items:center;justify-content:space-between;position:relative;}
.step-indicators::before{content:'';position:absolute;top:16px;left:20px;right:20px;height:2px;background:var(--border);z-index:0;}
.step-progress-fill{position:absolute;top:16px;left:20px;height:2px;background:var(--primary);z-index:1;transition:width .3s ease;width:0;}
.step-item{display:flex;flex-direction:column;align-items:center;position:relative;z-index:2;cursor:pointer;flex:0 0 auto;width:70px;}
.step-dot{width:32px;height:32px;border-radius:50%;background:var(--surface);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:var(--text-muted);transition:all .2s;}
.step-item.active .step-dot{background:var(--primary);border-color:var(--primary);color:#fff;}
.step-item.completed .step-dot{background:var(--success);border-color:var(--success);color:#fff;}
.step-label{font-size:11px;color:var(--text-muted);margin-top:6px;text-align:center;white-space:nowrap;}
.step-item.active .step-label{color:var(--primary);font-weight:600;}
.step-item.completed .step-label{color:var(--success);}
.form-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:28px 32px;margin-bottom:20px;}
.form-step{display:none;}
.form-step.active{display:block;animation:slideUp .35s ease;}
@keyframes slideUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
.step-title{font-size:18px;font-weight:700;color:var(--text);margin-bottom:4px;}
.step-subtitle{font-size:13px;color:var(--text-muted);margin-bottom:24px;}
.form-label{display:block;font-weight:600;font-size:13px;color:var(--text);margin-bottom:6px;}
.form-label .required{color:#dc2626;}
.form-control,.form-select{width:100%;border:1px solid var(--border);border-radius:var(--radius);padding:8px 12px;font-size:14px;font-family:inherit;color:var(--text);background:var(--surface);transition:border-color .15s;}
.form-control:focus,.form-select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 2px rgba(37,99,235,.15);}
textarea.form-control{resize:vertical;min-height:80px;}
.radio-cards,.checkbox-cards{display:grid;gap:8px;}
.radio-cards.cols-2,.checkbox-cards.cols-2{grid-template-columns:repeat(2,1fr);}
.radio-cards.cols-3,.checkbox-cards.cols-3{grid-template-columns:repeat(3,1fr);}
.radio-cards.cols-4,.checkbox-cards.cols-4{grid-template-columns:repeat(4,1fr);}
@media(max-width:768px){.radio-cards.cols-3,.radio-cards.cols-4,.checkbox-cards.cols-3,.checkbox-cards.cols-4{grid-template-columns:repeat(2,1fr);}}
@media(max-width:576px){.radio-cards.cols-2,.radio-cards.cols-3,.radio-cards.cols-4,.checkbox-cards.cols-2,.checkbox-cards.cols-3,.checkbox-cards.cols-4{grid-template-columns:1fr;}}
.card-option{border:1px solid var(--border);border-radius:var(--radius);padding:10px 14px;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:10px;background:var(--surface);position:relative;}
.card-option:hover{border-color:#93c5fd;background:#f8fafc;}
.card-option.selected{border-color:var(--primary);background:#eff6ff;}
.card-option input{display:none;}
.card-option .card-icon{font-size:16px;color:var(--text-muted);flex-shrink:0;width:20px;text-align:center;}
.card-option.selected .card-icon{color:var(--primary);}
.card-option .card-label{font-size:13px;font-weight:500;color:var(--text);}
.card-option .card-check{position:absolute;top:6px;right:6px;width:18px;height:18px;border-radius:50%;background:var(--primary);color:#fff;display:none;align-items:center;justify-content:center;font-size:10px;}
.card-option.selected .card-check{display:flex;}
.page-checkbox-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:6px;}
.page-checkbox-item{border:1px solid var(--border);border-radius:var(--radius);padding:8px 12px;display:flex;align-items:center;gap:8px;cursor:pointer;transition:all .15s;font-size:13px;}
.page-checkbox-item:hover{border-color:#93c5fd;background:#f8fafc;}
.page-checkbox-item.selected{border-color:var(--primary);background:#eff6ff;}
.page-checkbox-item input{accent-color:var(--primary);width:16px;height:16px;}
.btn-nav{padding:10px 24px;border-radius:var(--radius);font-weight:600;font-size:14px;border:none;cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;gap:6px;}
.btn-prev{background:var(--surface);color:var(--text);border:1px solid var(--border);}
.btn-prev:hover{background:#f1f5f9;}
.btn-next{background:var(--primary);color:#fff;}
.btn-next:hover{background:var(--primary-dark);}
.btn-submit{background:var(--success);color:#fff;}
.btn-submit:hover{background:#047857;}
.price-sidebar{position:sticky;top:20px;}
.price-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.price-card-header{background:#0f172a;color:#fff;padding:14px 18px;}
.price-card-header h5{margin:0;font-size:14px;font-weight:600;}
.price-card-body{padding:16px 18px;}
.price-item{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:13px;}
.price-item:last-child{border-bottom:none;}
.price-total{border-top:2px solid var(--border);margin-top:8px;padding-top:10px;display:flex;justify-content:space-between;align-items:center;}
.price-total .label{font-weight:700;font-size:14px;}
.price-total .value{font-weight:700;font-size:18px;color:var(--primary);}
.price-note{font-size:11px;color:var(--text-muted);text-align:center;margin:10px 0 0;}
.conditional-section{overflow:hidden;max-height:0;opacity:0;transition:max-height .4s cubic-bezier(.4,0,.2,1),opacity .3s ease,padding .3s ease;}
.conditional-section.visible{max-height:800px;opacity:1;}
.review-section{background:#f8fafc;border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:12px;}
.review-section h6{font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin:0 0 8px;font-weight:600;}
.review-item{display:flex;justify-content:space-between;padding:4px 0;font-size:13px;border-bottom:1px solid #f1f5f9;}
.review-item:last-child{border-bottom:none;}
.review-item .label{color:var(--text-muted);}
.review-item .value{font-weight:500;text-align:right;max-width:60%;}
.section-divider{border:none;border-top:1px solid var(--border);margin:20px 0;}
.error-message{color:#dc2626;font-size:12px;display:none;margin-top:4px;}
.error-message.visible{display:block;}
.step-validation-error{border-color:#dc2626 !important;}
.file-upload-area{border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer;transition:all .15s;}
.file-upload-area:hover{border-color:#93c5fd;background:#f8fafc;}
.info-badge{background:#fef3c7;color:#92400e;border-radius:var(--radius);padding:4px 10px;font-size:12px;display:inline-block;}
.badge-selected{background:#eff6ff;color:var(--primary);border-radius:4px;padding:2px 8px;font-size:11px;font-weight:500;}
.floating-price-toggle{display:none;position:fixed;bottom:20px;right:20px;z-index:998;background:var(--primary);color:#fff;border:none;border-radius:50%;width:56px;height:56px;font-size:14px;font-weight:700;box-shadow:0 4px 12px rgba(37,99,235,.4);cursor:pointer;align-items:center;justify-content:center;}
.floating-price-toggle .price-icon{font-size:20px;}
.floating-price-toggle .price-text{font-size:10px;margin-top:-2px;}
.price-close-btn{position:absolute;top:8px;right:8px;background:none;border:none;font-size:18px;color:var(--text-muted);cursor:pointer;padding:4px 8px;line-height:1;}
.price-close-btn:hover{color:var(--text);}
.site-footer{background:#0f172a;color:#94a3b8;text-align:center;padding:8px 12px;font-size:11px;line-height:1.3;margin-top:auto;flex:0 0 auto;}
.site-footer strong{color:#e2e8f0;}
@media(max-width:991px){.floating-price-toggle{display:flex;flex-direction:column;gap:0;}.price-sidebar{display:block;position:fixed;top:0;left:0;bottom:0;right:auto;width:85%;max-width:360px;z-index:1001;padding:0;background:var(--surface);box-shadow:4px 0 24px rgba(0,0,0,.2);transform:translateX(-100%);transition:transform .3s cubic-bezier(.4,0,.2,1);overflow-y:auto;}.price-sidebar.mobile-show{transform:translateX(0);}.price-sidebar-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.4);z-index:1000;opacity:0;transition:opacity .3s ease;}.price-sidebar-overlay.active{display:block;opacity:1;}.price-sidebar .price-card{border:none;border-radius:0;min-height:100vh;}.price-sidebar .price-card-header{border-radius:0;padding:16px 18px;position:sticky;top:0;z-index:1;}.price-sidebar .price-card-body{padding:20px 18px;}.step-label{display:none;}.step-item{width:auto;}.step-indicators{gap:4px;}.step-indicators::before{left:10px;right:10px;}.step-progress-fill{left:10px;}.site-footer{font-size:10px;padding:6px 8px;}}
</style>
</head>
<body>

<div class="top-bar">
    <div class="container d-flex align-items-center justify-content-between">
        <span class="brand">WebCraft Studio</span>
        <div class="d-flex align-items-center gap-3">
            <span id="autoSaveIndicator" class="badge bg-success d-none" style="font-size:10px;padding:4px 8px;">
                <i class="bi bi-check-circle"></i> Auto-save ON
            </span>
            <button type="button" id="startAgainBtn" class="btn btn-sm btn-outline-light d-none" onclick="startAgain()" style="font-size:11px;padding:2px 10px;border-radius:4px;">
                <i class="bi bi-arrow-counterclockwise"></i> Start Again
            </button>
            <span class="text-muted small">Step <span id="stepCounter">1</span> of 10</span>
        </div>
    </div>
</div>

<div class="progress-container">
    <div class="container">
        <div class="progress-bar-wrapper">
            <div class="step-indicators" id="stepIndicators">
                <div class="step-progress-fill" id="stepProgressFill"></div>
                <div>
                    <div class="step-dot active" data-step="1">1</div>
                    <div class="step-label">Basics</div>
                </div>
                <div>
                    <div class="step-dot" data-step="2">2</div>
                    <div class="step-label">Business</div>
                </div>
                <div>
                    <div class="step-dot" data-step="3">3</div>
                    <div class="step-label">Design</div>
                </div>
                <div>
                    <div class="step-dot" data-step="4">4</div>
                    <div class="step-label">Pages</div>
                </div>
                <div>
                    <div class="step-dot" data-step="5">5</div>
                    <div class="step-label">Features</div>
                </div>
                <div>
                    <div class="step-dot" data-step="6">6</div>
                    <div class="step-label">E-commerce</div>
                </div>
                <div>
                    <div class="step-dot" data-step="7">7</div>
                    <div class="step-label">Booking</div>
                </div>
                <div>
                    <div class="step-dot" data-step="8">8</div>
                    <div class="step-label">Integrations</div>
                </div>
                <div>
                    <div class="step-dot" data-step="9">9</div>
                    <div class="step-label">Budget</div>
                </div>
                <div>
                    <div class="step-dot" data-step="10">10</div>
                    <div class="step-label">Review</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <form id="requirementForm" action="<?= BASE_URL ?>/api/submit-requirement.php" method="POST" enctype="multipart/form-data">
                    <?= csrfField() ?>
                    <input type="hidden" name="current_step" id="currentStepInput" value="1">

                    <!-- STEP 1: Project Basics -->
                    <div class="form-step active" data-step="1">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-info-circle text-primary"></i> Project Basics</div>
                            <div class="step-subtitle">Tell us about your website project</div>

                            <div class="mb-4">
                                <label class="form-label">Website Type <span class="required">*</span></label>
                                <select name="website_type_id" id="website_type" class="form-select" required>
                                    <option value="">-- Select Website Type --</option>
                                    <?php foreach ($websiteTypes as $type): ?>
                                        <option value="<?= $type['id'] ?>"><?= sanitize($type['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="error-message" id="error_website_type">Please select a website type</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Project Purpose <span class="required">*</span></label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3" id="project_purpose_cards">
                                    <?php foreach ($projectPurposes as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="project_purpose[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="error-message" id="error_project_purpose">Please select at least one purpose</div>
                            </div>

                            <div class="mb-4 conditional-section" id="project_purpose_other_section">
                                <label class="form-label">Other Purpose (please specify)</label>
                                <input type="text" name="project_purpose_other" class="form-control" placeholder="Describe the purpose of your website">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Project Name <span class="required">*</span></label>
                                <input type="text" name="project_name" id="project_name" class="form-control" placeholder="e.g. My Awesome Website" required>
                                <div class="error-message" id="error_project_name">Please enter a project name</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Project Description <span class="required">*</span></label>
                                <textarea name="project_description" id="project_description" class="form-control" placeholder="Briefly describe what you want your website to achieve, target audience, and any specific ideas..." required></textarea>
                                <div class="error-message" id="error_project_description">Please describe your project</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Business Information -->
                    <div class="form-step" data-step="2">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-building text-primary"></i> Business Information</div>
                            <div class="step-subtitle">Tell us about your business and existing online presence</div>

                            <div class="mb-4">
                                <label class="form-label">Do you have an existing website? <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'existing_website')">
                                        <input type="radio" name="existing_website" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-check-circle text-success"></i></div>
                                        <div class="card-label">Yes</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'existing_website')">
                                        <input type="radio" name="existing_website" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'existing_website')">
                                        <input type="radio" name="existing_website" value="redesign">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-arrow-repeat text-warning"></i></div>
                                        <div class="card-label">Need Redesign</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_existing_website">Please select an option</div>
                            </div>

                            <div class="mb-4 conditional-section" id="existing_website_url_section">
                                <label class="form-label">Current Website URL</label>
                                <input type="url" name="existing_website_url" class="form-control" placeholder="https://your-current-website.com">
                            </div>

                            <div class="mb-4 conditional-section" id="existing_website_problems_section">
                                <label class="form-label">Problems with current website</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($existingWebsiteProblems as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="existing_website_problems[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <h6 class="fw-bold mb-3">Contact Information</h6>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="required">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Your full name" required>
                                    <div class="error-message" id="error_customer_name">Please enter your name</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="required">*</span></label>
                                    <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="you@example.com" required>
                                    <div class="error-message" id="error_customer_email">Please enter a valid email</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number <span class="required">*</span></label>
                                    <input type="tel" name="customer_phone" id="customer_phone" class="form-control" placeholder="+977 9XXXXXXXXX" required>
                                    <div class="error-message" id="error_customer_phone">Please enter your phone number</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="customer_company" class="form-control" placeholder="Company name (optional)">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="customer_city" class="form-control" placeholder="e.g. Kathmandu">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="customer_country" class="form-control" value="Nepal" placeholder="Country">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Design Preferences -->
                    <div class="form-step" data-step="3">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-palette text-primary"></i> Design Preferences</div>
                            <div class="step-subtitle">How should your website look and feel?</div>

                            <div class="mb-4">
                                <label class="form-label">Do you have design files available? <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'design_available')">
                                        <input type="radio" name="design_available" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-check-circle text-success"></i></div>
                                        <div class="card-label">Yes, I have designs</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'design_available')">
                                        <input type="radio" name="design_available" value="partial">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-half text-warning"></i></div>
                                        <div class="card-label">Partial / Some references</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'design_available')">
                                        <input type="radio" name="design_available" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-lightbulb text-primary"></i></div>
                                        <div class="card-label">No, need design</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_design_available">Please select an option</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Design Style <span class="required">*</span></label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-4">
                                    <?php foreach ($designStyles as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="design_style[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="error-message" id="error_design_style">Please select at least one style</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Reference Websites</label>
                                <small class="text-muted d-block mb-2">Paste URLs of websites you like (one per line)</small>
                                <textarea name="reference_urls" class="form-control" placeholder="https://example1.com&#10;https://example2.com"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Design Notes</label>
                                <input type="text" name="reference_notes" class="form-control" placeholder="Any specific color preferences, style notes, etc.">
                            </div>

                            <hr class="section-divider">

                            <h6 class="fw-bold mb-3">Brand Assets</h6>

                            <div class="row g-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Do you have a logo? <span class="required">*</span></label>
                                    <div class="radio-cards cols-2">
                                        <label class="card-option" onclick="selectRadio(this, 'has_logo')">
                                            <input type="radio" name="has_logo" value="yes" required>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">Yes</div>
                                        </label>
                                        <label class="card-option" onclick="selectRadio(this, 'has_logo')">
                                            <input type="radio" name="has_logo" value="no">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">No</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Brand Colors? <span class="required">*</span></label>
                                    <div class="radio-cards cols-2">
                                        <label class="card-option" onclick="selectRadio(this, 'has_brand_colors')">
                                            <input type="radio" name="has_brand_colors" value="yes" required>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">Yes</div>
                                        </label>
                                        <label class="card-option" onclick="selectRadio(this, 'has_brand_colors')">
                                            <input type="radio" name="has_brand_colors" value="no">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">No</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Brand Guidelines? <span class="required">*</span></label>
                                    <div class="radio-cards cols-2">
                                        <label class="card-option" onclick="selectRadio(this, 'has_brand_guidelines')">
                                            <input type="radio" name="has_brand_guidelines" value="yes" required>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">Yes</div>
                                        </label>
                                        <label class="card-option" onclick="selectRadio(this, 'has_brand_guidelines')">
                                            <input type="radio" name="has_brand_guidelines" value="no">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label">No</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Upload Brand Files</label>
                                <div class="file-upload-area" onclick="document.getElementById('brandFiles').click()">
                                    <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                    <p>Click to upload or drag and drop</p>
                                    <small class="small">PNG, JPG, PDF, AI, EPS (Max 10MB each)</small>
                                        <input type="file" name="files[]" id="brandFiles" multiple accept=".png,.jpg,.jpeg,.gif,.pdf,.ai,.eps">
                                </div>
                                <div id="brandFilesList" class="mt-2"></div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Pages -->
                    <div class="form-step" data-step="4">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-file-earmark text-primary"></i> Website Pages</div>
                            <div class="step-subtitle">Select the pages you need for your website</div>

                            <div class="mb-4">
                                <label class="form-label">Select Pages <span class="required">*</span></label>
                                <small class="text-muted d-block mb-2">Choose all pages you need</small>
                                <div class="page-checkbox-grid" id="pageOptions">
                                    <?php foreach ($pageOptions as $page): ?>
                                        <label class="page-checkbox-item" onclick="togglePageItem(this)">
                                            <input type="checkbox" name="selected_pages[]" value="<?= strtolower(str_replace(' ', '-', $page)) ?>">
                                            <span><?= $page ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="error-message" id="error_selected_pages">Please select at least one page</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Additional Custom Pages</label>
                                <small class="text-muted d-block mb-2">Enter any other pages not listed above (one per line)</small>
                                <textarea name="custom_pages" class="form-control" placeholder="e.g. Gallery&#10;Careers&#10;Our Process"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Estimated Total Pages</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                                    <input type="number" name="page_count" id="page_count" class="form-control" min="1" max="100" value="1" placeholder="Number of pages">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 5: Features -->
                    <div class="form-step" data-step="5">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-puzzle text-primary"></i> Features & Functionality</div>
                            <div class="step-subtitle">What features do you need?</div>

                            <div class="mb-4">
                                <label class="form-label">Content Management System (CMS) <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_cms'); toggleCmsSection()">
                                        <input type="radio" name="needs_cms" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-check-circle text-success"></i></div>
                                        <div class="card-label">Yes, need CMS</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_cms'); toggleCmsSection()">
                                        <input type="radio" name="needs_cms" value="basic">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-pencil-square text-warning"></i></div>
                                        <div class="card-label">Basic Editing</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_cms'); toggleCmsSection()">
                                        <input type="radio" name="needs_cms" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No CMS needed</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_needs_cms">Please select an option</div>
                            </div>

                            <div class="mb-4 conditional-section" id="cms_items_section">
                                <label class="form-label">What do you want to manage via CMS?</label>
                                <small class="text-muted d-block mb-2">Select all content types</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($cmsItems as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="cms_manage_items[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <div class="mb-4">
                                <label class="form-label">User Accounts System <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_user_accounts'); toggleUserSection()">
                                        <input type="radio" name="needs_user_accounts" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-people text-success"></i></div>
                                        <div class="card-label">Yes, need user accounts</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_user_accounts'); toggleUserSection()">
                                        <input type="radio" name="needs_user_accounts" value="admin-only">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-person-badge text-warning"></i></div>
                                        <div class="card-label">Admin accounts only</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_user_accounts'); toggleUserSection()">
                                        <input type="radio" name="needs_user_accounts" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No user accounts</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_needs_user_accounts">Please select an option</div>
                            </div>

                            <div class="mb-4 conditional-section" id="user_features_section">
                                <label class="form-label">User Account Features</label>
                                <small class="text-muted d-block mb-2">Select all features you need</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($userFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="user_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="user_roles_section">
                                <label class="form-label">User Roles Needed</label>
                                <small class="text-muted d-block mb-2">What types of users will have accounts?</small>
                                <div class="checkbox-cards cols-4">
                                    <?php foreach ($userRoles as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="user_roles[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 6: E-commerce -->
                    <div class="form-step" data-step="6">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-cart text-primary"></i> E-commerce</div>
                            <div class="step-subtitle">Do you need online selling capabilities?</div>

                            <div class="mb-4">
                                <label class="form-label">Do you need an online store? <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_ecommerce'); toggleEcommerceSection()">
                                        <input type="radio" name="needs_ecommerce" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-shop text-success"></i></div>
                                        <div class="card-label">Yes, full store</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_ecommerce'); toggleEcommerceSection()">
                                        <input type="radio" name="needs_ecommerce" value="catalog">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-collection text-warning"></i></div>
                                        <div class="card-label">Catalog only (no checkout)</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_ecommerce'); toggleEcommerceSection()">
                                        <input type="radio" name="needs_ecommerce" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No e-commerce</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_needs_ecommerce">Please select an option</div>
                            </div>

                            <div class="mb-4 conditional-section" id="ecommerce_features_section">
                                <label class="form-label">E-commerce Features</label>
                                <small class="text-muted d-block mb-2">Select all features you need</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($ecommerceFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="ecommerce_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="product_quantity_section">
                                <label class="form-label">Estimated Number of Products</label>
                                <select name="product_quantity" class="form-select">
                                    <option value="1-50">1 - 50 Products</option>
                                    <option value="51-200">51 - 200 Products</option>
                                    <option value="201-500">201 - 500 Products</option>
                                    <option value="501-1000">501 - 1000 Products</option>
                                    <option value="1000+">1000+ Products</option>
                                </select>
                            </div>

                            <div class="mb-4 conditional-section" id="payment_section">
                                <label class="form-label">Payment Gateway <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_payment'); togglePaymentMethods()">
                                        <input type="radio" name="needs_payment" value="yes">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Yes, need payments</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_payment'); togglePaymentMethods()">
                                        <input type="radio" name="needs_payment" value="bank-only">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Bank Transfer only</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_payment'); togglePaymentMethods()">
                                        <input type="radio" name="needs_payment" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">No payment needed</div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="payment_methods_section">
                                <label class="form-label">Payment Methods</label>
                                <small class="text-muted d-block mb-2">Select all payment methods you want</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($paymentMethods as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="payment_methods[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 7: Booking System -->
                    <div class="form-step" data-step="7">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-calendar-check text-primary"></i> Booking System</div>
                            <div class="step-subtitle">Do you need appointment or booking functionality?</div>

                            <div class="mb-4">
                                <label class="form-label">Do you need a booking/appointment system? <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_booking'); toggleBookingSection()">
                                        <input type="radio" name="needs_booking" value="yes" required>
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-calendar-check text-success"></i></div>
                                        <div class="card-label">Yes, need booking</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_booking'); toggleBookingSection()">
                                        <input type="radio" name="needs_booking" value="simple">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-calendar text-warning"></i></div>
                                        <div class="card-label">Simple calendar only</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_booking'); toggleBookingSection()">
                                        <input type="radio" name="needs_booking" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No booking needed</div>
                                    </label>
                                </div>
                                <div class="error-message" id="error_needs_booking">Please select an option</div>
                            </div>

                            <div class="mb-4 conditional-section" id="booking_types_section">
                                <label class="form-label">What type of bookings?</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($bookingTypes as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="booking_types[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="booking_features_section">
                                <label class="form-label">Booking Features</label>
                                <small class="text-muted d-block mb-2">Select all features you need</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($bookingFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="booking_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 8: Integrations -->
                    <div class="form-step" data-step="8">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-plug text-primary"></i> Integrations & Tools</div>
                            <div class="step-subtitle">What additional tools and services do you need?</div>

                            <div class="mb-4">
                                <label class="form-label">Communication Features</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($communicationFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="communication_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <div class="mb-4">
                                <label class="form-label">Search Functionality</label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'search_type')">
                                        <input type="radio" name="search_type" value="none">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">No search</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'search_type')">
                                        <input type="radio" name="search_type" value="basic">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Basic search</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'search_type')">
                                        <input type="radio" name="search_type" value="advanced">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Advanced search & filters</div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Google Maps Integration</label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_maps'); toggleMapSection()">
                                        <input type="radio" name="needs_maps" value="yes">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Yes, need maps</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_maps'); toggleMapSection()">
                                        <input type="radio" name="needs_maps" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">No maps needed</div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="map_features_section">
                                <label class="form-label">Map Features</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($mapFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="map_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Multi-language Support</label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'language_count'); toggleLanguageSection()">
                                        <input type="radio" name="language_count" value="1">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">Single language</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'language_count'); toggleLanguageSection()">
                                        <input type="radio" name="language_count" value="2-3">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">2-3 languages</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'language_count'); toggleLanguageSection()">
                                        <input type="radio" name="language_count" value="4+">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-label">4+ languages</div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="languages_section">
                                <label class="form-label">Which languages?</label>
                                <input type="text" name="languages" class="form-control" placeholder="e.g. English, Nepali, Hindi">
                            </div>

                            <hr class="section-divider">

                            <div class="mb-4">
                                <label class="form-label">SEO Level</label>
                                <div class="radio-cards cols-4">
                                    <?php foreach ($seoLevels as $key => $label): ?>
                                        <label class="card-option" onclick="selectRadio(this, 'seo_level')">
                                            <input type="radio" name="seo_level" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">SEO Features</label>
                                <small class="text-muted d-block mb-2">Select additional SEO features</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($seoFeaturesList as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="seo_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Analytics & Tracking</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($analyticsFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="analytics_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Security Features</label>
                                <small class="text-muted d-block mb-2">Select all that apply</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($securityFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="security_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 9: Budget & Timeline -->
                    <div class="form-step" data-step="9">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-cash-stack text-primary"></i> Budget & Timeline</div>
                            <div class="step-subtitle">Tell us about your budget and when you need it</div>

                            <div class="mb-4">
                                <label class="form-label">Project Timeline <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <?php foreach (TIMELINE_OPTIONS as $key => $label): ?>
                                        <label class="card-option" onclick="selectRadio(this, 'timeline')">
                                            <input type="radio" name="timeline" value="<?= $key ?>" required>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="error-message" id="error_timeline">Please select a timeline</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Preferred Launch Date</label>
                                <input type="date" name="launch_date" class="form-control" min="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Budget Range <span class="required">*</span></label>
                                <div class="radio-cards cols-3">
                                    <?php foreach (BUDGET_OPTIONS as $key => $label): ?>
                                        <label class="card-option" onclick="selectRadio(this, 'budget_range')">
                                            <input type="radio" name="budget_range" value="<?= $key ?>" required>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <div class="error-message" id="error_budget_range">Please select a budget range</div>
                            </div>

                            <hr class="section-divider">

                            <div class="mb-4">
                                <label class="form-label">Ongoing Maintenance</label>
                                <div class="radio-cards cols-3">
                                    <label class="card-option" onclick="selectRadio(this, 'needs_maintenance'); toggleMaintenanceSection()">
                                        <input type="radio" name="needs_maintenance" value="yes">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-tools text-success"></i></div>
                                        <div class="card-label">Yes, need maintenance</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_maintenance'); toggleMaintenanceSection()">
                                        <input type="radio" name="needs_maintenance" value="maybe">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-question-circle text-warning"></i></div>
                                        <div class="card-label">Maybe later</div>
                                    </label>
                                    <label class="card-option" onclick="selectRadio(this, 'needs_maintenance'); toggleMaintenanceSection()">
                                        <input type="radio" name="needs_maintenance" value="no">
                                        <div class="card-check"><i class="bi bi-check"></i></div>
                                        <div class="card-icon"><i class="bi bi-x-circle text-danger"></i></div>
                                        <div class="card-label">No maintenance</div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4 conditional-section" id="maintenance_features_section">
                                <label class="form-label">Maintenance Features</label>
                                <small class="text-muted d-block mb-2">Select what you need</small>
                                <div class="checkbox-cards cols-3">
                                    <?php foreach ($maintenanceFeatures as $key => $label): ?>
                                        <label class="card-option" onclick="toggleCardOption(this)">
                                            <input type="checkbox" name="maintenance_features[]" value="<?= $key ?>">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-label"><?= $label ?></div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Special Requirements</label>
                                <textarea name="special_requirements" class="form-control" placeholder="Any specific requirements, deadlines, or notes..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Specific Workflow Requirements</label>
                                <textarea name="specific_workflow" class="form-control" placeholder="Describe any specific business processes or workflows the website should support..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-nav btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 10: Review & Submit -->
                    <div class="form-step" data-step="10">
                        <div class="form-card">
                            <div class="step-title"><i class="bi bi-check-circle text-primary"></i> Review & Submit</div>
                            <div class="step-subtitle">Review your selections and submit your requirements</div>

                            <div class="info-badge"><i class="bi bi-info-circle"></i> Please review all your selections below. You can go back to make changes.</div>

                            <div id="reviewContent">
                                <div class="review-section">
                                    <h6><i class="bi bi-info-circle"></i> Project Basics</h6>
                                    <div class="review-item"><span class="label">Website Type</span><span class="value" id="review_website_type">-</span></div>
                                    <div class="review-item"><span class="label">Project Purpose</span><span class="value" id="review_project_purpose">-</span></div>
                                    <div class="review-item"><span class="label">Project Name</span><span class="value" id="review_project_name">-</span></div>
                                    <div class="review-item"><span class="label">Description</span><span class="value" id="review_project_description">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-building"></i> Business Info</h6>
                                    <div class="review-item"><span class="label">Existing Website</span><span class="value" id="review_existing_website">-</span></div>
                                    <div class="review-item"><span class="label">Contact Name</span><span class="value" id="review_customer_name">-</span></div>
                                    <div class="review-item"><span class="label">Email</span><span class="value" id="review_customer_email">-</span></div>
                                    <div class="review-item"><span class="label">Phone</span><span class="value" id="review_customer_phone">-</span></div>
                                    <div class="review-item"><span class="label">City</span><span class="value" id="review_customer_city">-</span></div>
                                    <div class="review-item"><span class="label">Country</span><span class="value" id="review_customer_country">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-palette"></i> Design</h6>
                                    <div class="review-item"><span class="label">Design Available</span><span class="value" id="review_design_available">-</span></div>
                                    <div class="review-item"><span class="label">Design Style</span><span class="value" id="review_design_style">-</span></div>
                                    <div class="review-item"><span class="label">Logo</span><span class="value" id="review_has_logo">-</span></div>
                                    <div class="review-item"><span class="label">Brand Colors</span><span class="value" id="review_has_brand_colors">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-file-earmark"></i> Pages</h6>
                                    <div class="review-item"><span class="label">Selected Pages</span><span class="value" id="review_selected_pages">-</span></div>
                                    <div class="review-item"><span class="label">Page Count</span><span class="value" id="review_page_count">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-puzzle"></i> Features</h6>
                                    <div class="review-item"><span class="label">CMS</span><span class="value" id="review_needs_cms">-</span></div>
                                    <div class="review-item"><span class="label">User Accounts</span><span class="value" id="review_needs_user_accounts">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-cart"></i> E-commerce</h6>
                                    <div class="review-item"><span class="label">Online Store</span><span class="value" id="review_needs_ecommerce">-</span></div>
                                    <div class="review-item"><span class="label">Payment</span><span class="value" id="review_needs_payment">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-calendar-check"></i> Booking</h6>
                                    <div class="review-item"><span class="label">Booking System</span><span class="value" id="review_needs_booking">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-plug"></i> Integrations</h6>
                                    <div class="review-item"><span class="label">Search</span><span class="value" id="review_search_type">-</span></div>
                                    <div class="review-item"><span class="label">Maps</span><span class="value" id="review_needs_maps">-</span></div>
                                    <div class="review-item"><span class="label">Languages</span><span class="value" id="review_language_count">-</span></div>
                                    <div class="review-item"><span class="label">SEO Level</span><span class="value" id="review_seo_level">-</span></div>
                                </div>

                                <div class="review-section">
                                    <h6><i class="bi bi-cash-stack"></i> Budget & Timeline</h6>
                                    <div class="review-item"><span class="label">Timeline</span><span class="value" id="review_timeline">-</span></div>
                                    <div class="review-item"><span class="label">Budget</span><span class="value" id="review_budget_range">-</span></div>
                                    <div class="review-item"><span class="label">Maintenance</span><span class="value" id="review_needs_maintenance">-</span></div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <h6 class="fw-bold mb-3">Confirm Contact Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Your Name <span class="required">*</span></label>
                                    <input type="text" name="confirm_name" id="confirm_name" class="form-control" placeholder="Full name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="confirm_email" id="confirm_email" class="form-control" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone <span class="required">*</span></label>
                                    <input type="tel" name="confirm_phone" id="confirm_phone" class="form-control" placeholder="+977 9XXXXXXXXX" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company</label>
                                    <input type="text" name="confirm_company" id="confirm_company" class="form-control" placeholder="Company name">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Preferred Contact Method</label>
                                    <div class="radio-cards cols-3">
                                        <label class="card-option" onclick="selectRadio(this, 'preferred_contact')">
                                            <input type="radio" name="preferred_contact" value="email" checked>
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-icon"><i class="bi bi-envelope"></i></div>
                                            <div class="card-label">Email</div>
                                        </label>
                                        <label class="card-option" onclick="selectRadio(this, 'preferred_contact')">
                                            <input type="radio" name="preferred_contact" value="phone">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-icon"><i class="bi bi-telephone"></i></div>
                                            <div class="card-label">Phone</div>
                                        </label>
                                        <label class="card-option" onclick="selectRadio(this, 'preferred_contact')">
                                            <input type="radio" name="preferred_contact" value="whatsapp">
                                            <div class="card-check"><i class="bi bi-check"></i></div>
                                            <div class="card-icon"><i class="bi bi-chat-dots"></i></div>
                                            <div class="card-label">WhatsApp</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-check-label d-flex align-items-start gap-2" style="cursor:pointer;">
                                    <input type="checkbox" name="confirmation" id="confirmation" class="form-check-input mt-1" required style="width:20px;height:20px;accent-color:var(--primary);">
                                    <span>I confirm that the information provided is accurate and I agree to the <a href="<?= BASE_URL ?>/terms" target="_blank">Terms of Service</a> and <a href="<?= BASE_URL ?>/privacy" target="_blank">Privacy Policy</a>. I understand this is a preliminary estimate and the final quotation may vary based on detailed requirements analysis. <span class="required">*</span></span>
                                </label>
                                <div class="error-message" id="error_confirmation">You must agree to proceed</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-nav btn-prev" onclick="prevStep()"><i class="bi bi-arrow-left"></i> Back</button>
                                <button type="submit" class="btn btn-nav btn-submit" id="submitBtn">
                                    <i class="bi bi-send"></i> Submit Requirements
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Price Sidebar -->
            <div class="col-lg-4">
                <div class="price-sidebar" id="priceSidebar">
                    <div class="price-card">
                        <div class="price-card-header" style="position:relative;">
                            <h5>Estimated Cost</h5>
                            <button class="price-close-btn d-lg-none" onclick="toggleMobilePrice()" title="Close">&times;</button>
                        </div>
                        <div class="price-card-body">
                            <div id="priceBreakdown">
                                <p class="text-muted small" style="margin:0;">Select options to see pricing</p>
                            </div>
                            <div class="price-total">
                                <span class="label">Total</span>
                                <span class="value" id="price_total">NPR 0</span>
                            </div>
                            <p class="price-note">* Estimate only. Final price after review.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="price-sidebar-overlay" id="priceOverlay" onclick="toggleMobilePrice()"></div>

<button class="floating-price-toggle" onclick="toggleMobilePrice()" id="floatingPriceBtn">
    <span class="price-icon"><i class="bi bi-calculator"></i></span>
    <span class="price-text" id="floatingPriceText">NPR 0</span>
</button>

<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> | Built by <strong>Abhijeet Prajapati</strong></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentStep = 1;
const totalSteps = 10;
const stepErrors = {};

const stepValidationRules = {
    1: ['website_type', 'project_purpose', 'project_name', 'project_description'],
    2: ['existing_website', 'customer_name', 'customer_email', 'customer_phone'],
    3: ['design_available', 'design_style', 'has_logo', 'has_brand_colors', 'has_brand_guidelines'],
    4: ['selected_pages'],
    5: ['needs_cms', 'needs_user_accounts'],
    6: ['needs_ecommerce'],
    7: ['needs_booking'],
    8: [],
    9: ['timeline', 'budget_range'],
    10: ['confirmation']
};

const websiteTypeNames = {
    <?php foreach ($websiteTypes as $type): ?>
    '<?= $type['id'] ?>': '<?= addslashes($type['name']) ?>',
    <?php endforeach; ?>
};

function toggleCardOption(el) {
    const input = el.querySelector('input[type="checkbox"]');
    if (input.checked) {
        input.checked = false;
        el.classList.remove('selected');
    } else {
        input.checked = true;
        el.classList.add('selected');
    }
    calculatePrice();
    updateReview();
}

function selectRadio(el, name) {
    const container = el.closest('.radio-cards') || el.parentElement;
    container.querySelectorAll('.card-option').forEach(card => {
        card.classList.remove('selected');
    });
    el.classList.add('selected');
    const input = el.querySelector('input[type="radio"]');
    if (input) input.checked = true;
    calculatePrice();
    updateReview();
    handleConditionalSections();
}

function togglePageItem(el) {
    const input = el.querySelector('input[type="checkbox"]');
    if (input.checked) {
        input.checked = false;
        el.classList.remove('selected');
    } else {
        input.checked = true;
        el.classList.add('selected');
    }
    updatePageCount();
    calculatePrice();
    updateReview();
}

function updatePageCount() {
    const selected = document.querySelectorAll('input[name="selected_pages[]"]:checked').length;
    const customPages = document.querySelector('textarea[name="custom_pages"]').value.trim();
    const customCount = customPages ? customPages.split('\n').filter(l => l.trim()).length : 0;
    document.getElementById('page_count').value = selected + customCount;
    calculatePrice();
}

document.querySelector('textarea[name="custom_pages"]').addEventListener('input', updatePageCount);

function handleConditionalSections() {
    const existingWebsite = document.querySelector('input[name="existing_website"]:checked');
    if (existingWebsite) {
        const val = existingWebsite.value;
        toggleSection('existing_website_url_section', val === 'yes' || val === 'redesign');
        toggleSection('existing_website_problems_section', val === 'yes' || val === 'redesign');
    }

    const needsCms = document.querySelector('input[name="needs_cms"]:checked');
    if (needsCms) {
        toggleSection('cms_items_section', needsCms.value === 'yes' || needsCms.value === 'basic');
    }

    const needsUserAccounts = document.querySelector('input[name="needs_user_accounts"]:checked');
    if (needsUserAccounts) {
        toggleSection('user_features_section', needsUserAccounts.value === 'yes');
        toggleSection('user_roles_section', needsUserAccounts.value === 'yes');
    }

    const needsEcommerce = document.querySelector('input[name="needs_ecommerce"]:checked');
    if (needsEcommerce) {
        toggleSection('ecommerce_features_section', needsEcommerce.value === 'yes');
        toggleSection('product_quantity_section', needsEcommerce.value === 'yes');
        toggleSection('payment_section', needsEcommerce.value === 'yes');
    }

    const needsPayment = document.querySelector('input[name="needs_payment"]:checked');
    if (needsPayment) {
        toggleSection('payment_methods_section', needsPayment.value === 'yes');
    }

    const needsBooking = document.querySelector('input[name="needs_booking"]:checked');
    if (needsBooking) {
        toggleSection('booking_types_section', needsBooking.value === 'yes' || needsBooking.value === 'simple');
        toggleSection('booking_features_section', needsBooking.value === 'yes');
    }

    const needsMaps = document.querySelector('input[name="needs_maps"]:checked');
    if (needsMaps) {
        toggleSection('map_features_section', needsMaps.value === 'yes');
    }

    const languageCount = document.querySelector('input[name="language_count"]:checked');
    if (languageCount) {
        toggleSection('languages_section', languageCount.value !== '1');
    }

    const needsMaintenance = document.querySelector('input[name="needs_maintenance"]:checked');
    if (needsMaintenance) {
        toggleSection('maintenance_features_section', needsMaintenance.value === 'yes');
    }

    const projectPurpose = document.querySelectorAll('input[name="project_purpose[]"]');
    const purposes = Array.from(projectPurpose).filter(p => p.checked).map(p => p.value);
    toggleSection('project_purpose_other_section', purposes.includes('other'));
}

function toggleSection(id, show) {
    const section = document.getElementById(id);
    if (section) {
        section.classList.toggle('visible', show);
    }
}

function toggleCmsSection() {
    setTimeout(handleConditionalSections, 10);
}
function toggleUserSection() {
    setTimeout(handleConditionalSections, 10);
}
function toggleEcommerceSection() {
    setTimeout(handleConditionalSections, 10);
}
function togglePaymentMethods() {
    setTimeout(handleConditionalSections, 10);
}
function toggleBookingSection() {
    setTimeout(handleConditionalSections, 10);
}
function toggleMapSection() {
    setTimeout(handleConditionalSections, 10);
}
function toggleLanguageSection() {
    setTimeout(handleConditionalSections, 10);
}
function toggleMaintenanceSection() {
    setTimeout(handleConditionalSections, 10);
}

let priceTimer = null;
function calculatePrice() {
    clearTimeout(priceTimer);
    priceTimer = setTimeout(() => {
        const form = document.getElementById('requirementForm');
        const data = {};
        new FormData(form).forEach((v, k) => {
            if (k.endsWith('[]')) {
                if (!data[k]) data[k] = [];
                data[k].push(v);
            } else {
                data[k] = v;
            }
        });
        const checked = {};
        form.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked').forEach(el => {
            if (el.name) {
                if (el.name.endsWith('[]')) {
                    if (!checked[el.name]) checked[el.name] = [];
                    checked[el.name].push(el.value);
                } else {
                    checked[el.name] = el.value;
                }
            }
        });
        Object.assign(data, checked);

        fetch('<?= BASE_URL ?>/api/calculate-price.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;
                const bd = data.breakdown;
                const items = [];
                if (bd.base > 0) items.push(['Base Website', bd.base]);
                if (bd.pages > 0) items.push(['Additional Pages', bd.pages]);
                if (bd.features > 0) items.push(['Features', bd.features]);
                if (bd.seo > 0) items.push(['SEO', bd.seo]);
                if (bd.languages > 0) items.push(['Languages', bd.languages]);
                if (bd.products > 0) items.push(['Products', bd.products]);
                if (bd.other > 0) items.push(['Other', bd.other]);
                
                const container = document.getElementById('priceBreakdown');
                if (items.length === 0) {
                    container.innerHTML = '<p class="text-muted small" style="margin:0;">Select options to see pricing</p>';
                } else {
                    container.innerHTML = items.map(([label, amt]) =>
                        '<div class="price-item"><span>' + label + '</span><span>NPR ' + Number(amt).toLocaleString() + '</span></div>'
                    ).join('');
                }
                document.getElementById('price_total').textContent = 'NPR ' + Number(data.total).toLocaleString();
                const floatText = document.getElementById('floatingPriceText');
                if (floatText) floatText.textContent = 'NPR ' + Number(data.total).toLocaleString();
                const counter = document.getElementById('stepCounter');
                if (counter) counter.textContent = currentStep;
            })
            .catch(() => {});
    }, 300);
}

function updateReview() {
    const getTypeName = (name) => {
        const el = document.querySelector(`select[name="${name}"]`);
        if (el && el.value) {
            const opt = el.options[el.selectedIndex];
            return opt ? opt.text : '-';
        }
        return '-';
    };
    const getRadio = (name) => {
        const el = document.querySelector(`input[name="${name}"]:checked`);
        if (el) {
            const label = el.closest('.card-option')?.querySelector('.card-label');
            return label ? label.textContent.trim() : el.value;
        }
        return '-';
    };
    const getCheckedValues = (name) => {
        const els = document.querySelectorAll(`input[name="${name}[]"]:checked`);
        if (els.length === 0) return '-';
        return Array.from(els).map(el => {
            const label = el.closest('.card-option')?.querySelector('.card-label');
            return label ? label.textContent.trim() : el.value;
        }).join(', ');
    };
    const getText = (name) => {
        const el = document.querySelector(`[name="${name}"]`);
        return el && el.value ? el.value.substring(0, 100) : '-';
    };

    document.getElementById('review_website_type').textContent = getTypeName('website_type');
    document.getElementById('review_project_purpose').textContent = getCheckedValues('project_purpose');
    document.getElementById('review_project_name').textContent = getText('project_name');
    document.getElementById('review_project_description').textContent = getText('project_description');
    document.getElementById('review_existing_website').textContent = getRadio('existing_website');
    document.getElementById('review_customer_name').textContent = getText('customer_name');
    document.getElementById('review_customer_email').textContent = getText('customer_email');
    document.getElementById('review_customer_phone').textContent = getText('customer_phone');
    document.getElementById('review_customer_city').textContent = getText('customer_city');
    document.getElementById('review_customer_country').textContent = getText('customer_country');
    document.getElementById('review_design_available').textContent = getRadio('design_available');
    document.getElementById('review_design_style').textContent = getCheckedValues('design_style');
    document.getElementById('review_has_logo').textContent = getRadio('has_logo');
    document.getElementById('review_has_brand_colors').textContent = getRadio('has_brand_colors');
    document.getElementById('review_selected_pages').textContent = getCheckedValues('selected_pages');
    document.getElementById('review_page_count').textContent = document.getElementById('page_count')?.value || '-';
    document.getElementById('review_needs_cms').textContent = getRadio('needs_cms');
    document.getElementById('review_needs_user_accounts').textContent = getRadio('needs_user_accounts');
    document.getElementById('review_needs_ecommerce').textContent = getRadio('needs_ecommerce');
    document.getElementById('review_needs_payment').textContent = getRadio('needs_payment');
    document.getElementById('review_needs_booking').textContent = getRadio('needs_booking');
    document.getElementById('review_search_type').textContent = getRadio('search_type');
    document.getElementById('review_needs_maps').textContent = getRadio('needs_maps');
    document.getElementById('review_language_count').textContent = getRadio('language_count');
    document.getElementById('review_seo_level').textContent = getRadio('seo_level');
    document.getElementById('review_timeline').textContent = getRadio('timeline');
    document.getElementById('review_budget_range').textContent = getRadio('budget_range');
    document.getElementById('review_needs_maintenance').textContent = getRadio('needs_maintenance');
}

function validateStep(step) {
    const rules = stepValidationRules[step];
    let valid = true;

    document.querySelectorAll('.form-step[data-step="' + step + '"] .error-message').forEach(el => {
        el.classList.remove('visible');
    });
    document.querySelectorAll('.form-step[data-step="' + step + '"] .step-validation-error').forEach(el => {
        el.classList.remove('step-validation-error');
    });

    for (const field of rules) {
        if (field === 'website_type') {
            const val = document.getElementById('website_type')?.value;
            if (!val) {
                showError('error_website_type');
                document.getElementById('website_type').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'project_purpose') {
            const checked = document.querySelectorAll('input[name="project_purpose[]"]:checked');
            if (checked.length === 0) {
                showError('error_project_purpose');
                valid = false;
            }
        } else if (field === 'project_name') {
            const val = document.getElementById('project_name')?.value.trim();
            if (!val) {
                showError('error_project_name');
                document.getElementById('project_name').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'project_description') {
            const val = document.getElementById('project_description')?.value.trim();
            if (!val) {
                showError('error_project_description');
                document.getElementById('project_description').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'existing_website') {
            if (!document.querySelector('input[name="existing_website"]:checked')) {
                showError('error_existing_website');
                valid = false;
            }
        } else if (field === 'customer_name') {
            const val = document.getElementById('customer_name')?.value.trim();
            if (!val) {
                showError('error_customer_name');
                document.getElementById('customer_name').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'customer_email') {
            const val = document.getElementById('customer_email')?.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!val || !emailRegex.test(val)) {
                showError('error_customer_email');
                document.getElementById('customer_email').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'customer_phone') {
            const val = document.getElementById('customer_phone')?.value.trim();
            if (!val) {
                showError('error_customer_phone');
                document.getElementById('customer_phone').classList.add('step-validation-error');
                valid = false;
            }
        } else if (field === 'design_available') {
            if (!document.querySelector('input[name="design_available"]:checked')) {
                showError('error_design_available');
                valid = false;
            }
        } else if (field === 'design_style') {
            const checked = document.querySelectorAll('input[name="design_style[]"]:checked');
            if (checked.length === 0) {
                showError('error_design_style');
                valid = false;
            }
        } else if (field === 'has_logo') {
            if (!document.querySelector('input[name="has_logo"]:checked')) {
                valid = false;
            }
        } else if (field === 'has_brand_colors') {
            if (!document.querySelector('input[name="has_brand_colors"]:checked')) {
                valid = false;
            }
        } else if (field === 'has_brand_guidelines') {
            if (!document.querySelector('input[name="has_brand_guidelines"]:checked')) {
                valid = false;
            }
        } else if (field === 'selected_pages') {
            const checked = document.querySelectorAll('input[name="selected_pages[]"]:checked');
            if (checked.length === 0) {
                showError('error_selected_pages');
                valid = false;
            }
        } else if (field === 'needs_cms') {
            if (!document.querySelector('input[name="needs_cms"]:checked')) {
                showError('error_needs_cms');
                valid = false;
            }
        } else if (field === 'needs_user_accounts') {
            if (!document.querySelector('input[name="needs_user_accounts"]:checked')) {
                showError('error_needs_user_accounts');
                valid = false;
            }
        } else if (field === 'needs_ecommerce') {
            if (!document.querySelector('input[name="needs_ecommerce"]:checked')) {
                showError('error_needs_ecommerce');
                valid = false;
            }
        } else if (field === 'needs_booking') {
            if (!document.querySelector('input[name="needs_booking"]:checked')) {
                showError('error_needs_booking');
                valid = false;
            }
        } else if (field === 'timeline') {
            if (!document.querySelector('input[name="timeline"]:checked')) {
                showError('error_timeline');
                valid = false;
            }
        } else if (field === 'budget_range') {
            if (!document.querySelector('input[name="budget_range"]:checked')) {
                showError('error_budget_range');
                valid = false;
            }
        } else if (field === 'confirmation') {
            const cb = document.getElementById('confirmation');
            if (!cb.checked) {
                showError('error_confirmation');
                valid = false;
            }
        }
    }

    return valid;
}

function showError(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('visible');
}

function nextStep() {
    if (!validateStep(currentStep)) return;

    if (currentStep === 2) {
        document.getElementById('confirm_name').value = document.getElementById('customer_name')?.value || '';
        document.getElementById('confirm_email').value = document.getElementById('customer_email')?.value || '';
        document.getElementById('confirm_phone').value = document.getElementById('customer_phone')?.value || '';
        document.getElementById('confirm_company').value = document.querySelector('[name="customer_company"]')?.value || '';
    }

    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
        calculatePrice();
        if (currentStep === 10) updateReview();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

function goToStep(step) {
    if (step >= 1 && step <= totalSteps) {
        currentStep = step;
        showStep(currentStep);
    }
}

function showStep(step) {
    document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
    const stepEl = document.querySelector('.form-step[data-step="' + step + '"]');
    if (stepEl) stepEl.classList.add('active');

    document.querySelectorAll('.step-dot').forEach(dot => {
        const s = parseInt(dot.getAttribute('data-step'));
        dot.classList.remove('active', 'completed');
        if (s === step) dot.classList.add('active');
        else if (s < step) dot.classList.add('completed');
    });

    const progress = ((step - 1) / (totalSteps - 1)) * 100;
    document.getElementById('stepProgressFill').style.width = progress + '%';

    document.getElementById('currentStepInput').value = step;

    window.scrollTo({ top: 0, behavior: 'smooth' });
    handleConditionalSections();
}

document.querySelectorAll('.step-dot').forEach(dot => {
    dot.addEventListener('click', function() {
        const step = parseInt(this.getAttribute('data-step'));
        goToStep(step);
    });
});

document.getElementById('brandFiles')?.addEventListener('change', function(e) {
    const list = document.getElementById('brandFilesList');
    list.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
        const item = document.createElement('div');
        item.className = 'd-flex align-items-center gap-2 p-2 bg-light rounded mb-1';
        item.innerHTML = '<i class="bi bi-file-earmark text-primary"></i><span class="small">' + file.name + '</span>';
        list.appendChild(item);
    });
});

document.getElementById('requirementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';

    const form = this;
    const data = {};
    new FormData(form).forEach((v, k) => {
        if (k.endsWith('[]')) {
            if (!data[k]) data[k] = [];
            data[k].push(v);
        } else {
            data[k] = v;
        }
    });
    const checked = {};
    form.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked').forEach(el => {
        if (el.name) {
            if (el.name.endsWith('[]')) {
                const key = el.name;
                if (!checked[key]) checked[key] = [];
                checked[key].push(el.value);
            } else {
                checked[el.name] = el.value;
            }
        }
    });
    Object.assign(data, checked);

    fetch(this.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            clearAutoSave();
            document.body.innerHTML = `
                <div class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:#f0f2f5;">
                    <div class="text-center p-5 bg-white rounded-4 shadow" style="max-width:500px;">
                        <div style="width:80px;height:80px;background:#d1faee;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                            <i class="bi bi-check-lg" style="font-size:2.5rem;color:#059669;"></i>
                        </div>
                        <h2 class="fw-bold mb-2">Requirements Submitted!</h2>
                        <p class="text-muted mb-1">Your requirement ID: <strong>${data.requirement_id || 'N/A'}</strong></p>
                        <p class="text-muted mb-4">Our team will review your requirements and send you a detailed quotation soon.</p>
                        <a href="<?= BASE_URL ?>/" class="btn btn-primary px-4 py-2">Back to Home</a>
                    </div>
                </div>
            `;
        } else {
            alert(data.message || 'Submission failed. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send"></i> Submit Requirements';
        }
    })
    .catch(err => {
        console.error('Submit error:', err);
        alert('An error occurred. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send"></i> Submit Requirements';
    });
});

function toggleMobilePrice() {
    const sidebar = document.getElementById('priceSidebar');
    const overlay = document.getElementById('priceOverlay');
    sidebar.classList.toggle('mobile-show');
    overlay.classList.toggle('active');
}

// === AUTO-SAVE TO LOCALSTORAGE ===
const AUTOSAVE_KEY = 'webcraft_draft';
let autoSaveTimer = null;

function showAutoSaveIndicator() {
    const badge = document.getElementById('autoSaveIndicator');
    const btn = document.getElementById('startAgainBtn');
    if (badge) badge.classList.remove('d-none');
    if (btn) btn.classList.remove('d-none');
}

function hideAutoSaveIndicator() {
    const badge = document.getElementById('autoSaveIndicator');
    const btn = document.getElementById('startAgainBtn');
    if (badge) badge.classList.add('d-none');
    if (btn) btn.classList.add('d-none');
}

function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        const form = document.getElementById('requirementForm');
        if (!form) return;
        const data = {};
        new FormData(form).forEach((v, k) => {
            if (k.endsWith('[]')) {
                const key = k.slice(0, -2);
                if (!data[key]) data[key] = [];
                data[key].push(v);
            } else {
                data[key] = v;
            }
        });
        const checked = {};
        form.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked').forEach(el => {
            if (el.name) {
                if (el.name.endsWith('[]')) {
                    const key = el.name.slice(0, -2);
                    if (!checked[key]) checked[key] = [];
                    checked[key].push(el.value);
                } else {
                    checked[el.name] = el.value;
                }
            }
        });
        data._checked = checked;
        data._step = currentStep;
        localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(data));
        showAutoSaveIndicator();
    }, 500);
}

function restoreAutoSave() {
    const saved = localStorage.getItem(AUTOSAVE_KEY);
    if (!saved) return false;
    try {
        const data = JSON.parse(saved);
        const form = document.getElementById('requirementForm');
        if (!form) return false;

        let hasData = false;

        Object.keys(data).forEach(key => {
            if (key.startsWith('_')) return;
            const val = data[key];
            if (Array.isArray(val)) {
                val.forEach(v => {
                    const el = form.querySelector(`[name="${key}[]"][value="${v}"]`);
                    if (el) { el.checked = true; hasData = true; }
                });
            } else {
                const el = form.querySelector(`[name="${key}"]`);
                if (el) {
                    if (el.type === 'radio') {
                        const radio = form.querySelector(`[name="${key}"][value="${val}"]`);
                        if (radio) { radio.checked = true; hasData = true; }
                    } else if (el.tagName === 'SELECT') {
                        el.value = val; hasData = true;
                    } else {
                        el.value = val; hasData = true;
                    }
                }
            }
        });

        if (data._checked) {
            Object.keys(data._checked).forEach(key => {
                const vals = data._checked[key];
                if (Array.isArray(vals)) {
                    vals.forEach(v => {
                        const el = form.querySelector(`[name="${key}[]"][value="${v}"]`);
                        if (el) { el.checked = true; hasData = true; }
                    });
                } else {
                    const el = form.querySelector(`[name="${key}"][value="${vals}"]`);
                    if (el) { el.checked = true; hasData = true; }
                }
            });
        }

        if (data._step) {
            currentStep = parseInt(data._step);
            showStep(currentStep);
        }

        handleConditionalSections();
        setTimeout(calculatePrice, 200);

        if (hasData) {
            showAutoSaveIndicator();
            return true;
        }
    } catch(e) {}
    return false;
}

function clearAutoSave() {
    localStorage.removeItem(AUTOSAVE_KEY);
    hideAutoSaveIndicator();
}

function startAgain() {
    if (!confirm('This will clear all your filled data and start fresh. Continue?')) return;
    clearAutoSave();
    const form = document.getElementById('requirementForm');
    if (form) form.reset();
    currentStep = 1;
    showStep(1);
    handleConditionalSections();
    document.getElementById('price_total').textContent = 'NPR 0';
    document.getElementById('floatingPriceText').textContent = 'NPR 0';
    const bd = document.getElementById('priceBreakdown');
    if (bd) bd.innerHTML = '<p class="text-muted small" style="margin:0;">Select options to see pricing</p>';
}

document.getElementById('requirementForm').addEventListener('input', autoSave);
document.getElementById('requirementForm').addEventListener('change', autoSave);
document.getElementById('requirementForm').addEventListener('click', function(e) {
    if (e.target.closest('.card-option') || e.target.closest('label') || e.target.closest('.form-check')) {
        autoSave();
    }
});

// === KEEP-ALIVE PING ===
setInterval(() => {
    fetch('<?= BASE_URL ?>/api/ping.php').catch(() => {});
}, 300000);

document.addEventListener('DOMContentLoaded', function() {
    handleConditionalSections();
    var restored = restoreAutoSave();
    if (!restored) hideAutoSaveIndicator();
});
</script>
</body>
</html>
