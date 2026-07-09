# PART 7 — CMS & Dynamic Section Builder

## Objective

The Content Management System (CMS) should empower Marigold's team to manage the entire website—from homepage content to landing pages, menus, blogs, SEO, and media—without writing code.

Unlike traditional page builders, the CMS should use a **Section Builder** approach.

This ensures:
- Design consistency.
- Better performance.
- Cleaner HTML.
- Easier maintenance.
- Future scalability.

---

# CMS Philosophy

The CMS should feel like building with LEGO blocks.

Instead of dragging arbitrary elements anywhere, administrators build pages using predefined sections that follow the Marigold design system.

Every section should:

- Be reusable.
- Be reorderable.
- Be responsive.
- Be configurable.
- Support scheduling.
- Support draft mode.
- Support SEO.

---

# CMS Modules

The CMS consists of the following modules:

Dashboard

↓

Pages

↓

Section Builder

↓

Media Library

↓

Navigation

↓

Footer

↓

Blog

↓

SEO

↓

Forms

↓

Announcements

↓

Testimonials

↓

FAQs

↓

Settings

---

# Dashboard

Purpose

Provide an overview of website content.

Widgets

Published Pages

Draft Pages

Recent Blog Posts

Media Usage

SEO Warnings

Broken Links (Future)

Scheduled Posts

Storage Usage

Quick Actions

Create Page

Upload Media

New Blog Post

Edit Homepage

---

# Pages

Administrators should manage all website pages.

Fields

Page Title

Slug

Meta Title

Meta Description

Featured Image

Status

Author

Publish Date

Visibility

Page Template

SEO Settings

Open Graph

Schema Type

Canonical URL

Robots Directive

---

Status Options

Draft

Published

Scheduled

Archived

Private

---

Page Templates

Default

Landing Page

Blog Listing

Contact

About

Solutions

Collections

Custom

---

# Section Builder

## Overview

Each page consists of one or more reusable sections.

Sections are stacked vertically.

Administrators can:

Add

Duplicate

Delete

Reorder

Hide

Schedule

Preview

---

## Available Sections

Hero Banner

Logo Cloud

Featured Categories

Product Carousel

Product Grid

Collections

Corporate Solutions

Image + Text

Video Banner

Statistics

Timeline

Testimonials

Team Members

Gallery

FAQ

Newsletter

Instagram Feed

Latest Blog Posts

CTA Banner

Rich Text

Spacer

Divider

Custom HTML (Admins Only)

---

# Hero Section

Fields

Headline

Subheading

Background Image

Overlay

Alignment

Button One

Button Two

Height

Animation

Visibility

---

# Product Carousel

Fields

Title

Subtitle

Product Source

Manual

Latest

Featured

Category

Brand

Limit

Autoplay

Navigation

Button

---

# Product Grid

Fields

Title

Category

Columns

Rows

Sorting

CTA

---

# Collections

Fields

Collection

Background

Title

Button

Display Style

---

# Image + Content

Fields

Image

Heading

Content

Button

Image Position

Background Colour

Animation

---

# Statistics

Fields

Icon

Number

Label

Suffix

Animation

---

# Testimonials

Fields

Title

Manual Selection

Automatic

Number

Autoplay

Style

---

# FAQ

Fields

Category

Accordion

Search

Expanded Item

---

# CTA Banner

Fields

Heading

Description

Background

Button

Overlay

Animation

---

# Newsletter

Fields

Heading

Description

Placeholder

Button

Success Message

---

# Rich Text

Supports

Headings

Paragraphs

Lists

Tables

Links

Images

Video Embeds

Code Blocks (Admin)

---

# Section Controls

Every section should support

Visibility

Desktop

Tablet

Mobile

Scheduling

Start Date

End Date

Background Colour

Padding

Animation

Container Width

Anchor ID

Custom Class

---

# Page Versioning

Every page keeps versions.

Administrator can

Restore Previous Version

Compare Versions

View History

Duplicate Version

---

# Draft Preview

Editors should preview unpublished changes before publishing.

Preview URL

Temporary

Secure

Expires Automatically

---

# Homepage Builder

Homepage is built using sections.

Default Order

Announcement

