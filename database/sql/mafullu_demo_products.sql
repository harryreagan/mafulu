INSERT INTO products (title, slug, description, category, price_usd, file_path, preview_path, is_active, sales_count, created_at, updated_at)
VALUES
('Canva Social Launch Kit', 'canva-social-launch-kit', 'A polished social media launch template pack for freelancers, coaches, and small brands. Includes editable campaign layouts, posting plans, and brand-ready copy prompts.', 'template', 29.00, 'private/products/canva-social-launch-kit.txt', 'private/previews/canva-social-launch-kit-preview.txt', 1, 18, NOW(), NOW()),
('Freelancer Proposal Bundle', 'freelancer-proposal-bundle', 'A proposal and statement-of-work bundle for service businesses. Built to help freelancers send clearer offers, timelines, and onboarding details to clients.', 'template', 24.00, 'private/products/freelancer-proposal-bundle.txt', 'private/previews/freelancer-proposal-bundle-preview.txt', 1, 31, NOW(), NOW()),
('Creator Media Kit Pack', 'creator-media-kit-pack', 'A sponsorship-ready media kit template set for creators and consultants. Includes clean one-page layouts for audience stats, rates, and partnership packages.', 'template', 27.00, 'private/products/creator-media-kit-pack.txt', 'private/previews/creator-media-kit-pack-preview.txt', 1, 12, NOW(), NOW()),
('Client Onboarding Playbook', 'client-onboarding-playbook', 'A short operational guide for improving first impressions after a sale. Covers welcome emails, kickoff checklists, document requests, and communication standards.', 'ebook', 18.00, 'private/products/client-onboarding-playbook.txt', 'private/previews/client-onboarding-playbook-preview.txt', 1, 22, NOW(), NOW()),
('Weekly Content Planning Handbook', 'weekly-content-planning-handbook', 'A practical planning handbook for content creators and small teams. Helps structure weekly themes, publishing cadence, and content repurposing workflows.', 'ebook', 15.00, 'private/products/weekly-content-planning-handbook.txt', 'private/previews/weekly-content-planning-handbook-preview.txt', 1, 15, NOW(), NOW()),
('Digital Product Pricing Guide', 'digital-product-pricing-guide', 'A pricing guide for lawful digital products such as templates, ebooks, and mini tools. Covers positioning, anchoring, offer structure, and tier planning.', 'ebook', 21.00, 'private/products/digital-product-pricing-guide.txt', 'private/previews/digital-product-pricing-guide-preview.txt', 1, 19, NOW(), NOW()),
('Invoice Tracker Desktop Toolkit', 'invoice-tracker-desktop-toolkit', 'A lightweight operational toolkit concept for tracking invoice status, due dates, and client payment follow-ups. Suitable for small service teams and solo operators.', 'software', 59.00, 'private/products/invoice-tracker-desktop-toolkit.txt', 'private/previews/invoice-tracker-desktop-toolkit-preview.txt', 1, 9, NOW(), NOW()),
('Laravel Client Portal Starter', 'laravel-client-portal-starter', 'A starter digital product concept for private client workspaces. Focused on uploads, milestones, internal notes, and simple project communication patterns.', 'software', 79.00, 'private/products/laravel-client-portal-starter.txt', 'private/previews/laravel-client-portal-starter-preview.txt', 1, 14, NOW(), NOW()),
('Email Automation Mini Course', 'email-automation-mini-course', 'A concise course on building welcome sequences, lead nurture flows, and post-purchase follow-up for legitimate online businesses.', 'course', 69.00, 'private/products/email-automation-mini-course.txt', 'private/previews/email-automation-mini-course-preview.txt', 1, 17, NOW(), NOW()),
('Productized Services Bootcamp', 'productized-services-bootcamp', 'A training product for packaging freelance services into clear, repeatable offers. Covers scoping, offer design, delivery systems, and client communication.', 'course', 89.00, 'private/products/productized-services-bootcamp.txt', 'private/previews/productized-services-bootcamp-preview.txt', 1, 11, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    description = VALUES(description),
    category = VALUES(category),
    price_usd = VALUES(price_usd),
    file_path = VALUES(file_path),
    preview_path = VALUES(preview_path),
    is_active = VALUES(is_active),
    sales_count = VALUES(sales_count),
    updated_at = VALUES(updated_at);
