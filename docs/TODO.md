# Marigold Signature Commerce Platform
# Master Project TODO List

> **Stack:** Core PHP 8.3+ · MySQL 8 · Tailwind CSS · Alpine.js · GSAP · Swiper.js
> **Design:** Dark luxury — `#050505` bg · `#C9A227` gold · Manrope headings · Inter body · Lucide icons
> **Principle:** Mobile-first. Every screen must feel like a native app on mobile.
> **Skills:** `ui-ux-pro-max` · `php-pro` · `database-design` · `seo-fundamentals` · `payment-integration` · `mobile-design` · `web-performance-optimization` · `tailwind-design-system` · `scroll-experience` · `analytics-tracking`

---

## LEGEND
- `[ ]` Not started
- `[/]` In progress
- `[x]` Complete
- 🎨 Needs `ui-ux-pro-max` skill
- 📱 Mobile-first critical
- 🔒 Security-sensitive
- ⚡ Performance-critical
- 🗄️ Database work

---

## PHASE 0 — Project Foundation

### 0.1 Environment & Repository
- [x] Set up project directory structure (`/public`, `/app`, `/storage`, `/database`, `/vendor`)
- [x] Initialize Composer with autoloading (PSR-4)
- [x] Configure `.env` file (APP, DB, SMTP, Paystack keys)
- [x] Set up `.gitignore` (exclude `.env`, `/vendor`, `/storage/logs`, `/storage/tmp`)
- [x] Create `public/index.php` as single entry point
- [x] Configure `.htaccess` for clean URLs and security headers
- [x] Set up Tailwind CSS (CDN for development, compiled for production)
- [x] Install GSAP (CDN), Swiper.js, Alpine.js, Lenis (smooth scroll)
- [x] Load Google Fonts: Manrope (600, 700, 800) + Inter (400, 500, 600)
- [x] Install Lucide Icons
- [x] Install PHPMailer, DomPDF, Intervention Image via Composer

### 0.2 Core Framework (MVC)
- [x] Build custom Router (GET, POST, PUT, DELETE, named routes)
- [x] Build base Controller class
- [x] Build base Model class with PDO connection
- [x] Build View renderer (template engine with layouts)
- [x] Build Service layer base class
- [x] Build Repository layer base class
- [x] Build Validator class (server-side, reusable rules)
- [x] Build Session manager (secure sessions, CSRF tokens, regeneration)
- [x] Build custom Exception handler (friendly errors + detailed logs)
- [x] Build Logger class (application, payment, auth, email logs)
- [x] Build Image processor (auto compress, WebP generation, thumbnails, lazy load)
- [x] Build File Cache class (homepage, categories, products, menus)
- [x] Build Config loader (never hardcode — currency, company name, tax rate, etc.)
- [x] Set up environment variables parser

### 0.3 Design System Foundation 🎨 📱
> Skill: `ui-ux-pro-max` + `tailwind-design-system` + `mobile-design`

- [x] Create `public/assets/css/design-system.css` — all CSS custom properties:
  - Colors: `--bg-primary: #050505`, `--surface: #111111`, `--card: #191919`, `--border: #2B2B2B`, `--gold: #C9A227`, `--gold-accent: #D4AF37`, `--text-primary: #F8F8F8`, `--text-secondary: #A7A7A7`, `--text-muted: #6B7280`
  - Spacing scale: 4 / 8 / 12 / 16 / 24 / 32 / 40 / 48 / 64 / 80 / 96 / 120 / 160
  - Border radius: buttons 12px, cards 16px, inputs 12px, modals 20px, product cards 20px
  - Typography scale: Display 72px down to Small 12px
  - Shadow system: soft and subtle — contrast over shadow
- [x] Configure Tailwind config with brand tokens
- [x] Build reusable component templates:
  - [x] Button (Primary, Secondary, Ghost, Icon) — black bg + gold border, hover: gold bg + black text
  - [x] Input / Textarea / Select (52px height, gold focus ring)
  - [x] Product Card (image 1:1, badge, category, name, price, wishlist, quick-add, request quote)
  - [x] Standard Card
  - [x] Badge system (New=Gold, Sale=Red, Pre-order=Blue, In Stock=Green, Out=Grey, Quote Only=Purple)
  - [x] Modal (scale 0.95 to 1, fade overlay)
  - [x] Drawer — slide from right, fade overlay 📱
  - [x] Toast notifications — slide from bottom, fade
  - [x] Accordion (height animation)
  - [x] Tabs
  - [x] Breadcrumbs
  - [x] Tooltips
  - [x] Table (sticky header, hover state, pagination, search, bulk actions)
  - [x] Skeleton loaders (shimmer effect)
  - [x] Empty states
  - [x] Loading states
  - [x] Error states
  - [x] Pagination
