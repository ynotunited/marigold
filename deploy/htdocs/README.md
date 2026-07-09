# Htdocs Deploy Package

This package is for hosts where `htdocs` is the document root, which matches your setup.

## Recommended upload layout

Upload the repository contents directly into `htdocs`, keeping the existing `public/` directory in place.

Example:

```text
htdocs/
  app/
  database/
  docs/
  public/
  storage/
  vendor/
  .env
  composer.json
  composer.lock
  migrate.php
  package.json
  tailwind.config.js
  index.php
  .htaccess
```

## Why this works

- `htdocs/index.php` becomes the public entry point.
- It forwards to `public/index.php`, which already bootstraps the app correctly.
- `public/index.php` resolves `BASE_PATH` to `htdocs`, so the app can find `app/`, `vendor/`, `.env`, and `storage/`.
- `public/.htaccess` continues to handle clean URL routing for the app itself.

## Upload order

1. Upload the repo root contents into `htdocs`.
2. Ensure `htdocs/public/` is present and unchanged.
3. Set your production `.env`.
4. Import the database.
5. Visit the site through `marigold.gt.tc`.

## Notes

- If the host requires a specific PHP version, use PHP 8.2 or later.
- Keep `vendor/` uploaded.
- If the server does not honor the root `index.php`, I can switch the package to serve directly from `htdocs/public/` instead.
