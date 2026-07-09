# PART 9 — Phase One Functional Specification

---

# Phase One Overview

## Objective

Deliver a production-ready ecommerce platform that enables Marigold to:

- Sell products online.
- Receive quotation requests.
- Manage products and content.
- Accept online payments.
- Manage customers.
- Manage orders.
- Publish content.
- Scale into future releases without rebuilding.

---

# MVP Scope

The MVP intentionally excludes:

- AI Features
- Warehouse Management
- Production Tracking
- Supplier Management
- CRM
- Mobile Apps
- Multi-company Support
- Loyalty Programs
- Artwork Approval Workflow
- Live Personalization

These are reserved for later phases.

---

# Module 1 — Authentication

## Objective

Allow customers and administrators to securely access the platform.

---

## User Stories

As a customer,

I want to register,

So I can place and track orders.

---

As a returning customer,

I want to log in,

So I can view my account.

---

As an administrator,

I want secure access,

So unauthorized users cannot manage the website.

---

## Functional Requirements

Customer Registration

Customer Login

Forgot Password

Reset Password

Logout

Remember Me

Email Verification (optional in MVP)

---

## Validation

Email unique.

Password minimum 8 characters.

Phone optional.

Name required.

---

## Business Rules

Customers cannot access Admin.

Admins cannot access Customer Dashboard.

Inactive users cannot log in.

---

## Acceptance Criteria

✓ Register successfully

✓ Login

✓ Reset password

✓ Logout

✓ Session expires correctly

---

# Module 2 — Product Catalogue

## Objective

Allow customers to browse products.

---

## Functional Requirements

Categories

Brands

Collections

Filters

Sorting

Pagination

Search

Featured Products

Related Products

Recently Viewed

---

## Product Types

Standard Product

Pre-order

Quote Only

---

## Business Rules

Pre-order

Cannot display "In Stock"

Must display expected lead time.

---

Quote Only

Cannot checkout directly.

Must display Request Quote.

---

Standard Product

Can purchase immediately.

---

## Product Status

Published

Draft

Archived

Hidden

---

## Acceptance Criteria

✓ Products searchable

✓ Filters work

✓ Pagination works

✓ Category URLs SEO friendly

✓ Mobile responsive

---

# Module 3 — Product Details

## Functional Requirements

Gallery

Multiple Images

Zoom

Description

Specifications

Price

SKU

Availability

Quantity

Wishlist

Share

Request Quote

Add to Cart

Related Products

---

## Business Rules

Out of Stock

Disable checkout.

Allow wishlist.

---

Pre-order

Allow purchase.

Display delivery estimate.

---

Quote Only

Hide Add to Cart.

Display Request Quote.

---

## Validation

Quantity

Must respect:

Minimum Order Quantity

Maximum Order Quantity

Available Stock

---

# Module 4 — Shopping Cart

## Functional Requirements

Add Product

Remove Product

Update Quantity

Coupon

Shipping Estimate

Mini Cart

Persistent Cart

Guest Cart

---

## Business Rules

Cart updates automatically.

Invalid coupons rejected.

Expired coupons rejected.

---

## Acceptance Criteria

✓ Cart survives refresh

✓ Quantity updates

✓ Totals recalculate instantly

✓ Coupons apply correctly

---

# Module 5 — Checkout

## Functional Requirements

Guest Checkout

Registered Checkout

Shipping Address

Billing Address

Payment

Order Notes

Review

Confirmation

---

## Payment Methods

Paystack

Bank Transfer

---

## Validation

Required fields.

Email format.

Phone format.

Payment confirmation.

---

## Business Rules

Orders only created after successful payment.

Bank transfer creates Pending Payment order.

---

## Acceptance Criteria

✓ Checkout completed

✓ Invoice generated

✓ Email sent

✓ Order appears in dashboard

---

# Module 6 — Orders

## Statuses

Pending

Paid

Processing

Packaging

Shipped

Delivered

Cancelled

Refunded

---

## Functional Requirements

View Order

Timeline

Invoice

Print

Email

Reorder

---

## Business Rules

Cancelled orders cannot be edited.