- [x] Build GSAP animation system:
  - [x] Page load: fade + 16px upward translate, 0.8s duration
  - [x] Hero: split text reveal + image scale 1.1 to 1 + button stagger
  - [x] Cards: fade + translateY 24px, stagger 0.08s
  - [x] Hover: -6px lift + scale 1.02
  - [x] Sticky header: shrink + blur + opacity transition on scroll
  - [x] Counters: animate 0 to value (ScrollTrigger)
  - [x] Progress bars: animated fill
  - [x] Page transitions: fade 300ms
  - [x] Respect prefers-reduced-motion
- [x] Build mobile-first CSS layout system:
  - [x] 4-col mobile grid -> 8-col tablet -> 12-col desktop
  - [x] Section padding: 56px mobile / 80px tablet / 120px desktop
  - [x] Max container: 1440px / Content: 1280px
  - [x] All touch targets minimum 44x44px
  - [x] Mobile bottom navigation bar (Home, Shop, Wishlist, Account, Cart) — app-like 📱
  - [x] Swipe gesture support for carousels and drawers 📱
  - [x] Input font-size min 16px (prevent iOS auto-zoom) 📱

---

## PHASE 1 — Authentication Module 🔒

### 1.1 Database 🗄️
> Skill: `database-design`

- [x] Create `users` table (id, uuid, first_name, last_name, email, phone, password, avatar, status, email_verified_at, last_login_at, remember_token, timestamps, deleted_at) + indexes
- [x] Create `roles` table (id, name, slug, description)
- [x] Create `permissions` table (id, module, name, slug, description)
- [x] Create `role_permissions` pivot table
- [x] Create `user_roles` pivot table
- [x] Seed default roles: Super Admin, Administrator, Sales, Inventory, Marketing, Support, Finance, Customer
- [x] Seed default permissions per module (view, create, edit, delete, approve, export, manage users)

### 1.2 Auth Logic 🔒
- [x] AuthController (login, register, logout, forgot-password, reset-password)
- [x] AuthService (bcrypt hashing, credential validation, remember token generation)
- [x] CSRF token on all auth forms
- [x] Rate limiting on login (lock after N failed attempts)
- [x] Session regeneration on login/logout
- [x] Session timeout handling
- [x] Remember Me (persistent cookie, 30 days)
- [x] Role middleware (admin guard vs customer guard — separate routes)
- [x] Permission checker (Policy class per module)
- [x] Auth logs (login, logout, failed attempts, password resets)

### 1.3 Auth UI 🎨 📱
> Skill: `ui-ux-pro-max` + `mobile-design`

- [x] Login page (email + password, remember me, forgot password link)
- [x] Register page (name, email, phone optional, password)
- [x] Forgot Password page
- [x] Reset Password page
- [x] All auth pages: full-screen dark luxury design, Marigold logo, gold accents
- [x] Mobile: full-screen card layout, no wasted space 📱
- [x] Inline real-time validation feedback (gold=valid, red=error)

---

## PHASE 1 — Full Database Schema 🗄️
> Skill: `database-design`

