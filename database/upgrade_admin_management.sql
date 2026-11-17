-- Peridona administrator management + predefined staff roles
-- Run this once in phpMyAdmin against the existing peridona_db database.
INSERT IGNORE INTO roles(name,description) VALUES
 ('Super Administrator','Full access to the Peridona website administrator area and team accounts.'),
 ('Content Manager','Manage public website content, services, projects, categories and testimonials.'),
 ('Enquiry Manager','Manage quotation enquiries and customer contact messages.');

INSERT IGNORE INTO permissions(permission_key,description) VALUES
 ('administrators.manage','Create, edit, activate and deactivate administrator accounts');

INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r CROSS JOIN permissions p WHERE r.name='Super Administrator';

INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r JOIN permissions p ON p.permission_key IN ('admin.access','projects.manage','services.manage','categories.manage','testimonials.manage') WHERE r.name='Content Manager';

INSERT IGNORE INTO role_permissions(role_id,permission_id)
SELECT r.id,p.id FROM roles r JOIN permissions p ON p.permission_key IN ('admin.access','enquiries.manage','messages.manage') WHERE r.name='Enquiry Manager';
