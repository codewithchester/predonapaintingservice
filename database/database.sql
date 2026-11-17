CREATE DATABASE IF NOT EXISTS peridona_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE peridona_db;

CREATE TABLE IF NOT EXISTS roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS administrators (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id BIGINT UNSIGNED NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_admin_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  permission_key VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS role_permissions (
  role_id BIGINT UNSIGNED NOT NULL,
  permission_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(role_id, permission_id),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS services (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  short_description VARCHAR(300) NOT NULL,
  description TEXT NOT NULL,
  icon VARCHAR(80) DEFAULT 'paintbrush',
  image_path VARCHAR(255) NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS project_categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  slug VARCHAR(140) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS projects (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  location VARCHAR(180) NULL,
  summary VARCHAR(300) NULL,
  description TEXT NULL,
  image_path VARCHAR(255) NULL,
  before_image VARCHAR(255) NULL,
  after_image VARCHAR(255) NULL,
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_projects_published (is_published, created_at),
  FOREIGN KEY (category_id) REFERENCES project_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS enquiries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference_code VARCHAR(32) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(190) NULL,
  location VARCHAR(180) NULL,
  property_type VARCHAR(100) NULL,
  service_type VARCHAR(120) NULL,
  project_size VARCHAR(100) NULL,
  preferred_date DATE NULL,
  message TEXT NOT NULL,
  attachment_path VARCHAR(255) NULL,
  status ENUM('pending','contacted','quoted','in_progress','completed','closed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_enquiries_status (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS testimonials (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(120) NOT NULL,
  role_label VARCHAR(160) NULL,
  quote TEXT NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS gallery_categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  slug VARCHAR(140) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS website_settings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(120) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(30) NULL,
  subject VARCHAR(160) NULL,
  message TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activity_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  administrator_id BIGINT UNSIGNED NULL,
  action VARCHAR(120) NOT NULL,
  details TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_activity_created (created_at),
  FOREIGN KEY (administrator_id) REFERENCES administrators(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO roles(name,description) VALUES
 ('Super Administrator','Full access to the Peridona website administrator area and team accounts.'),
 ('Content Manager','Manage public website content, services, projects, categories and testimonials.'),
 ('Enquiry Manager','Manage quotation enquiries and customer contact messages.');
INSERT IGNORE INTO project_categories(name,slug) VALUES
 ('Residential','residential'),('Commercial','commercial'),('Interior','interior'),('Exterior','exterior'),('Decorative Finishes','decorative-finishes');
INSERT IGNORE INTO services(title,slug,short_description,description,icon,is_published,sort_order) VALUES
 ('Interior Painting','interior-painting','Thoughtful colour and careful application for inviting interiors.','We help transform interior spaces through considered colour choices, careful preparation and a clean, professional application.','paintbrush',1,1),
 ('Exterior Painting','exterior-painting','Durable exterior finishes that help your property make a lasting impression.','Exterior painting requires careful surface assessment and the right products for the environment. Discuss your property with our team for a tailored recommendation.','house',1,2),
 ('Residential Painting','residential-painting','A considered finish for homes, apartments and residential properties.','From a single room to a full home, we work with homeowners to understand their needs, colour preferences and desired finish.','house',1,3),
 ('Commercial Painting','commercial-painting','Professional environments for offices, shops and business spaces.','We work with businesses and property managers to plan painting projects around the needs of their spaces and operations.','building-2',1,4),
 ('Colour Consultation','colour-consultation','Make confident colour choices for the character of your space.','Explore colour directions that complement your space, lighting, furnishings and the atmosphere you want to create.','palette',1,5),
 ('Surface Preparation & Finishing','surface-preparation-finishing','Careful preparation and attention to detail at every stage.','A quality finish starts before the first coat. We assess and prepare surfaces as part of a complete painting plan.','layers',1,6);
INSERT IGNORE INTO website_settings(setting_key,setting_value) VALUES
 ('phone_primary','0547320593'),('phone_secondary','0596262939'),('whatsapp_number','233547320593'),('company_email',''),('business_location','Ghana'),('working_hours','Please contact us to confirm availability.'),('instagram_url',''),('facebook_url','');


INSERT IGNORE INTO permissions(permission_key,description) VALUES
 ('admin.access','Access the administrator dashboard'),
 ('projects.manage','Create, edit and delete projects'),
 ('services.manage','Create, edit and delete services'),
 ('categories.manage','Manage project categories'),
 ('enquiries.manage','View and update quotation enquiries'),
 ('messages.manage','View contact messages'),
 ('testimonials.manage','Manage customer testimonials'),
 ('settings.manage','Update website settings'),
 ('administrators.manage','Create, edit, activate and deactivate administrator accounts');
-- Role permissions: existing and newly installed databases receive the same role access.
INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r CROSS JOIN permissions p WHERE r.name='Super Administrator';
INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r JOIN permissions p ON p.permission_key IN ('admin.access','projects.manage','services.manage','categories.manage','testimonials.manage') WHERE r.name='Content Manager';
INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r JOIN permissions p ON p.permission_key IN ('admin.access','enquiries.manage','messages.manage') WHERE r.name='Enquiry Manager';