- [x] `customers` (id, user_id, company_name, industry, tax_number, website, default_shipping_address, default_billing_address, notes, status)
- [x] `customer_addresses` (id, customer_id, type, country, state, city, address_line_1, address_line_2, postal_code, phone, is_default)
- [x] `categories` (id, parent_id, name, slug, description, image, icon, status, sort_order, meta_title, meta_description)
- [x] `brands` (id, name, slug, logo, website, description, status, featured)
- [x] `collections` (id, name, slug, banner, description, status)
- [x] `products` (id, uuid, sku, barcode, name, slug, short_description, description, brand_id, category_id, collection_id, status, product_type, price, sale_price, cost_price, stock_quantity, minimum_order_quantity, maximum_order_quantity, weight, dimensions, is_featured, is_new, is_best_seller, meta_title, meta_description, timestamps, deleted_at) + indexes on slug, sku, status, price
- [x] `product_images` (id, product_id, image, alt_text, sort_order, is_featured)
- [x] `product_variants` (id, product_id, variant_name, sku, price, stock)
- [x] `product_attributes` (id, name, slug, type)
- [x] `product_attribute_values` (attribute_id, value)
- [x] `product_variant_attributes` (variant_id, attribute_value_id)
- [x] `wishlists` (customer_id, product_id, created_at)
- [x] `carts` (id, customer_id, session_id, expires_at)
- [x] `cart_items` (cart_id, product_id, variant_id, quantity, price)
- [x] `orders` (id, order_number, customer_id, status, payment_status, shipping_status, subtotal, discount, tax, shipping, grand_total, payment_method, transaction_reference, notes, timestamps)
- [x] `order_items` (order_id, product_id, variant_id, quantity, price, subtotal)
- [x] `order_status_history` (order_id, status, comment, updated_by, created_at)
- [x] `quotes` (id, quote_number, customer_id, status, subtotal, discount, tax, grand_total, expiry_date, notes, sales_person, timestamps)
- [x] `quote_items` (quote_id, product_id, quantity, price)
- [x] `quote_messages` (quote_id, sender_id, message, created_at)
- [x] `quote_files` (quote_id, file_path, file_type, created_at)
- [x] `payments` (order_id, gateway, reference, amount, status, response_json, paid_at)
- [x] `shipping_methods` (id, name, price, estimated_days, status)
- [x] `shipments` (order_id, tracking_number, carrier, status, dispatched_at, delivered_at)
- [x] `coupons` (id, code, type, value, minimum_spend, expiry, usage_limit, customer_restriction, status)
- [x] `pages` (id, title, slug, template, status, seo_json, published_at)
- [x] `page_sections` (page_id, section_type, sort_order, configuration_json, status)
- [x] `posts` (id, title, slug, excerpt, content, author_id, featured_image, status, published_at, meta_title, meta_description)
- [x] `post_categories` (id, name, slug, description, status)
- [x] `post_tags` (id, name, slug)
- [x] `post_post_categories` pivot
- [x] `post_post_tags` pivot
- [x] `media` (id, filename, path, type, size, alt_text, folder, created_at)
- [x] `newsletters` (id, email, status, created_at)
- [x] `testimonials` (id, name, company, image, review, rating, featured, status)
- [x] `faqs` (id, category, question, answer, sort_order, status)
- [x] `reviews` (id, product_id, customer_id, rating, review, status, created_at)
- [x] `announcements` (id, message, bg_color, text_color, button_text, button_url, schedule_start, schedule_end, priority, dismissible, status)
- [x] `settings` (key, value, type, group)
- [x] `notifications` (id, user_id, type, data_json, read_at, created_at)
- [x] `logs` (id, level, message, context_json, created_at)
- [x] Run all migrations in order + seed core data (roles, permissions, settings, demo products)

---

## PHASE 1 — Public Website

### 2.1 Header & Navigation 🎨 📱
> Skill: `ui-ux-pro-max` + `mobile-design`

- [x] Announcement bar (40px, editable from CMS, auto-rotate if multiple)
- [x] Desktop header: Logo | Shop · Categories · Corporate Solutions · About · Blog · Contact | Search · Wishlist · Account · Cart
- [x] Transparent over hero, solid dark bg after scrolling (GSAP shrink + blur transition)
- [x] Mega dropdown for Categories (GSAP animated, lifestyle images per category)
- [x] Mobile header: Logo | Search icon | Cart icon | Hamburger 📱
- [x] Mobile full-screen slide-in drawer menu (app-like, animated) 📱
- [x] Instant search overlay (results: Products, Categories, Articles — no page reload)
- [x] Cart mini-drawer (slide from right, editable qty, coupon field, checkout CTA)
- [x] Wishlist count badge on icon
- [x] Cart item count badge on icon

### 2.2 Homepage 🎨 📱 ⚡
> Skill: `ui-ux-pro-max` + `scroll-experience`

- [x] Hero Section: full-viewport, lifestyle photography, GSAP split-text headline reveal, subtitle fade+translate, staggered buttons, subtle background parallax, image scale 1.1 to 1
- [x] Trusted Brands: logo cloud with marquee auto-scroll animation, pause on hover
- [x] Featured Categories: large image cards, title, product count, hover animation — mobile: horizontal scroll carousel 📱
- [x] Corporate Solutions: 6 cards (Employee Welcome Kits, Executive Gifts, Conference Merchandise, Promotional Campaigns, Client Appreciation, Awards & Recognition)
- [x] Featured Products: 8 products, hover image swap, quick-add, wishlist, request quote
- [x] New Arrivals: Swiper carousel, lazy load images
- [x] Why Choose Marigold: 4 columns, animated counters on scroll (Premium Quality, Fast Delivery, Customization, Dedicated Support)
- [x] Featured Collection Banner: large CMS-driven promotional banner
- [x] Testimonials: Swiper slider, autoplay, customer image, company, 5-star rating
- [x] Instagram Gallery: 6-image grid, hover overlay effect
- [x] Latest Insights: 3 blog articles (image, category, reading time, title, CTA)
- [x] Newsletter: email input, subscribe button, success toast notification
- [x] Footer: 4-column (company info, quick links, categories, newsletter); bottom bar (copyright, privacy, terms, social, payment method icons)
- [x] Mobile homepage: single column, all touch-friendly, bottom sticky CTA bar 📱

### 2.3 Shop / Product Listing Page 🎨 📱
> Skill: `ui-ux-pro-max`

