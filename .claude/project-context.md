# Sigma Project Context

## Project Overview
- **Type:** Laravel dental lab management system
- **Stack:** Laravel, PHP, Blade templates, jQuery, Alpine.js, Bootstrap 4
- **Location:** `C:\Users\Yazan\Desktop\Sigma\staging`

## Key Directories
- `resources/views/` - Blade templates
- `resources/views/components/` - Reusable Blade components
- `resources/views/layouts/app.blade.php` - Main layout file
- `app/Http/Controllers/` - Controllers
- `routes/web.php` - Route definitions
- `public/assets/css/` - CSS files
- `public/assets/js/` - JS files

## Important Components
1. **ios-dtp.blade.php** - Custom iOS-style date-time picker
   - Uses Alpine.js
   - Props: name, id, value, required, class, mode (datetime/date)
   - Fixed: JS function name sanitization ($jsId), XSS prevention with @json()
   - Has text-size-adjust to prevent accessibility font scaling

2. **dialog-popup component** - Uses Magnific Popup library
   - Located in components folder
   - Used across many views

## Dialog/Modal Patterns
- **Magnific Popup:** Used in most pages via `<x-dialog-popup>` component
  - Trigger: `.popup-trigger` class with `data-mfp-src` and `data-mfp-class`
  - JS: `jquery.magnific-popup.min.js`

- **Bootstrap Modals:** Preferred for iOS compatibility
  - Use `data-toggle="modal"` and `data-target="#modalId"`
  - Add `modal-dialog-centered` for vertical centering
  - Dark blur backdrop CSS:
    ```css
    .modal {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
    }
    ```

## Recent Performance Optimizations (app.blade.php)
1. **Deferred JS:** animation.js, script2.js (NOT jQuery - breaks everything)
2. **Async CSS:** dialog.css, custom-styling.css using `media="print" onload="this.media='all'"`
3. **Preconnect:** Added for CDN origins (fonts.googleapis.com, cdnjs.cloudflare.com, etc.)
4. **Consolidated Google Fonts:** Combined 7+ requests into 1
5. **Removed duplicate Font Awesome:** Kept v6, removed v5

## Route Naming Convention
- Uses hyphens: `media-create`, `media-edit`, `media-destroy`, `media-update`
- NOT dots: ~~`media.create`~~

## Common Gotchas
1. **jQuery dependency:** 6,855 usages - cannot be deferred or removed
2. **Magnific Popup iOS issues:** Popups shift off-screen on iPad (768x1024)
   - Solution: Convert to Bootstrap modals
3. **Parsley validation:** Use `.validate()` not `.on('form:error')`
4. **JPEG validation:** Use `mimes:jpeg,jpg` not just `mimes:jpg`

## Key Files Modified Recently
- `resources/views/layouts/app.blade.php` - Performance optimizations
- `resources/views/dashboard.blade.php` - Converted payment/delivery popups to Bootstrap modals
- `resources/views/media/index.blade.php` - Fixed route names, button layout
- `resources/views/media/edit.blade.php` - Fixed Parsley validation, button re-enable
- `app/Http/Controllers/MediaController.php` - Fixed JPEG mime validation
- `resources/views/components/ios-dtp.blade.php` - JS function name fix, XSS fix

## CSS Patterns Used
- Dark modal backdrop: `rgba(0, 0, 0, 0.7)` with `backdrop-filter: blur(5px)`
- iOS text freeze: `text-size-adjust: 100%`
- Attribute selectors for dynamic IDs: `[id^="payment-modal-"]`

## JavaScript Patterns
- Alpine.js for reactive components (ios-dtp)
- jQuery for DOM manipulation and plugins
- Magnific Popup for dialogs (being phased out for Bootstrap)

## User Preferences (from CLAUDE.md)
- Edit only explicitly referenced files
- Make changes immediately, no confirmations
- One-shot edits only
- Max 3 short sentences explanation
- No warnings, no preambles
- User understands Laravel, PHP, JS, HTTP, DB basics
