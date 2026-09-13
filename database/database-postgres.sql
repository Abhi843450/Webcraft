-- Website Requirement & Quotation Builder Database
-- PostgreSQL version for Render

-- =====================================================
-- ADMIN USERS & ROLES
-- =====================================================

CREATE TABLE IF NOT EXISTS admin_roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    permissions JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_roles (name, slug, permissions) VALUES
('Super Admin', 'super-admin', '{"all": true}'),
('Admin', 'admin', '{"requirements": true, "quotations": true, "pricing": true, "reports": true}'),
('Sales Manager', 'sales-manager', '{"requirements": true, "quotations": true}'),
('Support', 'support', '{"requirements": "view"}')
ON CONFLICT (slug) DO NOTHING;

CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    role_id INTEGER NOT NULL REFERENCES admin_roles(id),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    login_attempts INTEGER DEFAULT 0,
    locked_until TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (role_id, name, email, username, password) VALUES
(1, 'Super Admin', 'admin@demo.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON CONFLICT (email) DO NOTHING;

-- =====================================================
-- WEBSITE TYPES
-- =====================================================

CREATE TABLE IF NOT EXISTS website_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    base_price DECIMAL(12,2) DEFAULT 0.00,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO website_types (name, slug, description, base_price, display_order) VALUES
('Basic Website', 'basic', 'Informational website with static pages', 20000.00, 1),
('Professional Business Website', 'professional-business', 'Business pages, CMS, forms, SEO', 35000.00, 2),
('Business Website', 'business', 'Standard business website', 30000.00, 3),
('E-commerce Website', 'ecommerce', 'Products, cart, checkout, payments', 45000.00, 4),
('Booking Website', 'booking', 'Appointments, reservations, availability', 40000.00, 5),
('Membership Website', 'membership', 'Registration, login, profiles, member content', 40000.00, 6),
('Educational Website', 'educational', 'Courses, students, teachers, admissions', 45000.00, 7),
('News / Blog Website', 'news-blog', 'Articles, categories, authors, comments', 30000.00, 8),
('Directory Website', 'directory', 'Listings, search, filters', 35000.00, 9),
('Marketplace', 'marketplace', 'Multiple sellers, products/services', 60000.00, 10),
('Portal', 'portal', 'User-specific dashboards and services', 50000.00, 11),
('Custom Web Application', 'custom', 'Complex business logic and workflows', 70000.00, 12),
('Landing Page', 'landing', 'Single page for marketing', 8000.00, 13)
ON CONFLICT (slug) DO NOTHING;

-- =====================================================
-- FEATURE CATEGORIES & FEATURES
-- =====================================================

CREATE TABLE IF NOT EXISTS feature_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50),
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO feature_categories (name, slug, display_order) VALUES
('Core', 'core', 1),
('Design', 'design', 2),
('CMS', 'cms', 3),
('E-commerce', 'ecommerce', 4),
('Booking', 'booking', 5),
('User System', 'user-system', 6),
('Communication', 'communication', 7),
('SEO', 'seo', 8),
('Security', 'security', 9),
('Integration', 'integration', 10),
('Content', 'content', 11),
('Maps & Location', 'maps-location', 12),
('Analytics', 'analytics', 13),
('Hosting', 'hosting', 14),
('Maintenance', 'maintenance', 15)
ON CONFLICT (slug) DO NOTHING;

CREATE TABLE IF NOT EXISTS features (
    id SERIAL PRIMARY KEY,
    category_id INTEGER NOT NULL REFERENCES feature_categories(id),
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) DEFAULT 0.00,
    pricing_type VARCHAR(20) DEFAULT 'fixed' CHECK (pricing_type IN ('fixed','per_page','per_product','per_language','per_user_role','percentage','custom')),
    is_active BOOLEAN DEFAULT TRUE,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (slug)
);

