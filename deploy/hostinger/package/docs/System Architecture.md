# 20. System Architecture

## Overview

The Marigold Commerce Platform shall be developed as a modular MVC application using Core PHP.

The application must be lightweight enough to run efficiently on shared hosting while maintaining an architecture that supports future enterprise expansion.

The codebase should separate:

- Business Logic
- Presentation
- Database
- Services
- Utilities
- Modules

No SQL queries should exist inside views.

No HTML should exist inside controllers.

No business logic should exist inside templates.

---

# Architectural Principles

The system should follow:

Single Responsibility Principle

Open/Closed Principle

Dependency Injection where appropriate

Repository Pattern (for large modules)

Service Layer

Reusable Components

Configuration Driven Development

---

# Technology Stack

Backend

Core PHP 8.3+

Frontend

HTML5

Tailwind CSS

Alpine.js

GSAP

Swiper.js

Database

MySQL 8

Caching

File Cache

Optional Redis (Future)

Queue

Database Queue (Future)

Email

SMTP

PHPMailer

PDF

DomPDF

Images

Intervention Image

---

# Project Structure

/public

Application entry point.

Contains only public assets.

```
public/

index.php

assets/

uploads/

robots.txt

favicon.ico
```

---

/app

Application logic.

```
app/

Controllers/

Models/

Services/

Repositories/

Helpers/

Middleware/

Policies/

Traits/

Validators/

Events/

Listeners/

Mail/

Notifications/

View/

Core/

Config/

Routes/
```

---

/storage

```
storage/

logs/

cache/

exports/

imports/

invoices/

quotes/

tmp/

sessions/
```

---

/database

```
database/

migrations/

seeders/

backups/
```

---

/vendor

Composer packages.

---

# MVC Flow

Browser

↓

Router

↓

Controller

↓

Service

↓

Repository

↓

Database

↓

Repository

↓

Controller

↓

View

↓

Browser

---

# Modules

Every major feature becomes its own module.

Examples

Authentication

Products

Orders

Customers

Quotes

CMS

Blog

Inventory

Reports

Marketing

Settings

Users

Notifications

Search

Each module owns:

Controller

Model

Views

Routes

Policies

Services

Validation

---

# Routing

Human readable URLs.

Examples

/

shop

/category/drinkware

/product/executive-notebook

/cart

/checkout

/blog

/blog/how-to-choose-corporate-gifts

/contact

/about

/request-quote

/customer/dashboard

/admin/products

/admin/orders

---

# Configuration

Never hardcode.

Everything configurable.

Examples

Currency

Company Name

Tax Rate

SMTP

Payment Keys

Google Analytics

Meta Pixel

Maintenance Mode

Logo

Contact Details

---

# Environment Variables

APP_NAME

APP_URL

APP_ENV

DB_HOST

DB_NAME

DB_USER

DB_PASS

SMTP_HOST

SMTP_PORT

SMTP_USER

SMTP_PASSWORD

PAYSTACK_SECRET

PAYSTACK_PUBLIC

---

# Service Layer

Business logic belongs here.

Example

OrderService

Functions

Create Order

Update Order

Calculate Totals

Apply Coupons

Generate Invoice

Send Confirmation

---

QuoteService

Create Quote

Generate PDF

Approve Quote

Reject Quote

Convert To Order

---

ProductService

Create Product

Update Product

Bulk Import

Inventory Update

Search

Recommendations

---

# Repository Layer

Purpose

Keep database logic separate.

Example

ProductRepository

find()

findBySlug()

search()

featured()

related()

newArrivals()

---

# Validation

Every request validated.

Server-side.

Client-side enhancement.

Never trust browser validation.

---

# Error Handling

Friendly messages.

Detailed logs.

Custom exception handler.

Automatic logging.

Error IDs.

---

# Logging

Application Logs

Payment Logs

Email Logs

Authentication Logs

System Logs

API Logs (Future)

---

# Session Management

Secure Sessions

Regenerate IDs

CSRF Protection

Session Timeout

Remember Me

---

# Authentication

Customer

Email + Password

Google Login (Future)

OTP (Future)

Admin

Email + Password

2FA (Phase Two)

---

# Authorization

Role Based Access Control

Permissions

View

Create

Edit

Delete

Approve

Export

Manage Users

Every module checks permissions.

---

# Security

Passwords

bcrypt

Prepared Statements

PDO

CSRF Tokens

Required

XSS Protection

Output Escaping

Rate Limiting

Login Attempts

Security Headers

Enabled

File Upload Validation

Required

MIME Validation

Required

Image Processing

Required

---

# Caching

Homepage

Categories

Products

Settings

Menus

Blog

Footer

All cache automatically clears after updates.

---

# Image Processing

Automatic Compression

Responsive Sizes

WebP Generation

Thumbnail Creation

Lazy Loading

---

# File Upload Rules

Images

JPG

PNG

WEBP

Documents

PDF

DOCX

XLSX

Maximum

20MB

Virus Scan (Future)

---

# Search Engine

Phase One

LIKE Queries

Indexed Columns

Category Search

Brand Search

Product Search

Blog Search

Phase Two

Full-text Search

Search Suggestions

Popular Searches

Synonyms

---

# API Architecture

Although Phase One is server-rendered, every major module should expose service methods that can later be wrapped by REST endpoints.

This allows future mobile apps without rewriting business logic.

Future Endpoints

/api/products

/api/orders

/api/customers

/api/quotes

/api/blog

/api/auth

---

# Database Naming Convention

snake_case

Examples

product_images

order_items

customer_addresses

quote_requests

---

# Coding Standards

PSR-12

Namespaces

Composer Autoloading

Strict Typing

Reusable Traits

Reusable Components

---

# Performance Targets

Homepage

< 2 seconds

Product Page

< 2 seconds

Dashboard

< 2 seconds

Database Queries

< 100ms average

Image Optimization

Automatic

Core Web Vitals

90+

---

# Scalability Strategy

Phase One

Shared Hosting

↓

Phase Two

Cloud VPS

↓

Phase Three

Load Balancer

↓

Object Storage

↓

Redis

↓

Queue Workers

↓

Microservices (Only If Required)

The application should never require rewriting to support this progression.

---

# Deployment

Development

Laragon

↓

GitHub

↓

Production

Hostinger Shared Hosting

↓

GitHub Actions (Future)

↓

Zero Downtime Deployment (Future)

---

# Backups

Automatic Database Backup

Manual Backup

Restore System

Export Settings

---

End of Part 6