# Legal & Compliance Documentation

Comprehensive reference for the legal and compliance pages of the Marigold Signature website
(`Marigold Signature Nigeria Limited`).

---

## 1. Overview

The site ships with six public-facing legal/compliance pages. They are all static views rendered
through the `main` layout, served by `App\Controller\PageController`, and linked from the footer.

| Page | Route | View file | Controller method | Type |
| --- | --- | --- | --- | --- |
| Privacy Policy | `/privacy-policy` | `app/View/pages/public/static/privacy.php` | `PageController::privacy` | Existing (rewritten) |
| Terms of Use | `/terms-and-conditions` | `app/View/pages/public/static/terms.php` | `PageController::terms` | Existing (rewritten) |
| Shipping Policy | `/shipping-policy` | `app/View/pages/public/static/shipping.php` | `PageController::shipping` | Existing |
| Return Policy | `/return-policy` | `app/View/pages/public/static/returns.php` | `PageController::returns` | Existing |
| Data & Compliance | `/data-and-compliance` | `app/View/pages/public/static/data-compliance.php` | `PageController::dataCompliance` | **New** |
| IP Infringement | `/ip-infringement` | `app/View/pages/public/static/ip-infringement.php` | `PageController::ipInfringement` | **New** |

---

## 2. Routing & Registration

Routes are registered in `public/index.php` under the **"Static Policy Pages"** comment block:

```php
$router->get('/privacy-policy', ['App\Controller\PageController', 'privacy']);
$router->get('/terms-and-conditions', ['App\Controller\PageController', 'terms']);
$router->get('/shipping-policy', ['App\Controller\PageController', 'shipping']);
$router->get('/return-policy', ['App\Controller\PageController', 'returns']);
$router->get('/data-and-compliance', ['App\Controller\PageController', 'dataCompliance']);
$router->get('/ip-infringement', ['App\Controller\PageController', 'ipInfringement']);
```

All six are also registered in the XML sitemap (`app/Controller/SitemapController.php`).

> **Note:** The footer previously linked to `/privacy`, `/terms`, and `/shipping`, which were not
> registered routes. These were corrected to the canonical routes above.

---

## 3. Content Summary

### 3.1 Privacy Policy — `/privacy-policy`

Explains what data Marigold collects and why, with sections:

1. Information We Collect
2. How We Use Your Information
3. Legal Basis for Processing
4. Cookies & Tracking Technologies
5. How We Share Your Information
6. Data Retention
7. Data Security
8. Your Privacy Rights
9. International Data Transfers
10. Children's Privacy
11. Third-Party Links
12. Changes to This Policy
13. Contact Us

Contact emails used: `privacy@marigoldsignature.com`, `data@marigoldsignature.com`.

### 3.2 Terms of Use — `/terms-and-conditions`

Governs use of the website and services, with sections:

1. Agreement to Terms
2. Eligibility
3. Accounts & Security
4. Products, Pricing & Availability
5. Corporate Orders & Quotations
6. Customization & Artwork Approval
7. Payment Terms (50% non-refundable deposit on custom orders)
8. Shipping, Delivery & Returns
9. Intellectual Property Rights
10. Acceptable Use
11. Limitation of Liability
12. Indemnification
13. Termination
14. Governing Law & Jurisdiction (Nigeria)
15. Changes to These Terms
16. Contact Us

Contact email: `legal@marigoldsignature.com`.

### 3.3 Data & Compliance — `/data-and-compliance`

Documents the data-protection and compliance framework (NDPA/NDPR-aligned, GDPR-aligned where
applicable), with sections:

1. Our Compliance Commitment
2. Regulatory Framework
3. Categories of Data We Process
4. Lawful Bases for Processing
5. Data Protection Principles
6. Data Subject Rights
7. Data Retention Schedule (tabular)
8. Security Safeguards
9. Third-Party Processors
10. Data Breach Management
11. Compliance & Oversight
12. Contact & Regulatory Information