INSERT INTO features (category_id, name, slug, price, pricing_type, display_order) VALUES
(1, 'Contact Form', 'contact-form', 2000.00, 'fixed', 1),
(1, 'Advanced Contact System', 'advanced-contact', 5000.00, 'fixed', 2),
(1, 'Newsletter Subscription', 'newsletter', 3000.00, 'fixed', 3),
(3, 'CMS / Admin Panel', 'cms', 12000.00, 'fixed', 1),
(3, 'Blog', 'blog', 7000.00, 'fixed', 2),
(3, 'News Management', 'news', 8000.00, 'fixed', 3),
(3, 'Events Management', 'events', 8000.00, 'fixed', 4),
(3, 'Gallery', 'gallery', 5000.00, 'fixed', 5),
(3, 'Testimonials', 'testimonials', 3000.00, 'fixed', 6),
(3, 'FAQ', 'faq', 3000.00, 'fixed', 7),
(3, 'Downloads', 'downloads', 4000.00, 'fixed', 8),
(4, 'E-commerce', 'ecommerce', 35000.00, 'fixed', 1),
(4, 'Product Categories', 'product-categories', 5000.00, 'fixed', 2),
(4, 'Product Variations', 'product-variations', 8000.00, 'fixed', 3),
(4, 'Inventory Management', 'inventory', 10000.00, 'fixed', 4),
(4, 'Shopping Cart', 'shopping-cart', 8000.00, 'fixed', 5),
(4, 'Wishlist', 'wishlist', 3000.00, 'fixed', 6),
(4, 'Checkout System', 'checkout', 10000.00, 'fixed', 7),
(4, 'Order Management', 'order-management', 12000.00, 'fixed', 8),
(4, 'Coupons & Discounts', 'coupons', 5000.00, 'fixed', 9),
(4, 'Product Reviews', 'product-reviews', 4000.00, 'fixed', 10),
(4, 'Shipping System', 'shipping', 8000.00, 'fixed', 11),
(4, 'Tax Management', 'tax-management', 5000.00, 'fixed', 12),
(4, 'Invoice Generation', 'invoice', 6000.00, 'fixed', 13),
(4, 'Refund System', 'refunds', 7000.00, 'fixed', 14),
(4, 'Order Tracking', 'order-tracking', 5000.00, 'fixed', 15),
(5, 'Booking System', 'booking-system', 20000.00, 'fixed', 1),
(5, 'Calendar View', 'calendar', 5000.00, 'fixed', 2),
(5, 'Availability Management', 'availability', 6000.00, 'fixed', 3),
(5, 'Time Slots', 'time-slots', 4000.00, 'fixed', 4),
(5, 'Booking Confirmation', 'booking-confirmation', 3000.00, 'fixed', 5),
(5, 'Cancellation & Rescheduling', 'cancellation', 4000.00, 'fixed', 6),
(5, 'Admin Approval', 'booking-approval', 5000.00, 'fixed', 7),
(6, 'User Registration', 'user-registration', 8000.00, 'fixed', 1),
(6, 'User Login System', 'user-login', 8000.00, 'fixed', 2),
(6, 'User Dashboard', 'user-dashboard', 15000.00, 'fixed', 3),
(6, 'Admin Dashboard', 'admin-dashboard', 15000.00, 'fixed', 4),
(6, 'Social Login', 'social-login', 8000.00, 'fixed', 5),
(6, 'Email Verification', 'email-verification', 3000.00, 'fixed', 6),
(6, 'Phone Verification', 'phone-verification', 5000.00, 'fixed', 7),
(6, 'Forgot Password', 'forgot-password', 3000.00, 'fixed', 8),
(6, 'Profile Management', 'profile-management', 5000.00, 'fixed', 9),
(6, 'Notifications System', 'notifications', 8000.00, 'fixed', 10),
(6, 'Saved Items / Favorites', 'favorites', 4000.00, 'fixed', 11),
(7, 'Live Chat', 'live-chat', 8000.00, 'fixed', 1),
(7, 'WhatsApp Integration', 'whatsapp', 3000.00, 'fixed', 2),
(7, 'SMS Integration', 'sms-integration', 7000.00, 'fixed', 3),
(7, 'Push Notifications', 'push-notifications', 8000.00, 'fixed', 4),
(7, 'Automated Emails', 'automated-emails', 5000.00, 'fixed', 5),
(8, 'Basic SEO', 'seo-basic', 5000.00, 'fixed', 1),
(8, 'Standard SEO', 'seo-standard', 10000.00, 'fixed', 2),
(8, 'Advanced SEO', 'seo-advanced', 15000.00, 'fixed', 3),
(9, 'SSL/HTTPS', 'ssl', 0.00, 'fixed', 1),
(9, 'Two-Factor Authentication', '2fa', 5000.00, 'fixed', 2),
(9, 'CAPTCHA', 'captcha', 2000.00, 'fixed', 3),
(9, 'Activity Logs', 'activity-logs', 4000.00, 'fixed', 4),
(9, 'Automatic Backups', 'auto-backups', 5000.00, 'fixed', 5),
(10, 'Google Maps', 'google-maps', 4000.00, 'fixed', 1),
(10, 'Social Media Integration', 'social-media', 5000.00, 'fixed', 2),
(10, 'Custom API Integration', 'api-integration', 10000.00, 'fixed', 3),
(10, 'CRM Integration', 'crm', 15000.00, 'fixed', 4),
(10, 'ERP Integration', 'erp', 20000.00, 'fixed', 5),
(11, 'Content Writing', 'content-writing', 8000.00, 'fixed', 1),
(11, 'Image Optimization', 'image-optimization', 3000.00, 'fixed', 2),
(11, 'Video Embedding', 'video-embedding', 3000.00, 'fixed', 3),
(11, 'Professional Copywriting', 'copywriting', 15000.00, 'fixed', 4),
(12, 'Google Maps Integration', 'gmaps', 4000.00, 'fixed', 1),
(12, 'OpenStreetMap', 'openstreetmap', 3000.00, 'fixed', 2),
(12, 'Location Search', 'location-search', 5000.00, 'fixed', 3),
(12, 'Nearby Search', 'nearby-search', 6000.00, 'fixed', 4),
(13, 'Google Analytics', 'google-analytics', 3000.00, 'fixed', 1),
(13, 'Google Search Console', 'search-console', 2000.00, 'fixed', 2),
(13, 'Meta Pixel', 'meta-pixel', 3000.00, 'fixed', 3),
(13, 'Conversion Tracking', 'conversion-tracking', 5000.00, 'fixed', 4),
(14, 'Domain Setup', 'domain-setup', 0.00, 'fixed', 1),
(14, 'Hosting Setup', 'hosting-setup', 0.00, 'fixed', 2),
(14, 'Business Email', 'business-email', 3000.00, 'fixed', 3),
(15, 'Monthly Maintenance', 'monthly-maintenance', 5000.00, 'fixed', 1),
(15, 'Security Updates', 'security-updates', 3000.00, 'fixed', 2),
(15, 'Content Updates', 'content-updates', 4000.00, 'fixed', 3),
(15, 'Technical Support', 'tech-support', 5000.00, 'fixed', 4);

