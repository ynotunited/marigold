# InfinityFree Deploy Package

This folder describes the deployment layout for Marigold on InfinityFree.

## Target layout

Upload the application root to your InfinityFree account root, and upload the contents of `public/` into `public_html/`.

Example:

```text
account-root/
  app/
  database/
  docs/
  storage/
  vendor/
  .env
  composer.json
  composer.lock
  migrate.php
  package.json
  tailwind.config.js
  public_html/
    .htaccess
    index.php
    assets/
    ms-logo-icon.png
    ms-logo-removebg-preview.png
    manifest.json
    robots.txt
    sw.js
```

## Why this layout

- `public_html/` is the web root on InfinityFree.
- PHP entry point stays in `public_html/index.php`.
- The application code stays one level above web root.
- `.htaccess` handles clean URLs and security headers.

## Upload order

1. Upload the non-public app files to the account root.
2. Upload `public/` contents into `public_html/`.
3. Create the database in InfinityFree control panel.
4. Import the SQL schema and seed data if you have a dump.
5. Update `.env` with the InfinityFree database and URL values.

## Notes

- Keep `vendor/` uploaded because shared hosts usually do not run Composer for you.
- InfinityFree is suitable for a demo, small business site, or low-traffic launch.
- If a background install or build step is needed, run it locally before upload.
