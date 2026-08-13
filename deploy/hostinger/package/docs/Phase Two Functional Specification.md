# PART 10 — Phase Two Functional Specification

---

# Phase Two Overview

## Objective

Transform the ecommerce platform into a complete business management system.

At the end of Phase Two, Marigold should be able to manage:

- Customers
- Sales
- Inventory
- Production
- Delivery
- Marketing
- Corporate Accounts

from one platform.

---

# Phase Two Business Domains

Customer Management

↓

Sales Management

↓

Inventory

↓

Production

↓

Delivery

↓

CRM

↓

Marketing

↓

Reporting

↓

Automation

---

# Module 1 — Corporate Accounts

## Objective

Support businesses that place recurring or large-volume orders.

---

## Features

Company Profile

Company Logo

Industry

Multiple Contact Persons

Billing Contacts

Delivery Contacts

Purchase History

Preferred Pricing

Credit Limit

Assigned Account Manager

Tax Information

VAT Number

Payment Terms

Contract Documents

Notes

---

## Corporate Dashboard

Orders

Quotes

Invoices

Approvals

Addresses

Saved Products

Downloads

Support

---

## Business Rules

One company

↓

Many users

One company

↓

Many delivery addresses

One company

↓

Many quotations

---

# Module 2 — CRM

## Objective

Track leads before they become customers.

---

## Pipeline

Lead

↓

Qualified

↓

Quotation

↓

Negotiation

↓

Won

↓

Customer

---

## Lead Fields

Company

Industry

Contact Person

Phone

Email

Source

Expected Value

Priority

Sales Representative

Next Follow-up

Status

Probability

---

## Activities

Call

Meeting

Email

WhatsApp

Visit

Task

Reminder

---

## Dashboard

Pipeline Value

Deals Won

Conversion Rate

Follow-ups Today

Lead Sources

---

# Module 3 — Inventory Management

## Objective

Provide accurate stock visibility.

---

## Products

Current Stock

Reserved Stock

Available Stock

Incoming Stock

Minimum Stock

Maximum Stock

---

## Transactions

Purchase

Sale

Return

Adjustment

Damage

Transfer

---

## Alerts

Low Stock

Out of Stock

Negative Inventory

---

## Reports

Stock Value

Fast Movers

Slow Movers

Dead Stock

Inventory Aging

---

# Module 4 — Warehouse Management

Warehouses

Storage Locations

Bins

Transfers

Receiving

Dispatch

Cycle Count

Stock Audit

Barcode Support

QR Code Support

---

# Module 5 — Production Management

## Objective

Track customized orders from approval to delivery.

---

## Workflow

Order Paid

↓

Artwork Required

↓

Artwork Uploaded

↓

Artwork Review

↓

Customer Approval

↓

Production Queue

↓

Printing

↓

Quality Check

↓

Packaging

↓

Dispatch

↓

Completed

---

## Production Dashboard

Jobs Waiting

Jobs In Progress

Completed Today

Delayed Jobs

Rejected Jobs

Average Completion Time

---

## Production Job

Customer

Order

Products

Quantity

Artwork

Due Date

Assigned Staff

Priority

Status

Notes

---

# Module 6 — Artwork Approval

Customer uploads logo.

↓

Designer prepares mockup.

↓

Customer reviews.

↓

Approve

OR

Request Changes

↓

Final Approval

↓

Production

---

## Features

Multiple Versions

Comments

PDF Approval

Image Approval

History

Notifications

---

# Module 7 — Delivery Management

Delivery Dashboard

Courier

Tracking Number

Dispatch Date

Estimated Delivery

Delivered Date

Delivery Notes

Proof of Delivery

Signature (Future)

---

## Delivery Status

Preparing

Ready

Dispatched

In Transit

Delivered

Returned

Cancelled

---

# Module 8 — Supplier Management

Suppliers

Products

Purchase Orders

Contacts

Invoices

Payments

Performance

Delivery Time

Supplier Ratings

---

# Module 9 — Purchase Orders

Generate Purchase Orders

Receive Goods

Supplier Invoices

Outstanding Orders

Approval Workflow

---

# Module 10 — Marketing

Campaigns

Email Marketing

Discounts

Coupons

Referral Codes

Gift Cards

Promotions

Seasonal Campaigns

---

# Module 11 — Customer Loyalty

Reward Points

Membership Levels

Silver

Gold

Platinum

Birthday Rewards

Referral Bonuses

VIP Discounts

---

# Module 12 — Support Centre

Support Tickets

Departments

Priority

Attachments

Replies

Internal Notes

Knowledge Base

---

# Module 13 — Notifications

SMS

Email

Dashboard

WhatsApp (Future)

Push Notifications (Future)

---

# Module 14 — Reporting

Sales

Inventory

Production

Customers

Marketing

Finance

CRM

Quotes

Support

Exports

CSV

Excel

PDF

---

# Module 15 — Advanced Search

Search

Orders

Customers

Products

Quotes

Invoices

Support

Production Jobs

Suppliers

---

# Module 16 — Automation

Automatic Quote Expiry

Low Stock Alerts

Invoice Generation

Reminder Emails

Follow-up Tasks

Recurring Reports

Scheduled Backups

---

# Module 17 — User Permissions

Department-based access

Sales

Inventory

Production

Marketing

Support

Finance

Management

Granular permissions

Create

Read

Update

Delete

Approve

Export

---

# Phase Two Acceptance Criteria

✓ Corporate accounts fully functional.

✓ CRM pipeline operational.

✓ Inventory updates automatically.

✓ Warehouse supports stock movements.

✓ Production workflow tracks every job.

✓ Artwork approval system operational.

✓ Delivery tracking available.

✓ Supplier management active.

✓ Purchase orders generated.

✓ Loyalty system implemented.

✓ Support ticketing live.

✓ Reports exportable.

✓ Automation reduces manual tasks.

---

# Deployment Strategy

Phase Two should remain deployable on:

Hostinger Business Hosting (minimum)

Recommended:

VPS with Redis and scheduled workers.

No architectural changes should be required when upgrading from Phase One.

---

End of Part 10