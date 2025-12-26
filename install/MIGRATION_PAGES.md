# Pages Module Migration to Theme

## Overview

This migration moves the Pages functionality from the core CMS to the Starter theme as a theme-specific module. This allows different themes to have their own page management systems tailored to their specific needs.

## What Changes

### Before Migration
- Pages module was in `app/controllers/PageController.php`
- Page model was in `app/models/Page.php`
- Views were in `app/views/admin/pages/`
- Hard-coded into CMS core

### After Migration
- Pages module is in `themes/starter/modules/pages/`
- Controller: `themes/starter/modules/pages/Controller.php`
- Model: `themes/starter/modules/pages/models/PageModel.php`
- Views: `themes/starter/modules/pages/views/admin/`
- Loaded dynamically when theme is active

## Data Compatibility

**Important:** No data is lost or modified during migration!

- The `posts` table (where pages are stored) remains unchanged
- The `page_meta` table (custom fields) remains unchanged
- All existing pages and their data remain intact
- The module just changes HOW we access the data, not WHERE it's stored

## Running the Migration

### Step 1: Backup (Automatic)
```bash
php install/migrate_pages_module.php
```

This will:
- Create a backup in `storage/backups/pages_migration_YYYY-MM-DD_HHMMSS/`
- Register the Pages module in the database
- Test data compatibility
- Provide next steps

### Step 2: Test
1. Go to admin panel
2. Navigate to the Pages module: `/admin?page=module/pages`
3. Test creating, editing, and viewing pages
4. Ensure all functionality works

### Step 3: Cleanup (Optional)
Once you've verified everything works:

```bash
php install/migrate_pages_module.php --cleanup
```

This will:
- Remove old core files: `app/controllers/PageController.php`, `app/models/Page.php`, `app/views/admin/pages/`
- Keep the backup for safety
- Update the system to only use the theme module

## Rollback

If something goes wrong, you can rollback:

1. Copy files from `storage/backups/pages_migration_YYYY-MM-DD_HHMMSS/` back to their original locations
2. Remove the Pages module from the `modules` table:
   ```sql
   DELETE FROM modules WHERE slug = 'pages' AND path LIKE 'themes/%';
   ```
3. Restart your web server

## Theme Switching

When you switch themes:
- The old theme's Pages module will be deactivated
- The new theme's Pages module will be activated (if it has one)
- **Data remains intact** - different themes just provide different UIs and features

If a theme doesn't have a Pages module:
- Pages won't be accessible in that theme
- Data is still in the database
- Switching back to a theme with Pages will restore access

## Benefits

1. **Theme-Specific Features**: Each theme can customize the page management to fit its needs
2. **Flexibility**: E-commerce themes can have product pages, blog themes can have article pages
3. **Modularity**: Pages module can be updated independently per theme
4. **No Data Loss**: Database structure stays the same, ensuring compatibility

## Technical Details

### Module Structure
```
themes/starter/modules/pages/
├── Controller.php          # Main controller (PagesModuleController)
├── models/
│   └── PageModel.php      # Page data model
├── views/
│   └── admin/             # Admin interface views
│       ├── index.php      # List pages
│       ├── create.php     # Create page
│       ├── edit.php       # Edit page
│       └── versions.php   # Version history
└── module.json            # Module manifest
```

### Routes
All admin routes are now prefixed with `module/pages`:
- `/admin?page=module/pages` - List pages
- `/admin?page=module/pages/create` - Create page
- `/admin?page=module/pages/edit/{id}` - Edit page

### Module Manifest
The `module.json` defines:
- Module metadata (name, version, author)
- Admin menu configuration
- Routes and handlers
- Required permissions

## Support

If you encounter issues:
1. Check the backup exists in `storage/backups/`
2. Verify the theme module files exist in `themes/starter/modules/pages/`
3. Check database: `SELECT * FROM modules WHERE slug = 'pages'`
4. Review error logs

For questions or issues, refer to the main CMS documentation.