- [x] Page hero (category name + breadcrumb)
- [x] Sidebar filters: Category, Brand, Price range, Availability, New Arrivals, Best Sellers, Pre-order, Quote Only
- [x] Mobile: filter drawer slides up from bottom (app sheet pattern) 📱
- [x] Active filter chips displayed above grid with individual clear buttons
- [x] Sorting dropdown: Newest, Price Low, Price High, Popularity, Alphabetical
- [x] Product grid: 4-col desktop, 2-col tablet, 2-col mobile
- [x] Product cards: hover image swap, wishlist icon, quick-add, request quote
- [x] Product count indicator (Showing X of Y products)
- [x] Pagination + optional infinite scroll
- [x] Empty state (no results, search suggestions)

### 2.4 Product Detail Page 🎨 📱
> Skill: `ui-ux-pro-max`

- [x] Breadcrumb navigation
- [x] Image gallery: thumbnails, zoom on hover, lightbox, swipe on mobile 📱
- [x] Product info: name, price (regular + sale), SKU, category, brand, availability badge, stock status
- [x] Variant selector (if applicable)
- [x] Quantity input (enforces MOQ, max stock)
- [x] Sticky action area on mobile (Add to Cart + Request Quote always visible) 📱
- [x] Action buttons: Add to Cart, Request Quote, Add to Wishlist, Share
- [x] Accordion sections: Description, Specifications, Shipping Info, Returns, FAQs
- [x] Delivery estimate display
- [x] Related products: 8 products, horizontal scroll on mobile 📱
- [x] Recently viewed products (localStorage + session)
- [x] Schema.org Product + BreadcrumbList structured data

### 2.5 Cart 📱
- [x] Mini cart drawer (slide from right, always accessible via header icon)
- [x] Cart page: item list, qty editor, remove item, coupon field, order totals, shipping estimate, checkout CTA
- [x] Persistent cart for logged-in users (merge session cart on login)
- [x] Guest cart (session-based)
- [x] Real-time total recalculation (Alpine.js, no page reload)
- [x] Mobile: full-page cart with fixed bottom checkout button 📱

### 2.6 Checkout 🎨 📱 🔒
> Skill: `payment-integration`

- [x] Guest checkout option (no account required)
- [x] Registered checkout (pre-fill from saved addresses)
- [x] Shipping address form
- [x] Billing address (same as shipping toggle)
- [x] Payment method selection: Paystack, Bank Transfer
- [x] Order notes field
- [x] Order review summary (collapsible accordion on mobile) 📱
- [x] Paystack inline popup integration
- [x] Bank transfer: show account details, create Pending Payment order
- [x] Order confirmation page: thank you message, order number, estimated delivery, invoice download, continue shopping CTA
- [x] Email confirmation automatically sent on successful order

### 2.7 About Page 🎨
- [x] Company story / brand narrative section
- [x] Mission and values
- [x] Stats / milestones (animated counters)
- [x] CTA: Request Quote

### 2.8 Corporate Solutions Page 🎨 📱
- [x] Solutions grid (icon, title, description, CTA per card)
- [x] Industries served section
- [x] How it works (steps / timeline visual)
- [x] Prominent quote request CTA

### 2.9 Contact Page 🎨 📱
- [x] Hero section
- [x] Contact cards (phone, email, office address, WhatsApp)
- [x] Contact form (name, email, phone, subject, message, honeypot spam protection)
- [x] Google Maps embed
- [x] WhatsApp floating sticky button on mobile 📱
- [x] Business hours display
- [x] Success / error toast after form submit

### 2.10 Blog 🎨 📱
> Skill: `seo-fundamentals`

- [x] Blog listing: featured article hero, grid of posts, category sidebar, search
- [x] Blog detail: hero image, reading progress bar, rich content, author, tags, share buttons, related posts
- [x] Category / tag archive pages
- [x] Schema.org Article structured data per post
- [x] Open Graph tags per post
- [x] Estimated reading time display

### 2.11 FAQ Page 🎨
- [x] Categorized accordion FAQ
- [x] Search within FAQs
- [x] Links to contact / request quote from FAQ

### 2.12 Static Pages
- [x] Privacy Policy
- [x] Terms & Conditions
- [x] Shipping Policy
- [x] Return Policy

### 2.13 SEO Infrastructure ⚡
> Skill: `seo-fundamentals`

- [x] Automatic XML sitemap (`/sitemap.xml`, auto-updated on content changes)
- [x] `robots.txt`
- [x] Canonical URLs on every page
- [x] Dynamic meta title + meta description per page / product / category / post
- [x] Open Graph tags (title, description, image)
- [x] Twitter Card tags
- [x] Schema.org markup: Product, BreadcrumbList, Article, Organization, FAQPage
- [x] SEO-friendly URLs for all routes (slugs, no query strings for public pages)
- [x] Single H1 per page enforced