-- =====================================================
-- PRICING RULES
-- =====================================================

CREATE TABLE IF NOT EXISTS pricing_rules (
    id SERIAL PRIMARY KEY,
    feature_id INTEGER REFERENCES features(id) ON DELETE SET NULL,
    rule_name VARCHAR(100) NOT NULL,
    rule_type VARCHAR(20) NOT NULL CHECK (rule_type IN ('base_price','per_page','per_product','per_language','per_user_role','additional_item','conditional','range','custom')),
    base_amount DECIMAL(12,2) DEFAULT 0.00,
    unit_amount DECIMAL(12,2) DEFAULT 0.00,
    included_units INTEGER DEFAULT 0,
    min_amount DECIMAL(12,2) DEFAULT 0.00,
    max_amount DECIMAL(12,2) DEFAULT 0.00,
    condition_key VARCHAR(100),
    condition_value VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO pricing_rules (feature_id, rule_name, rule_type, base_amount, unit_amount, included_units) VALUES
(NULL, 'Additional Pages', 'per_page', 0.00, 800.00, 5),
(NULL, 'Additional Languages', 'per_language', 0.00, 5000.00, 1),
(NULL, 'Additional Products', 'per_product', 0.00, 200.00, 0),
(NULL, 'Additional User Roles', 'per_user_role', 0.00, 3000.00, 2);

-- =====================================================
-- DYNAMIC QUESTIONS SYSTEM
-- =====================================================

CREATE TABLE IF NOT EXISTS question_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    step_number INTEGER DEFAULT 0,
    icon VARCHAR(50),
    description TEXT,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO question_categories (name, slug, step_number, display_order) VALUES
('Project Basics', 'project', 1, 1),
('Business Information', 'business', 2, 2),
('Design Requirements', 'design', 3, 3),
('Website Pages', 'pages', 4, 4),
('Features', 'features', 5, 5),
('User Accounts', 'users', 6, 6),
('Content', 'content', 7, 7),
('E-commerce', 'ecommerce', 8, 8),
('Booking', 'booking', 9, 9),
('Payment', 'payment', 10, 10),
('Integrations', 'integrations', 11, 11),
('SEO & Analytics', 'seo', 12, 12),
('Security', 'security', 13, 13),
('Multilingual', 'multilingual', 14, 14),
('Technical', 'technical', 15, 15),
('Budget & Timeline', 'budget', 16, 16),
('Contact', 'contact', 17, 17)
ON CONFLICT (slug) DO NOTHING;

CREATE TABLE IF NOT EXISTS questions (
    id SERIAL PRIMARY KEY,
    category_id INTEGER NOT NULL REFERENCES question_categories(id),
    question TEXT NOT NULL,
    description TEXT,
    question_type VARCHAR(20) NOT NULL DEFAULT 'text' CHECK (question_type IN ('text','textarea','number','email','phone','single_choice','multiple_choice','yes_no','url','date','file','range')),
    field_name VARCHAR(100) NOT NULL,
    is_required BOOLEAN DEFAULT FALSE,
    placeholder VARCHAR(255),
    default_value VARCHAR(255),
    min_value INTEGER,
    max_value INTEGER,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    show_if_question_id INTEGER REFERENCES questions(id) ON DELETE SET NULL,
    show_if_value VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (field_name)
);

CREATE TABLE IF NOT EXISTS question_options (
    id SERIAL PRIMARY KEY,
    question_id INTEGER NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    label VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    price_impact DECIMAL(12,2) DEFAULT 0.00,
    feature_id INTEGER REFERENCES features(id) ON DELETE SET NULL,
    display_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- REQUIREMENTS (Customer Submissions)
-- =====================================================

CREATE TABLE IF NOT EXISTS requirements (
    id SERIAL PRIMARY KEY,
    requirement_id VARCHAR(30) NOT NULL UNIQUE,
    resume_token VARCHAR(64) UNIQUE,
    status VARCHAR(30) DEFAULT 'new' CHECK (status IN ('new','contacted','under_review','requirement_clarification','quotation_prepared','quotation_sent','negotiation','approved','rejected','on_hold','converted','completed','cancelled')),
    website_type_id INTEGER REFERENCES website_types(id) ON DELETE SET NULL,
    website_type_other VARCHAR(255),
    project_purpose JSONB,
    project_purpose_other VARCHAR(255),
    project_name VARCHAR(255),
    project_description TEXT,
    existing_website VARCHAR(20) DEFAULT 'no' CHECK (existing_website IN ('no','keep','redesign','rebuild','not_sure')),
    existing_website_url VARCHAR(500),
    existing_website_problems JSONB,
    design_available VARCHAR(20) DEFAULT 'need_design' CHECK (design_available IN ('yes','logo_only','references','no','need_design')),
    design_style JSONB,
    design_style_other VARCHAR(255),
    reference_urls JSONB,
    reference_notes TEXT,
    has_logo VARCHAR(20) DEFAULT 'no' CHECK (has_logo IN ('yes','no','need_design')),
    has_brand_colors VARCHAR(20) DEFAULT 'no' CHECK (has_brand_colors IN ('yes','no','need_help')),
    has_brand_guidelines VARCHAR(10) DEFAULT 'no' CHECK (has_brand_guidelines IN ('yes','no')),
    selected_pages JSONB,
    custom_pages JSONB,
    page_count VARCHAR(20),
    content_provider VARCHAR(20) DEFAULT 'customer' CHECK (content_provider IN ('customer','developer','both','copywriting')),
    content_requirements JSONB,
    needs_cms VARCHAR(10) DEFAULT 'no' CHECK (needs_cms IN ('yes','no','not_sure')),
    cms_manage_items JSONB,
    needs_user_accounts VARCHAR(10) DEFAULT 'no' CHECK (needs_user_accounts IN ('no','yes','not_sure')),
    user_features JSONB,
    user_roles JSONB,
    user_roles_other VARCHAR(255),
    needs_ecommerce VARCHAR(10) DEFAULT 'no' CHECK (needs_ecommerce IN ('no','yes','future')),
    ecommerce_features JSONB,
    product_quantity VARCHAR(20),
    needs_booking VARCHAR(10) DEFAULT 'no' CHECK (needs_booking IN ('no','yes')),
    booking_types JSONB,
    booking_features JSONB,
    needs_payment VARCHAR(10) DEFAULT 'no' CHECK (needs_payment IN ('no','yes','future')),
    payment_methods JSONB,
    payment_methods_other VARCHAR(255),
    communication_features JSONB,
    search_type VARCHAR(10) DEFAULT 'none' CHECK (search_type IN ('none','basic','advanced')),
    search_filters JSONB,
    needs_maps VARCHAR(10) DEFAULT 'no' CHECK (needs_maps IN ('no','yes')),
    map_features JSONB,
    language_count INTEGER DEFAULT 1,
    languages JSONB,
    seo_level VARCHAR(10) DEFAULT 'none' CHECK (seo_level IN ('none','basic','standard','advanced')),
    seo_features JSONB,
    analytics_features JSONB,
    security_features JSONB,
    hosting_domain JSONB,
    integrations JSONB,
    integrations_detail JSONB,
    special_requirements TEXT,
    specific_workflow TEXT,
    timeline VARCHAR(20) DEFAULT 'no_deadline' CHECK (timeline IN ('no_deadline','1month','1_2months','2_3months','3_6months','6plus')),
    launch_date DATE,
    budget_range VARCHAR(50),
    needs_maintenance VARCHAR(10) DEFAULT 'no' CHECK (needs_maintenance IN ('no','yes','not_sure')),
    maintenance_features JSONB,
    customer_name VARCHAR(150) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(30),
    customer_company VARCHAR(150),
    customer_address TEXT,
    customer_city VARCHAR(100),
    customer_country VARCHAR(100) DEFAULT 'Nepal',
    preferred_contact VARCHAR(20) DEFAULT 'email' CHECK (preferred_contact IN ('phone','email','whatsapp','viber','other')),
    submitted_ip VARCHAR(45),
    user_agent TEXT,
    system_estimate DECIMAL(12,2) DEFAULT 0.00,
    admin_override_price DECIMAL(12,2),
    price_override_reason TEXT,
    complexity_score INTEGER DEFAULT 0,
    complexity_level VARCHAR(20) DEFAULT 'basic' CHECK (complexity_level IN ('basic','moderate','advanced','complex')),
    estimate_confidence VARCHAR(10) DEFAULT 'high' CHECK (estimate_confidence IN ('high','medium','low')),
    assigned_to INTEGER REFERENCES admins(id) ON DELETE SET NULL,
    submission_json JSONB,
    is_archived BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS requirement_answers (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    question_id INTEGER NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    answer_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS requirement_features (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    feature_id INTEGER NOT NULL REFERENCES features(id) ON DELETE CASCADE,
    price DECIMAL(12,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS requirement_files (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    file_type VARCHAR(50),
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_size INTEGER,
    mime_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- STATUS HISTORY
-- =====================================================

CREATE TABLE IF NOT EXISTS requirement_status_history (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INTEGER REFERENCES admins(id) ON DELETE SET NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- ADMIN NOTES
-- =====================================================

CREATE TABLE IF NOT EXISTS admin_notes (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    admin_id INTEGER NOT NULL REFERENCES admins(id) ON DELETE CASCADE,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- QUOTATIONS
-- =====================================================

CREATE TABLE IF NOT EXISTS quotations (
    id SERIAL PRIMARY KEY,
    quotation_number VARCHAR(30) NOT NULL UNIQUE,
    requirement_id INTEGER NOT NULL REFERENCES requirements(id) ON DELETE CASCADE,
    admin_id INTEGER NOT NULL REFERENCES admins(id) ON DELETE CASCADE,
    status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft','sent','accepted','rejected','expired')),
    project_name VARCHAR(255),
    scope_description TEXT,
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    discount_type VARCHAR(10) DEFAULT 'none' CHECK (discount_type IN ('none','fixed','percentage')),
    discount_value DECIMAL(12,2) DEFAULT 0.00,
    discount_amount DECIMAL(12,2) DEFAULT 0.00,
    tax_enabled BOOLEAN DEFAULT FALSE,
    tax_percentage DECIMAL(5,2) DEFAULT 0.00,
    tax_amount DECIMAL(12,2) DEFAULT 0.00,
    total_amount DECIMAL(12,2) DEFAULT 0.00,
    validity_days INTEGER DEFAULT 30,
    valid_until DATE,
    terms_and_conditions TEXT,
    notes TEXT,
    internal_notes TEXT,
    sent_at TIMESTAMP,
    responded_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS quotation_items (
    id SERIAL PRIMARY KEY,
    quotation_id INTEGER NOT NULL REFERENCES quotations(id) ON DELETE CASCADE,
    feature_id INTEGER REFERENCES features(id) ON DELETE SET NULL,
    item_name VARCHAR(255) NOT NULL,
    description TEXT,
    quantity INTEGER DEFAULT 1,
    unit_price DECIMAL(12,2) DEFAULT 0.00,
    total_price DECIMAL(12,2) DEFAULT 0.00,
    display_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS quotation_status_history (
    id SERIAL PRIMARY KEY,
    quotation_id INTEGER NOT NULL REFERENCES quotations(id) ON DELETE CASCADE,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INTEGER REFERENCES admins(id) ON DELETE SET NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- EMAIL LOGS
-- =====================================================

CREATE TABLE IF NOT EXISTS email_logs (
    id SERIAL PRIMARY KEY,
    requirement_id INTEGER REFERENCES requirements(id) ON DELETE SET NULL,
    quotation_id INTEGER REFERENCES quotations(id) ON DELETE SET NULL,
    to_email VARCHAR(150) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT,
    status VARCHAR(10) DEFAULT 'queued' CHECK (status IN ('queued','sent','failed')),
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- AUDIT LOGS
-- =====================================================

CREATE TABLE IF NOT EXISTS audit_logs (
    id SERIAL PRIMARY KEY,
    admin_id INTEGER REFERENCES admins(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50),
    target_id INTEGER,
    details JSONB,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SETTINGS
-- =====================================================

CREATE TABLE IF NOT EXISTS settings (
    id SERIAL PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type VARCHAR(10) DEFAULT 'text' CHECK (setting_type IN ('text','textarea','number','boolean','json','file')),
    setting_group VARCHAR(50) DEFAULT 'general',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) VALUES
('company_name', 'WebDev Solutions', 'text', 'general', 'Company name'),
('company_email', 'info@webdevsolutions.com', 'text', 'general', 'Company email'),
('company_phone', '+977-1-4XXXXXX', 'text', 'general', 'Company phone'),
('company_address', 'Kathmandu, Nepal', 'textarea', 'general', 'Company address'),
('currency', 'NPR', 'text', 'pricing', 'Currency code'),
('currency_symbol', 'NPR', 'text', 'pricing', 'Currency symbol'),
('tax_enabled', '0', 'boolean', 'pricing', 'Enable tax'),
('tax_percentage', '13', 'number', 'pricing', 'Tax percentage'),
('quotation_validity_days', '30', 'number', 'pricing', 'Quotation validity in days'),
('price_display_mode', 'both', 'text', 'pricing', 'exact, range, or both'),
('smtp_host', '', 'text', 'email', 'SMTP host'),
('smtp_port', '587', 'number', 'email', 'SMTP port'),
('smtp_username', '', 'text', 'email', 'SMTP username'),
('smtp_password', '', 'text', 'email', 'SMTP password'),
('smtp_encryption', 'tls', 'text', 'email', 'SMTP encryption'),
('from_email', '', 'text', 'email', 'From email address'),
('from_name', 'WebCraft Studio', 'text', 'email', 'From name'),
('max_upload_size', '10', 'number', 'general', 'Max upload size in MB'),
('requirement_prefix', 'REQ', 'text', 'general', 'Requirement ID prefix'),
('quotation_prefix', 'QUO', 'text', 'general', 'Quotation number prefix'),
('maintenance_mode', '0', 'boolean', 'general', 'Maintenance mode')
ON CONFLICT (setting_key) DO NOTHING;

-- =====================================================
-- INDEXES
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_requirements_status ON requirements(status);
CREATE INDEX IF NOT EXISTS idx_requirements_created ON requirements(created_at);
CREATE INDEX IF NOT EXISTS idx_requirements_type ON requirements(website_type_id);
CREATE INDEX IF NOT EXISTS idx_requirements_customer_email ON requirements(customer_email);
CREATE INDEX IF NOT EXISTS idx_requirements_archived ON requirements(is_archived);
CREATE INDEX IF NOT EXISTS idx_quotations_status ON quotations(status);
CREATE INDEX IF NOT EXISTS idx_quotations_requirement ON quotations(requirement_id);
CREATE INDEX IF NOT EXISTS idx_audit_admin ON audit_logs(admin_id);
CREATE INDEX IF NOT EXISTS idx_audit_created ON audit_logs(created_at);
