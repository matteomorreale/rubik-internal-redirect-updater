# Rubik Internal Redirect Updater

### Plugin Name: Rubik Internal Redirect Updater

**Version:** 1.2  
**Author:** Matteo Morreale  
**Description:** This plugin updates internal links on a WordPress site that point to 301 redirects, replacing them with their final target to avoid unnecessary redirect hops and improve performance.

--- WARNING --- The [Rubik Link Analyzer](https://github.com/matteomorreale/rubik-link-analyzer) plugin must be installed, and a scan must have been performed.

## Features

- **Internal Link Scanning:** Scans site posts to identify internal links pointing to 301 redirects.
- **Automatic Updates:** Automatically updates links to point directly to the final URL, improving SEO and reducing load times.
- **Simple Interface:** Provides an admin interface to start scans and view modification logs.
- **Status Tracking:** Processed links are tracked in a support table to avoid repetitions and infinite loops.

## Requirements

- **Rubik Link Analyzer** plugin with a completed scan.
- **WordPress 5.0+**
- **PHP 7.0+**
- **cURL** enabled for HTTP requests.

## Installation

1. **Upload the plugin:** Upload the plugin folder to the `/wp-content/plugins/` directory.
2. **Activate the plugin:** Go to the WordPress Plugins section and activate "Rubik Internal Redirect Updater."
3. **Admin Options:** A new menu item called "Redirect Updater" will appear in the WordPress admin panel.

## Usage

1. **Access the plugin:** Navigate to **Redirect Updater** in the WordPress admin menu.
2. **Start the scan:** Click the "Start Scan and Update Links" button to begin scanning posts.
3. **View modification logs:** During the scan, the plugin will display a log of processed links, including:
   - The **post title** where the link was found (with a link to the post itself).
   - The **original URL** of the link.
   - The **status** of the check (e.g., OK, Ignored, Error).
   - The **HTTP response code** for each link (e.g., 301, 404, etc.).

## How It Works

- **Link Scanning:** The plugin scans links within posts to determine if they point to 301 redirects.
- **Redirect Processing:** Internal links are updated to their final destination if the redirection is confirmed.
- **Comprehensive Log:** A log is generated for each operation, including errors and HTTP response codes.
- **Processed Link Tracking:** To avoid loops and repetitions, processed links are tracked in a separate table (`rubik_processed_links`).

## Tables Used

- **`rubik_link_data`**: Existing table used for link scanning.
- **`rubik_processed_links`**: Support table created by the plugin to store processed links and prevent repetitions.

## Security Notes

- The plugin uses **nonces** to protect AJAX calls and prevent CSRF attacks.
- Link verification is performed using **cURL**, ensuring the plugin follows redirects without exposing vulnerabilities.

## Limitations

- Currently, the plugin **does not modify external links**, marking them as ignored in the log.
- If a link undergoes a chain of redirects, the plugin follows all redirects up to a maximum of 10 to avoid potential **infinite loops**.

## Uninstallation

When the plugin is deactivated, the support table (`rubik_processed_links`) is removed to free up database space.

## Contributions

Contributions and suggestions are welcome! Feel free to open issues or pull requests on [GitHub](https://github.com/matteomorreale/rubik-internal-redirect-updater).

## License

This plugin is licensed under **GPLv2** or later. Feel free to modify and redistribute it.

## Contact

For more information or support, contact [Matteo Morreale](mailto:matteo.morreale@gmail.com) or visit [website](https://matteomorreale.it).

---

Thank you for using Rubik Internal Redirect Updater! We hope it helps improve your link management and site performance.
