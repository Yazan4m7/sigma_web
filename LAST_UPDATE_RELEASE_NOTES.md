# Last Update Release Notes
Compare: 5289df9 -> a728e50

This update focuses on clearer operations workflows, more readable case dialogs, and mobile-friendly layouts, plus better printing access.

## Operations Dashboard
- Standardized column sizing and alignment across waiting and active tables to reduce jitter.
- Clearer separation of Doctor, Patient, Delivery Date, Assigned To, Count, and Tags columns.
- Patient names now support mixed-language direction and clean wrapping for readability.
- Table layout fixed to prevent header/body misalignment and accidental horizontal scroll.
- Row padding and line height refined to show more cases without losing clarity.
- Tags and count columns centered for quick scanning.
- Stage tabs optimized for mobile with horizontal scroll, stacked icon/label layout, and tighter spacing.
- Badge circles stay perfectly round across screen sizes, with progressive scaling on small screens.
- Badge alignment corrected so counts sit centered next to stage names.
- Table ordering disabled to preserve workflow order as provided by the system.

## Case Completion and Dialogs
- Case completion job lists now appear as structured rows with teeth, type, material, shade, and style.
- Implant and abutment details display as stacked extras under each job when relevant.
- Long job type and material names truncate cleanly without breaking layout.
- Modals use a smoother local fade-in animation for a more polished feel.
- Delivery and QC dialogs get tailored footer spacing for clearer button separation.
- Case completion modal padding reduced to show more content at a glance.
- Active and waiting dialogs now apply consistent column styling with the main tables.
- Background scrolling is locked whenever any dialog or slide panel is open.
- Dialog open/close handlers now keep scroll-lock state in sync even when closed by backdrop.
- Loading dialog animation speed increased for snappier feedback.

## Cases Index and Filters
- Filters compressed into a single, compact row with consistent input heights.
- Date filters now use "From" and "To" placeholders instead of labels to save space.
- Doctor filter dropdown footprint reduced while keeping multi-select and search.
- Search input simplified with a shorter placeholder and aligned with filters.
- Apply Filters button reduced to a single icon for faster visual scanning.
- Mobile table layout uses fixed column widths to avoid sideways scrolling.
- Mobile typography and padding tuned for uniform row height and clean truncation.
- Status badges shrink on mobile to keep rows readable.
- Sticky table header now uses runtime toolbar height for accurate positioning.

## Delivery Schedule
- Mobile filter layout keeps From and To on the same row.
- Filter and print controls align cleanly, with print anchored to the right.
- Print button converted to a compact icon for faster access.
- Units column centered and status column left-aligned for easier scanning.

## Case View and Printing
- Added "Print Mini Label" and "Print Label" buttons on the view-only case page.
- Print actions grouped together and aligned to the right for quick access.
- Timeline shortcut hidden on view-only screen for a cleaner presentation.

## Slide Panel (Case Builds)
- Slide panel job list redesigned into a grid with dedicated columns.
- Column order prioritizes teeth first, then job type, material, shade, and style.
- Slide panel padding adjusted for a cleaner edge-to-edge layout.
- View/Edit action buttons now share a consistent full-width layout.
- Mobile grid uses tighter columns and smaller text to fit more detail.
- Slide panel open/close now participates in global scroll-lock logic.

## Navigation and Global Layout
- Header actions bar tightened to prevent wrapping and keep search aligned.
- Header search width adjusted to reduce horizontal crowding.
- Three-dot menu and page title spacing improved on mobile.
- Body margin removed for full-width layout consistency.
- Checkbox hit areas enlarged with padding and margin for easier tapping.
- Sidebar collapse spacing adjusted to reduce layout shifts.

## Consistency and Polish
- Buttons in dialogs use white text where needed for stronger contrast.
- Reset-to-waiting action spacing adjusted to avoid accidental taps.
- Badge sizing and aspect ratio enforced across responsive stylesheets.

## Summary Snapshot
- UI enhancements: 20+ items
- Bug fixes: 20+ items
- Mobile optimizations: 15+ items
- Workflow dialog improvements: 10+ items
- Printing and output changes: 4 items
- Navigation and header refinements: 6 items