↓

Hero

↓

Logo Cloud

↓

Categories

↓

Solutions

↓

Featured Products

↓

Collections

↓

Testimonials

↓

Instagram

↓

Blog

↓

Newsletter

↓

Footer

Every section can be reordered.

---

# Landing Pages

Marketing can build unlimited landing pages.

Examples

Christmas Gifts

Corporate Welcome Kits

Conference Essentials

Back To School

Promotional Campaigns

Employee Recognition

Each landing page uses the same section builder.

---

# Navigation Manager

Administrators manage menus visually.

Header Menu

Footer Menu

Mobile Menu

Mega Menu (Phase 2)

Fields

Title

URL

Target

Parent

Icon

Visibility

Order

Badge

---

# Footer Builder

Footer consists of widgets.

Widgets

Company Info

Quick Links

Categories

Newsletter

Social Links

Payment Icons

Copyright

Legal

Editable from CMS.

---

# Announcement Manager

Purpose

Display promotional announcements.

Examples

Free Shipping

Holiday Notice

Promotions

Maintenance

Fields

Message

Background Colour

Text Colour

Button

Link

Schedule

Priority

Dismissible

---

# Popup Manager

Purpose

Marketing campaigns.

Examples

Newsletter

Coupon

Announcement

Product Launch

Fields

Title

Description

Image

Button

Trigger

Delay

Frequency

Devices

Exit Intent (Phase 2)

---

# Media Library

Supports

Images

PDF

Videos

Documents

Folders

Bulk Upload

Search

Compression

Automatic WebP

Alt Text

Usage Tracking

Unused File Detection

---

# Blog CMS

Fields

Title

Slug

Category

Tags

Excerpt

Content

Featured Image

Gallery

SEO

Author

Reading Time

Related Articles

Status

Publish Date

---

# SEO Manager

Global Settings

Homepage SEO

Default Meta

Default Image

Organization Schema

Social Links

Google Verification

Bing Verification

Sitemap

Robots

Redirects

Canonical Rules

---

# Forms Manager

Purpose

Centralize every form.

Examples

Contact Form

Quote Form

Newsletter

Support

Custom Forms

Each form should support

Validation

Spam Protection

Email Notifications

Submission Export

CSV

Status

---

# Testimonials Manager

Fields

Customer

Company

Position

Rating

Photo

Quote

Featured

Status

---

# FAQ Manager

Categories

Search

Ordering

Status

Accordion

---

# Banner Manager

Create reusable banners.

Examples

Homepage Hero

Category Hero

Collection Banner

Sale Banner

Holiday Banner

Fields

Image

Headline

Subtitle

Button

Schedule

Priority

Animation

---

# Theme Settings

Administrators should configure:

Logo

Favicon

Colours

Typography

Buttons

Social Links

Business Hours

Emails

Phone Numbers

Addresses

Currency

Measurement Units

Tax Settings

---

# Content Scheduling

Every content type supports scheduling.

Publish Date

Expiry Date

Timezone

Automatic Publishing

Automatic Archive

---

# Search

Global CMS Search

Should find:

Pages

Sections

Products

Media

Blog Posts

Categories

Customers

Orders

Quotes

Settings

---

# Audit History

Every content change is recorded.

Created

Edited

Deleted

Published

Restored

With

User

Date

IP Address

---

# Permission Matrix

Super Admin

Everything

Administrator

Everything Except System

Content Editor

CMS

Blog

Media

SEO

Marketing

Marketing Pages

Announcements

Newsletter

Sales

Quotes

Orders

No CMS

---

# Future Expansion

The CMS architecture should support future modules without redesign.

Examples

Microsites

Campaign Pages

Multi-language

Regional Content

A/B Testing

Theme Variants

Personalization

AI Content Generation

Reusable Content Blocks

---

# Acceptance Criteria

✓ Unlimited pages

✓ Unlimited reusable sections

✓ Drag-and-drop section ordering

✓ Draft & preview mode

✓ Version history

✓ Scheduled publishing

✓ Responsive visibility controls

✓ Media optimization

✓ Global SEO management

✓ Permission-based editing

✓ Shared hosting compatible

---

End of Part 7