Delivered orders become read-only.

---

# Module 7 — Quote Requests

## Objective

Enable customers to request quotations instead of immediate purchases.

---

## Functional Requirements

Request Quote

Upload Requirements

Add Notes

Select Quantity

Submit

Receive Confirmation

---

## Admin Features

View Quotes

Edit Prices

Generate PDF

Approve

Reject

Expire

Convert to Order

---

## Customer Features

View Quotes

Download PDF

Accept

Reject

Message Sales

---

## Validation

Minimum quantity.

Contact information.

Product selection.

---

## Acceptance Criteria

✓ Quote submitted

✓ Admin notified

✓ Customer receives confirmation

✓ PDF generated

---

# Module 8 — Customer Dashboard

## Features

Dashboard

Orders

Quotes

Wishlist

Addresses

Downloads

Notifications

Settings

---

## Business Rules

Customer only sees own data.

Cannot access other accounts.

---

# Module 9 — Blog

## Functional Requirements

Categories

Tags

Search

Related Posts

SEO

Sharing

Featured Articles

---

## Admin

Create

Edit

Delete

Schedule

Draft

Publish

---

# Module 10 — Contact

## Functional Requirements

Contact Form

WhatsApp

Google Maps

Business Hours

Departments

---

## Validation

Required fields.

Spam protection.

---

# Module 11 — Newsletter

## Functional Requirements

Subscribe

Unsubscribe

Export Subscribers

Duplicate Detection

---

# Module 12 — CMS

## Functional Requirements

Pages

Section Builder

Homepage Builder

Landing Pages

Media

Navigation

SEO

Footer

Announcement Bar

---

# Module 13 — Search

## Features

Product Search

Category Search

Brand Search

Blog Search

Instant Results

---

## Future

AI Search

Semantic Search

Voice Search

---

# Module 14 — Reviews

## Features

Write Review

Rating

Photos (Future)

Admin Approval

Reply

---

## Validation

Verified Purchase Only

One Review Per Product

---

# Module 15 — Wishlist

## Features

Save Product

Remove

Move to Cart

Persistent

---

# Module 16 — Notifications

## Customer

Order Updates

Quote Updates

Password Changes

Newsletter

---

## Admin

New Orders

New Quotes

Low Stock

New Reviews

Contact Messages

---

# Module 17 — SEO

Automatic Sitemap

Robots

Canonical URLs

Meta Tags

Open Graph

Structured Data

Breadcrumbs

Schema.org

---

# Module 18 — Analytics

Dashboard Metrics

Orders

Sales

Revenue

Visitors

Top Products

Top Categories

Top Customers

---

# Module 19 — Reports

Sales

Orders

Products

Customers

Quotes

Revenue

Export CSV

Export PDF

---

# Module 20 — Settings

Company

Payments

Shipping

Emails

Taxes

Social Media

Theme

Maintenance

SMTP

Google Analytics

Meta Pixel

---

# Global Business Rules

Products cannot be deleted if referenced by completed orders.

Categories cannot be deleted while containing products.

Orders cannot be modified after delivery.

Quotes expire automatically after configured duration.

Email notifications are queued when possible.

All uploaded files are validated.

Every admin action is logged.

Every customer action updates activity history.

---

# Non-Functional Requirements

Maximum page load:

2 seconds

Maximum dashboard load:

2 seconds

Responsive:

100%

Accessibility:

WCAG AA

SEO:

95+ Lighthouse

Performance:

90+ Lighthouse

Security:

OWASP Top 10 compliant

---

# Phase One Acceptance Criteria

The MVP is considered complete when:

✓ Customers can register and log in.

✓ Products can be managed through the admin.

✓ Customers can browse, search and filter products.

✓ Orders can be placed and paid for.

✓ Quote requests can be submitted and managed.

✓ Content can be managed through the CMS.

✓ Blog is operational.

✓ Customer dashboard is functional.

✓ Admin dashboard manages all Phase One modules.

✓ Website is fully responsive.

✓ SEO fundamentals are implemented.

✓ Platform is deployable on Hostinger Shared Hosting without modification.

---

End of Part 9