### 2.14 Error Pages 🎨
- [x] 404 page (branded, search bar, links to shop)
- [x] 500 page (branded, friendly message, contact link)
- [x] Maintenance mode page (scheduled downtime notice)

---

## PHASE 1 — Customer Portal

### 3.1 Dashboard 🎨 📱
> Skill: `ui-ux-pro-max` + `mobile-design`

- [x] Personalized welcome message (first name + time of day)
- [x] Overview stat cards: Pending Orders, Completed Orders, Pending Quotes, Wishlist count
- [x] Recent orders table (last 5)
- [x] Pending quotes list
- [x] Recommended products section
- [x] Recently viewed products
- [x] Mobile: card-stack layout, bottom nav bar (Dashboard, Orders, Quotes, Account) 📱

### 3.2 Orders
- [x] Order list (filter by status, date range, search by order number)
- [x] Order detail: vertical timeline stepper (Ordered > Payment Received > Processing > Packaging > Shipped > Delivered)
- [x] Invoice download (PDF via DomPDF, branded)
- [x] Repeat order (add all previous items to cart)
- [x] Cancel order (if eligible — pending/paid only)
- [x] Request support per order

### 3.3 Quotes
- [x] Quote list (tabs: Pending, Reviewed, Approved, Rejected, Expired)
- [x] Quote detail view (products, quantities, pricing, notes)
- [x] Download quote PDF
- [x] Accept quote (one-click, converts to order with payment)
- [x] Reject quote
- [x] Quote messaging thread (customer asks questions, sales responds)

### 3.4 Wishlist
- [x] Saved products grid (product card format)
- [x] Move individual item to cart
- [x] Remove from wishlist
- [x] Share wishlist (unique link)

### 3.5 Address Book
- [x] List saved shipping + billing addresses
- [x] Add / edit / delete address
- [x] Set default shipping address

### 3.6 Downloads
- [x] Invoices, receipts, catalogues, product manuals

### 3.7 Notifications
- [x] List: order updates, quote updates, promotions, announcements
- [x] Mark as read / mark all as read
- [x] Unread count badge in nav

### 3.8 Account Settings
- [x] Update profile (name, phone, avatar upload)
- [x] Change password (current + new + confirm)
- [x] Update email (with confirmation)
- [x] Newsletter preferences toggle
- [x] Delete account (with confirmation modal)

---

## PHASE 1 — Quote Request System

- [x] Request Quote button on: product card (hover), product detail page (primary CTA)
- [x] Quote form: product pre-selected, quantity input, artwork/logo upload, additional notes
- [x] Multi-product quote basket (customers can add multiple products before submitting)
- [x] Contact info pre-filled for logged-in users
- [x] File upload (JPG, PNG, PDF, DOCX — max 20MB per file, MIME validated)
- [x] Submit → success page + instant email confirmation to customer
- [x] Admin email notification on every new quote submission

---

## PHASE 1 — Admin Dashboard

### 4.1 Admin Layout 🎨
> Skill: `ui-ux-pro-max`

- [x] Collapsible sidebar: Dashboard, Products, Categories, Brands, Collections, Corporate Solutions, Orders, Quotes, Customers, Marketing, Content, Reports, Settings, Administration
- [x] Topbar: global search, notifications bell (count badge), user avatar + role chip
- [x] Dashboard KPI cards: Revenue, Orders, Pending Quotes, Products, Customers, Visitors
- [x] Sales overview chart (line — daily/weekly/monthly toggle)
- [x] Revenue chart
- [x] Best selling categories (bar chart)
- [x] Recent activity feed
- [x] Quick actions: Add Product, Create Category, View Orders, New Blog Post, Upload Media
- [x] Role-based sidebar (items hidden by permission)
- [x] Mobile admin: responsive, hamburger-collapsible sidebar 📱

### 4.2 Product Management
- [x] Product list (search, filter by status/category/brand, bulk actions, CSV export, pagination)
- [x] Create/Edit product — tabbed form:
  - Tab 1 General: name, slug (auto-generate + manual override), SKU, barcode, description (rich text), short description, specifications, features, materials, dimensions, weight, MOQ, max quantity
  - Tab 2 Pricing: regular price, sale price, bulk pricing tiers, quote-only toggle, pre-order toggle, tax class
  - Tab 3 Inventory: stock quantity, stock status, low stock alert threshold, availability
  - Tab 4 Media: featured image upload, gallery (drag to reorder, WebP auto-generated)
  - Tab 5 SEO: meta title, meta description, keywords, OG image, canonical URL
  - Tab 6 Related: cross-sell, upsell, accessories (product search + select)
  - Tab 7 Publishing: draft/published/scheduled/hidden/archived, publish date
