# PART 8 — Database Design & Data Architecture

## Objective

Design a normalized, scalable, and high-performance relational database that powers every feature of the Marigold Commerce Platform.

The schema must:

- Support all Phase 1 functionality.
- Scale into Phase 2 without restructuring.
- Support enterprise features in Phase 3.
- Remain optimized for shared hosting.
- Be migration-friendly.

---

# Database Engine

MySQL 8+

Storage Engine

InnoDB

Character Set

utf8mb4

Collation

utf8mb4_unicode_ci

Timezone

UTC

Primary Keys

BIGINT UNSIGNED AUTO_INCREMENT

Foreign Keys

Required wherever relationships exist.

Soft Deletes

Supported where appropriate.

---

# Database Modules

Authentication

Users

Roles

Permissions

Customers

Products

Categories

Brands

Collections

Orders

Quotes

Inventory

CMS

Blog

Marketing

SEO

Media

Notifications

Settings

Reports

Logs

---

# Authentication Module

## users

Stores all authenticated users.

Fields

id

uuid

first_name

last_name

email

phone

password

avatar

status

email_verified_at

last_login_at

remember_token

created_at

updated_at

deleted_at

Indexes

email

phone

status

---

## roles

Examples

Super Admin

Administrator

Sales

Inventory

Marketing

Support

Finance

Customer

Fields

id

name

slug

description

created_at

updated_at

---

## permissions

Examples

products.create

products.edit

orders.view

quotes.approve

users.manage

Fields

id

module

name

slug

description

---

## role_permissions

Many-to-many relationship.

role_id

permission_id

---

## user_roles

user_id

role_id

---

# Customer Module

## customers

Stores customer-specific information.

Fields

id

user_id

company_name

industry

tax_number

website

default_shipping_address

default_billing_address

notes

status

created_at

updated_at

---

## customer_addresses

Fields

id

customer_id

type

country

state

city

address_line_1

address_line_2

postal_code

phone

is_default

---

# Product Module

## products

Core product table.

Fields

id

uuid

sku

barcode

name

slug

short_description

description

brand_id

category_id

collection_id

status

product_type

price

sale_price

cost_price

stock_quantity

minimum_order_quantity

maximum_order_quantity

weight

length

width

height

is_featured

is_new

is_best_seller

meta_title

meta_description

created_at

updated_at

deleted_at

Indexes

slug

sku

status

price

---

## product_images

id

product_id

image

alt_text

sort_order

is_featured

---

## product_variants

id

product_id

variant_name

sku

price

stock

---

## product_attributes

Examples

Colour

Material

Size

Capacity

Fields

id

name

slug

type

---

## product_attribute_values

attribute_id

value

---

## product_variant_attributes

variant_id

attribute_value_id

---

# Category Module

## categories

id

parent_id

name

slug

description

image

icon

status

sort_order

meta_title

meta_description

---

# Brands

## brands

id

name

slug

logo

website

description

status

featured

---

# Collections

## collections

Examples

Executive Gifts

Christmas Collection

Conference Essentials

Fields

id

name

slug

banner

description

status

---

# Wishlist

## wishlists

customer_id

product_id

created_at

---

# Cart

## carts

customer_id

session_id

expires_at

---

## cart_items

cart_id

product_id

variant_id

quantity

price

---

# Orders

## orders

Fields

id

order_number

customer_id

status

payment_status

shipping_status

subtotal

discount

tax

shipping

grand_total

payment_method

transaction_reference

notes

created_at

updated_at

---

## order_items

order_id

product_id

variant_id

quantity

price

subtotal

---

## order_status_history

order_id

status

comment

updated_by

created_at

---

# Quote Module

## quotes

quote_number

customer_id

status

subtotal

discount

tax

grand_total

expiry_date

notes

sales_person

---

## quote_items

quote_id

product_id

quantity

price

---

## quote_messages

conversation between sales team and customer.

---

## quote_files

Artwork

Logos

Reference documents

PDF

---

# Payment Module

## payments

order_id

gateway

reference

amount

status

response

paid_at

---

# Shipping Module

## shipping_methods

Standard

Express

Pickup

---

## shipments

order_id

tracking_number

carrier

status

dispatched_at

delivered_at

---

# CMS Module

## pages

id

title

slug

template

status

seo

published_at

---

## page_sections

Stores JSON configuration.

Fields

page_id

section_type

sort_order

configuration_json

status

---

# Blog Module

## posts

title

slug

excerpt

content

author_id

featured_image

status

published_at

---

## post_categories

---

## post_tags

---

# Media Module

## media

filename

path

mime_type

size

width

height

alt_text

uploaded_by

---

# SEO Module

## seo_redirects

source_url

destination_url

status_code

---

## seo_meta

page_type

page_id

meta_title

meta_description

schema_json

---

# Marketing Module

## coupons

code

type

value

minimum_spend

usage_limit

expires_at

---

## newsletter_subscribers

email

status

subscribed_at

---

# Notification Module

## notifications

title

message

user_id

type

read_at

---

# Contact Module

## contact_messages

name

email

phone

subject

message

status

---

# FAQ Module

## faqs

question

answer

category

sort_order

status

---

# Testimonials

## testimonials

customer_name

company

position

photo

rating

testimonial

featured

---

# Settings

## settings

key

value

type

group

autoload

---

# Activity Logs

## activity_logs

user_id

module

action

description

ip_address

user_agent

created_at

---

# Audit Logs

Stores critical system changes.

Cannot be deleted.

---

# Search Strategy

Indexes

slug

email

sku

order_number

quote_number

status

created_at

Full-text indexes (Phase 2)

Product Name

Description

Blog Content

---

# Relationships

Users

↓

Customers

↓

Orders

↓

Order Items

↓

Products

↓

Categories

↓

Brands

↓

Collections

Quotes follow a similar structure.

---

# Database Optimization

Use foreign keys.

Use indexed lookup fields.

Avoid N+1 queries.

Limit SELECT *.

Paginate all large datasets.

Use transactions for:

Orders

Payments

Quotes

Inventory updates

---

# Migration Strategy

Every table created using versioned migration files.

Never edit production tables manually.

Schema changes must always be tracked.

---

# Future Tables (Phase 2 & 3)

inventory_transactions

warehouses

stock_adjustments

production_jobs

production_stages

artwork_approvals

crm_leads

crm_tasks

support_tickets

reward_points

suppliers

supplier_products

api_tokens

webhooks

ai_conversations

analytics_events

mobile_devices

audit_exports

These tables are intentionally excluded from Phase 1 migrations but reserved in the architecture.

---

# Acceptance Criteria

✓ Third Normal Form (3NF) where appropriate.

✓ All foreign key relationships documented.

✓ Indexed search fields.

✓ Soft delete support where required.

✓ UUID support for public-facing entities.

✓ Migration-based schema management.

✓ Shared-hosting compatible while ready for future enterprise expansion.

---

End of Part 8