Contact emails: `data@marigoldsignature.com`, `privacy@marigoldsignature.com`.

### 3.4 IP Infringement — `/ip-infringement`

Takedown procedure for copyright, trademark, and design infringement claims, with sections:

1. Respect for Intellectual Property
2. Copyright Infringement Notices
3. Trademark & Design Infringement Notices
4. Information Required in a Notice
5. How to Submit a Notice
6. Counter-Notices
7. Repeat Infringer Policy
8. Good Faith & Misrepresentation
9. Contact Information

Designated agent email: `ip@marigoldsignature.com`.

### 3.5 Shipping Policy — `/shipping-policy`

Delivery timelines, fees, and fulfilment terms (unchanged, pre-existing).

### 3.6 Return Policy — `/return-policy`

Returns, exchanges, and refunds procedure (unchanged, pre-existing).

---

## 4. Front-End Conventions

All legal pages share a consistent layout built from the design system tokens
(`public/assets/css/design-system.css`) — **no `@tailwindcss/typography` (prose) dependency**:

- **Hero:** `pt-32 pb-16 px-6 lg:px-20 border-b border-[var(--border)]`, centered heading at
  `max-w-[800px]`, with a gold eyebrow (`uppercase tracking-widest text-xs text-[var(--gold)]`) and
  a "Last updated: {date}" line (`date('F j, Y')`).
- **Body:** `py-20 px-6 lg:px-20`, content column `max-w-[800px] mx-auto space-y-14`.
- **Headings:** `<h2 class="text-2xl font-bold text-white mb-4">`.
- **Paragraphs / lists:** `text-[var(--text-secondary)] leading-relaxed`, `list-disc pl-6 space-y-2`.
- **Contact boxes:** `rounded-2xl bg-[var(--card)] border border-[var(--border)] p-6`.
- **Data retention table:** uses `table w-full text-left` wrapped in `overflow-x-auto` for mobile.

> The previous versions of `privacy.php` and `terms.php` used Tailwind `prose` classes, which are
> **not compiled** (the typography plugin is absent from `tailwind.config.js`). The rewrite removed
> that dependency.

---

## 5. Cross-References

| Source | Links to |
| --- | --- |
| Footer (`app/View/components/footer.php`) | All six legal pages |
| `app/View/components/newsletter_popup.php` | `/terms-and-conditions`, `/privacy-policy` |
| `app/View/pages/public/quote_request.php` | `/terms-and-conditions` |
| `app/View/pages/public/contact.php` | `/privacy-policy` |
| Terms of Use (§9) | `/ip-infringement` |
| Terms of Use (§8) | `/shipping-policy`, `/return-policy` |
| Data & Compliance (§3, §12) | `/privacy-policy`, `/terms-and-conditions` |

---

## 6. Build & Verification

- Tailwind utilities used by the legal pages must exist in the compiled `public/assets/css/app.css`.
  After adding/removing classes, rebuild: `npm run build:css`.
- PHP lint: `php -l <file>` on any modified view/controller.
- Smoke test each route:
  `http://ms.test/privacy-policy`, `http://ms.test/terms-and-conditions`,
  `http://ms.test/data-and-compliance`, `http://ms.test/ip-infringement`,
  `http://ms.test/shipping-policy`, `http://ms.test/return-policy`.

---

## 7. Maintenance

- **"Last updated" dates** are generated dynamically with `date('F j, Y')` — no manual edit needed.
- **Review cadence:** policies should be re-reviewed at least annually or whenever laws, contact
  details, or business practices change.
- **Contact emails** are defined inline per page. To centralise, they could later be moved to
  `config/` (e.g. `data@`, `privacy@`, `legal@`, `ip@` @ `marigoldsignature.com`).
- When adding a new legal page: create the view under `app/View/pages/public/static/`, add a
  `PageController` method, register the route in `public/index.php`, add it to
  `SitemapController`, and add a footer link.