- [x] Bulk CSV import for products
- [x] Duplicate product shortcut

### 4.3 Categories, Brands, Collections
- [x] Category CRUD (unlimited nesting via parent_id, image, SEO, sort order, status)
- [x] Brand CRUD (logo upload, description, website, featured toggle, SEO)
- [x] Collection CRUD (banner, description, status, product assignment via search)
- [x] Corporate Solutions CRUD (solution landing page, linked products, icon)

### 4.4 Order Management
- [x] Order list (advanced search by order number/customer/email, date filter, payment status filter, delivery status filter)
- [x] Order detail view (customer info, items table, pricing breakdown, timeline, payment details)
- [x] Update order status dropdown (triggers customer notification email automatically)
- [x] Add internal note (visible to admins only)
- [x] Print invoice (PDF download)
- [x] Print packing slip
- [x] Export orders to CSV
- [x] Cancel / refund order

### 4.5 Quote Management
- [x] Quote list (tabs: Pending, Approved, Rejected, Expired, Converted)
- [x] Quote detail (customer info, product list, quantities, artwork files, customer requirements, sales notes field)
- [x] Inline price editing on quote items
- [x] Generate branded PDF quote (DomPDF)
- [x] Send quote to customer by email (with PDF attached)
- [x] Approve / Reject / Expire actions
- [x] Convert approved quote to order (one click)
- [x] Quote messaging thread (sales team replies to customer)

### 4.6 Customer Management
- [x] Customer list (search, filter by status/type, CSV export)
- [x] Customer detail: profile, order history table, quote history, lifetime value, saved addresses, internal notes, support history
- [x] Activate / deactivate account
- [x] Mark as corporate account
- [x] Add internal notes

### 4.7 Media Library
- [x] Folder management (create, rename, delete)
- [x] Bulk drag-and-drop upload
- [x] Auto image compression + WebP generation on upload
- [x] Alt text editor per file
- [x] Search by filename or alt text
- [x] Unused file detection + bulk delete
- [x] Storage usage indicator

### 4.8 Blog CMS
- [x] Post list (search, filter by status/category/author, scheduled posts highlighted)
- [x] Post editor (title, slug auto-generate, excerpt, rich text editor, featured image, categories, tags, author, SEO fields, publishing controls)
- [x] Draft / Publish / Schedule / Archive
- [x] Category + tag CRUD
- [x] Featured article toggle

### 4.9 CMS Section Builder
- [x] Page list (create, edit, delete, duplicate)
- [x] Section-based page builder — admins can add, reorder (drag), hide, schedule, delete:
  - Hero Banner, Logo Cloud, Featured Categories, Product Carousel, Product Grid, Collections, Corporate Solutions, Image+Text, Video Banner, Statistics, Timeline, Testimonials, Team Members, Gallery, FAQ, Newsletter, Instagram Feed, Latest Blog Posts, CTA Banner, Rich Text, Spacer, Divider, Custom HTML (Super Admin only)
- [x] Per-section controls: visibility (desktop/tablet/mobile), schedule (start + end date), background colour, padding, animation, container width, anchor ID
- [x] Page versioning: restore previous version, compare versions, view history
- [x] Draft preview: secure temporary URL, expires automatically
- [x] Homepage builder using same section system
- [x] Unlimited landing pages (same builder)

### 4.10 Navigation Manager
- [x] Header menu (drag to reorder, parent/child nesting)
- [x] Footer menu management
- [x] Mobile menu management
- [x] Fields: title, URL, open-in target, parent item, icon, visibility, sort order, badge label

### 4.11 Content Managers
- [x] Testimonials CRUD (name, company, image, review, star rating, featured, status)
- [x] FAQ CRUD (category, question, answer, sort order, status)
- [x] Announcement manager (message, colours, button, schedule, priority, dismissible toggle)
- [x] Popup manager (title, description, image, button, trigger type, delay, frequency, devices)

### 4.12 Newsletter
- [x] Subscriber list (search, export CSV)
- [x] Manual campaign send (subject, HTML body, preview)
- [x] Unsubscribe management + GDPR compliance
- [x] Duplicate detection on subscribe

### 4.13 Marketing
- [x] Coupon CRUD (percentage, fixed amount, min spend, expiry date, usage limit, customer restriction)
- [x] Coupon usage tracking per order
- [x] Reviews management (approve, reject, reply, feature, spam detection)

### 4.14 Reports 📊
> Skill: `analytics-tracking`

- [x] Revenue report (by day / week / month / year, chart + table)
- [x] Sales report (orders, values, trends)
- [x] Orders report (status breakdown, average order value)
- [x] Customers report (new vs returning, LTV, top customers)
- [x] Quotes report (approval rate, conversion rate, average quote value)
- [x] Products report (top sellers, slow movers, stock value)
- [x] Category performance
- [x] Marketing report (coupons used, newsletter signups, conversion)
- [x] CSV export on all reports
- [x] PDF export on all reports

