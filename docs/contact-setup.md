# Contact page

The theme renders `/contact/` through `page-contact.php`. The page content holds
the Fluent Forms shortcode, so the form remains editable in WordPress and its ID
is not hardcoded in the template. Header, footer and home CTA link to this page.

## Install on another environment

Git Updater deploys theme files, not WordPress pages or Fluent Forms records.
Activate Fluent Forms, deploy this theme, and run from the WordPress root:

```sh
wp eval-file wp-content/themes/mv-v2/tools/setup-contact.php
```

Use the actual theme directory name if different. The setup creates a published
Contact page and a three-field form, with notifications to the recipient configured
in the setup script and Reply-To set to the visitor's email. It reuses its own
form and page on subsequent runs without overwriting editorial changes. It stops
if an unrelated Contact page already exists. No credentials are included.

Without WP-CLI: export **Portfolio contact** from Fluent Forms → Tools in local,
import it in production, and create a page with slug `contact`, template **Contact**,
and the imported form's shortcode. Enable the honeypot in Fluent Forms settings.
Set the `mv_contact_form_id` WordPress option to the imported ID if using the
theme's entry cleanup; the CLI setup does this automatically.

## Before launch

- Configure and test the site's authenticated mail transport (FluentSMTP is
  installed locally but installing it does not configure a connection). The form
  leaves From Email to that transport and uses the visitor only as Reply-To.
- Submit a real test and confirm receipt in the destination mailbox.
- The form requests deletion of entries after submission. `inc/contact.php`
  enforces this for the configured contact form after notification processing,
  using Fluent Forms' deletion service at shutdown. Disable the form's
  `delete_entry_on_submission` setting if retention is required. Check any
  separate SMTP logging retention. Failed
  delivery can mean the message cannot be recovered from WordPress.
- Publish an appropriate privacy policy and assign it in WordPress Settings →
  Privacy. The contact template automatically links it once published.
- Honeypot is basic spam protection. Cloudflare Turnstile can be added through
  Fluent Forms when production site and secret keys are available.
- Purge the production page cache after deploying and creating the page.

## Verification

Check the three navigation links, required-field and invalid-email errors,
honeypot rejection, success confirmation, actual mail receipt, light/dark themes,
mobile layout, and keyboard navigation. The recipient must not appear in HTML.