### 4.15 Settings
- [x] General: company name, logo, favicon, address, phone, email, timezone, currency symbol
- [x] SMTP: host, port, username, password, from name, from email
- [x] Payment: Paystack public key, Paystack secret key
- [x] Tax: rate percentage, price display (inclusive/exclusive)
- [x] Shipping: methods CRUD (name, price, estimated days, status)
- [x] Integrations: Google Analytics ID, Meta Pixel ID, WhatsApp number
- [x] Maintenance mode toggle (with custom message)
- [x] Footer copyright text

### 4.16 User & Role Management 🔒
- [x] Admin user list (search, filter by role/status)
- [x] Create / edit / deactivate admin user
- [x] Role assignment per user
- [x] Permission matrix editor per role (checkbox grid: module x action)
- [x] Audit log viewer (who did what action, on which record, when)

---

## PHASE 1 — Email Notification System

> All emails: branded HTML templates, dark luxury design, Marigold logo, gold accents

- [x] Welcome email (new customer registration)
- [x] Order confirmation email (customer — immediate on payment)
- [x] Payment received email (customer)
- [x] Order status update email (customer — fires on each status change)
- [x] Invoice email with PDF attachment
- [x] Quote request confirmation (customer — instant acknowledgement)
- [x] Quote sent to customer (with PDF quote attached)
- [x] Quote accepted notification (customer)
- [x] Quote rejected notification (customer)
- [x] Password reset email (secure token link, 1-hour expiry)
- [x] New order alert (admin team)
- [x] New quote request alert (admin team)
- [x] Low stock alert (admin — fires at threshold)
- [x] New review notification (admin)
- [x] Contact form submission alert (admin)
- [x] PHPMailer SMTP integration with HTML + text fallback

---

## PHASE 1 — Search Module

- [x] Instant search AJAX endpoint (no page reload, debounced 300ms)
- [x] Results grouped: Products, Categories, Blog Articles
- [x] Recent searches (stored in localStorage)
- [x] Popular searches (tracked in DB)
- [x] No results state with suggestions
- [x] Full search results page with filters
- [x] MySQL LIKE queries with indexed columns (Phase 1)
- [x] Future: full-text search + synonyms (Phase 2)

---

## PHASE 1 — Performance & Security ⚡ 🔒

### Performance
> Skill: `web-performance-optimization`

- [x] File-based caching: homepage, categories, products, settings, menus, footer
- [x] Cache auto-invalidated on content updates
- [x] Native lazy loading on all images (loading=lazy)
- [x] WebP images served automatically (fallback to original)
- [x] Responsive images with srcset
- [x] GSAP: transform and opacity only (no layout properties), 60fps target
- [x] Disable heavy animations on low-end devices (hardware concurrency check)
- [x] Minify CSS + JS for production
- [x] Google Fonts preconnect + display=swap
- [x] Lighthouse targets: Performance 95+, Accessibility 100, SEO 100, Best Practices 100
- [x] Core Web Vitals: LCP < 2.5s, CLS < 0.1, INP < 200ms

### Security 🔒
- [x] bcrypt password hashing (never MD5, never SHA1)
- [x] PDO prepared statements on every database query (zero raw SQL)
- [x] CSRF token required on all state-changing forms
- [x] XSS protection: htmlspecialchars on all output
- [x] Rate limiting: login, register, contact form, newsletter, quote submit
- [x] Security headers in .htaccess: X-Frame-Options DENY, X-Content-Type-Options nosniff, Referrer-Policy, CSP
- [x] File uploads: MIME type validation, extension whitelist, max 20MB, images re-processed (strip EXIF)
- [x] Admin routes: auth middleware + permission check on every request
- [x] Customer data isolation: queries always scoped to authenticated user ID
- [x] Session: secure + httpOnly cookies, regenerate ID on privilege change, timeout after inactivity
- [x] Audit logs for all admin write actions

---

## PHASE 1 — Mobile App Experience 📱
> Skill: `mobile-design`

- [x] Bottom navigation bar (Home, Shop, Wishlist, Account, Cart) — persistent, app-like 📱
- [x] Swipe left/right on image galleries 📱
- [x] Swipe on product carousels (Swiper.js touch support) 📱
- [x] Sticky bottom CTA bar on product detail page (Add to Cart + Request Quote) 📱
- [x] Filter drawer slides up from bottom (sheet pattern, handle bar) 📱
- [x] Cart drawer accessible from bottom nav 📱
- [x] Smooth momentum scroll (Lenis)
- [x] No unintentional horizontal scroll on any page
- [x] Input font-size minimum 16px everywhere (prevents iOS zoom)
- [x] All tap targets minimum 44x44px
- [x] PWA manifest.json (name, icons, theme_color, display standalone)
- [x] Service worker (offline fallback for static pages)
- [x] Add to Home Screen prompt (mobile browsers)

---

## PHASE 1 — QA & Testing Checklist

- [x] Mobile viewport tests: 320px, 375px, 414px, 428px
- [x] Tablet viewport tests: 768px, 1024px
- [x] Desktop viewport tests: 1280px, 1440px, 1920px
- [x] Browser: Chrome, Firefox, Safari, Edge (latest 2 versions each)
- [x] Full checkout flow: guest (Paystack + bank transfer)
- [x] Full checkout flow: registered customer
- [x] Quote request flow: submit -> admin review -> PDF -> customer accept -> order created
- [x] All 15+ email templates (send to real inbox, verify HTML rendering)
- [x] Lighthouse audit on homepage (target 95+ performance)
- [x] WAVE accessibility audit on all public pages
- [x] Keyboard navigation on all forms
- [x] Screen reader test (ARIA labels, roles, live regions)
- [x] Contrast ratio check (minimum 4.5:1 on all text)
- [x] prefers-reduced-motion: all animations disabled correctly
- [x] All admin CRUD operations (create, read, update, delete, export)
- [x] Role-based access: each role can only access permitted modules
- [x] CSRF: remove token from form, verify 403 response
- [x] File upload: test invalid MIME type rejection, max size enforcement
- [x] Image WebP generation on upload
- [x] Cache invalidation after product / page update

---

## PHASE 2 — Business Management Platform (Future)

> Build after Phase 1 is live and stable in production

- [ ] Corporate Accounts (company profiles, multi-user, preferred pricing tiers, credit limits, assigned account manager)
- [ ] CRM pipeline: Lead -> Qualified -> Quotation -> Negotiation -> Won -> Customer
- [ ] CRM activities: Call, Meeting, Email, WhatsApp, Visit, Task, Reminder
- [ ] Inventory Management (stock transactions, low stock alerts, fast / slow / dead stock reports)
- [ ] Warehouse Management (storage locations, bins, transfers, receiving, dispatch, cycle count, barcode + QR)
- [ ] Production Management (artwork -> mockup -> customer approval -> production queue -> quality check -> dispatch)
- [ ] Artwork Approval Workflow (multi-version, comments, PDF approval, history, notifications)
- [ ] Delivery Management (courier, tracking number, dispatch date, proof of delivery)
- [ ] Supplier Management (purchase orders, supplier ratings, delivery time tracking)
- [ ] Email Marketing Campaigns (Mailchimp / Brevo integration)
- [ ] Customer Loyalty Program (reward points, Silver / Gold / Platinum membership levels)
- [ ] Support Ticketing System (departments, priorities, attachments, knowledge base)
- [ ] Advanced Automation (quote expiry, low stock alerts, recurring reports, scheduled backups)
- [ ] SMS Notifications integration
- [ ] Advanced multi-module Reporting (sales, inventory, production, finance, CRM)
- [ ] 2FA for all admin accounts

---

## PHASE 3 — Enterprise & AI Platform (Future)

> Long-term vision, builds on Phase 1 + 2 without architectural changes

- [ ] AI-powered product recommendations (collaborative filtering)
- [ ] AI semantic search + voice search
- [ ] AI quote pricing suggestions (based on historical data)
- [ ] Intelligent inventory forecasting
- [ ] Automated reorder triggers
- [ ] Predictive sales analytics
- [ ] Multi-company / white-label support
- [ ] Full REST API (mobile apps + third-party integrations)
- [ ] iOS + Android native apps
- [ ] Cloud VPS migration with Redis, CDN, load balancer

---

## SKILLS REFERENCE MAP

| Area | Primary Skill |
|------|--------------|
| UI/UX Design & All Components | `ui-ux-pro-max` |
| PHP Backend / MVC Framework | `php-pro` |
| Database Schema & Optimization | `database-design` |
| Tailwind CSS Design System | `tailwind-design-system` |
| Mobile-First / App-Like UI | `mobile-design` |
| Scroll & GSAP Animations | `scroll-experience` |
| SEO Implementation | `seo-fundamentals` |
| Paystack Payment Integration | `payment-integration` |
| Performance & Web Vitals | `web-performance-optimization` |
| Analytics & Reporting | `analytics-tracking` |
| Security Audit | `security-auditor` |
| PHP Patterns Reference | `laravel-expert` |

---

*Last Updated: July 2026 — Phase 1 Active*
*Project: Marigold Signature Nigeria Limited — Premium Corporate Merchandise Platform*
