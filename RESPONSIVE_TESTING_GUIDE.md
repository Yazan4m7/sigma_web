# Responsive Testing Guide - Operations Dialog Component

## Quick Start Testing

### Using Chrome DevTools (Recommended)

1. **Open DevTools**
   - Press `F12` or `Ctrl+Shift+I` (Windows/Linux)
   - Press `Cmd+Option+I` (Mac)

2. **Enable Device Mode**
   - Click device toolbar icon (📱) or press `Ctrl+Shift+M`

3. **Test Common Devices**

   Select from dropdown:
   - **iPhone SE** - 375x667 (Small mobile)
   - **iPhone 12 Pro** - 390x844 (Standard mobile)
   - **iPhone 14 Pro Max** - 430x932 (Large mobile)
   - **iPad Mini** - 768x1024 (Small tablet)
   - **iPad Pro** - 1024x1366 (Large tablet)
   - **Responsive** - Custom sizes

4. **Test Orientations**
   - Click rotate icon (🔄) to switch portrait ↔ landscape

5. **Test Touch Events**
   - Enable "Touch" from DevTools settings
   - Simulates touch interactions

## Testing Checklist

### 📱 Mobile (375px - 767px)

#### Dialog Behavior
- [ ] Dialog opens full-width with proper padding
- [ ] Close button (×) is easily tappable (min 44px)
- [ ] Title is fully visible, no overflow
- [ ] Modal backdrop dims screen properly

#### Machine Grid
- [ ] Machines display in single column
- [ ] Each machine card is easily tappable
- [ ] Machine images load and display correctly
- [ ] Machine names don't overflow card
- [ ] Selected state clearly visible (blue border)

#### Form Inputs
- [ ] Build name input is full-width
- [ ] Material type dropdown is full-width
- [ ] Both inputs stack vertically
- [ ] Keyboard opens without breaking layout
- [ ] Inputs don't zoom on focus (Safari)

#### Action Button
- [ ] Button is full-width
- [ ] Text is clearly readable
- [ ] Disabled state shows gray (no click)
- [ ] Enabled state shows blue gradient
- [ ] Tap provides visual feedback

#### Build Rows (Expanded Tiles)
- [ ] Headers compress nicely on one line
- [ ] Toggle arrow (▼) is tappable
- [ ] Build title truncates with ellipsis (...)
- [ ] Date and count remain visible
- [ ] Expand animation smooth (300ms)
- [ ] Job rows stack vertically when expanded

#### Tables
- [ ] Headers abbreviated (D.Date not Delivery Date)
- [ ] Less important columns hidden
- [ ] No horizontal scrolling
- [ ] Text remains readable
- [ ] Tap to sort works (if enabled)

#### Tabs
- [ ] Stage tabs scroll horizontally if needed
- [ ] Active/Waiting tabs fit in viewport
- [ ] Active tab clearly highlighted
- [ ] Swipe scrolling feels smooth

### 📱 Large Mobile / Phablet (576px - 767px)

- [ ] Machines display in 2 columns
- [ ] Build name and material inputs side-by-side
- [ ] Machine images slightly larger (10rem)
- [ ] Slide panel max-width 400px (not full screen)
- [ ] Job rows can show horizontal layout

### 📱 Tablet Portrait (768px - 991px)

- [ ] Dialog max-width 720px (centered)
- [ ] Machines display in 3 columns
- [ ] Stage tabs show as vertical sidebar (left 20%)
- [ ] Content area 80% (right side)
- [ ] Desktop headers shown (Delivery Date not D.Date)
- [ ] More table columns visible
- [ ] Padding increases for breathing room
- [ ] Machine images larger (11rem)

### 💻 Tablet Landscape / Small Desktop (992px - 1199px)

- [ ] Dialog max-width 900px
- [ ] Machines display in 4 columns
- [ ] All table columns visible
- [ ] Slide panel max-width 500px
- [ ] Machine images 12rem height
- [ ] Hover effects working (on mouse devices)

### 💻 Desktop (1200px+)

- [ ] Dialog max-width 1000px
- [ ] Maximum spacing and padding
- [ ] Machine images 13rem height
- [ ] All features fully visible
- [ ] Optimal typography sizes
- [ ] Smooth hover animations

## Landscape Testing

### 🔄 Mobile Landscape (height < 600px)

- [ ] Dialog height adjusts (max 95vh)
- [ ] Padding reduced to fit content
- [ ] Machine images smaller (6rem)
- [ ] More columns in machine grid
- [ ] Build rows more compressed
- [ ] Still scrollable if content overflows

## Interaction Testing

### Touch Interactions (Mobile/Tablet)
1. **Tap machine card**
   - [ ] Card highlights immediately
   - [ ] Border changes to blue
   - [ ] Previous selection deselects

2. **Tap and scroll**
   - [ ] Smooth momentum scrolling
   - [ ] No bounce at edges (controlled)
   - [ ] Scrollbars hidden on iOS

3. **Tap expand/collapse build**
   - [ ] Smooth expand animation
   - [ ] Arrow rotates 180°
   - [ ] Content reveals progressively

4. **Swipe slide panel**
   - [ ] Slide-in animation smooth
   - [ ] Backdrop appears
   - [ ] Tap backdrop to close

### Mouse Interactions (Desktop)
1. **Hover machine card**
   - [ ] Border color changes
   - [ ] Subtle lift effect (translateY)
   - [ ] Cursor changes to pointer

2. **Hover buttons**
   - [ ] Color darkens
   - [ ] Shadow appears
   - [ ] Smooth transition

3. **Click close button**
   - [ ] Modal closes with fade
   - [ ] No layout shift

### Keyboard Interactions (All Devices)
1. **Tab navigation**
   - [ ] Focus visible (blue outline 3px)
   - [ ] Logical tab order
   - [ ] Can reach all interactive elements

2. **Enter/Space on buttons**
   - [ ] Activates button action
   - [ ] Same as click/tap

3. **Escape key**
   - [ ] Closes modal (if implemented)
   - [ ] Returns focus properly

## Browser-Specific Testing

### Safari iOS (iPhone/iPad)

1. **Bottom Bar Overlap**
   - [ ] Content not hidden behind Safari UI
   - [ ] Safe area inset applied correctly
   - [ ] Footer button always accessible

2. **Zoom Prevention**
   - [ ] Inputs don't trigger zoom
   - [ ] Viewport stays fixed
   - [ ] Double-tap doesn't zoom

3. **Scrolling**
   - [ ] Bounce scroll feels natural
   - [ ] Momentum scrolling smooth
   - [ ] Can scroll to top/bottom

### Chrome Android

1. **Address Bar**
   - [ ] Layout adapts as address bar hides
   - [ ] No layout shift
   - [ ] Full viewport used

2. **Scrollbars**
   - [ ] Custom styled scrollbars visible
   - [ ] Gray track, darker thumb
   - [ ] Hover effect on desktop

### Samsung Internet

- [ ] Same behavior as Chrome Android
- [ ] Touch targets adequate
- [ ] Animations smooth

## Edge Cases Testing

### Long Text Handling
1. **Very long machine name**
   - [ ] Wraps to multiple lines
   - [ ] Or truncates with ellipsis
   - [ ] Card expands vertically if needed

2. **Long patient name**
   - [ ] Shows ellipsis
   - [ ] Doesn't break layout
   - [ ] Tooltip shows full name (if implemented)

3. **Long build name**
   - [ ] Truncates with ellipsis
   - [ ] Header doesn't wrap

### Many Items
1. **10+ machines**
   - [ ] Grid scrolls vertically
   - [ ] No horizontal overflow
   - [ ] All machines accessible

2. **20+ jobs in build**
   - [ ] Expanded area scrolls
   - [ ] Smooth scroll performance
   - [ ] No lag or jank

### Empty States
- [ ] No machines: Shows empty grid or message
- [ ] No jobs: Shows empty state
- [ ] Error state: Shows error message

### Network Conditions
1. **Slow connection**
   - [ ] Images load progressively
   - [ ] Placeholder or fallback shown
   - [ ] No broken image icons

2. **Offline**
   - [ ] Cached styles work
   - [ ] Proper error handling

## Performance Testing

### Animation Smoothness
- [ ] Dialog fade-in: 60fps
- [ ] Build expand: 60fps
- [ ] Slide panel: 60fps
- [ ] Page scroll: 60fps

### Load Time
- [ ] responsive.css loads < 50ms
- [ ] No render-blocking
- [ ] No FOUC (Flash of Unstyled Content)

### Memory Usage
- [ ] No memory leaks on open/close
- [ ] Images garbage collected
- [ ] Smooth after 10+ modal opens

## Accessibility Testing

### Screen Reader (VoiceOver/TalkBack)
- [ ] Modal announces as dialog
- [ ] Machine cards announce correctly
- [ ] Form labels read properly
- [ ] Navigation logical

### Keyboard Only
- [ ] Can open dialog (if triggered by button)
- [ ] Can navigate all elements
- [ ] Can submit form
- [ ] Can close dialog

### High Contrast Mode
- [ ] Borders more visible (3px)
- [ ] Focus indicators clear
- [ ] Text readable

### Reduced Motion
- [ ] Animations very fast or none
- [ ] Still functional
- [ ] No dizziness/discomfort

## Quick Test Script

### 5-Minute Smoke Test

1. **Open page** → Admin Dashboard v2
2. **Open DevTools** → Device mode (F12, Ctrl+Shift+M)
3. **Select iPhone 12 Pro** → Portrait
   - Tap to open machine dialog
   - Tap a machine card (should highlight)
   - Type in build name input
   - Select material type
   - Verify button enables
   - Close dialog
4. **Rotate to Landscape** → Verify no breakage
5. **Select iPad** → Portrait
   - Repeat steps 3-4
   - Verify 3-column grid
6. **Select Responsive** → Drag to various sizes
   - Watch grid adapt (1→2→3→4 columns)
   - Verify no horizontal scroll
7. **Switch to Desktop** → No device emulation
   - Verify hover effects
   - Verify max-width
8. **Test Build Expand** → Click/tap toggle arrow
   - Smooth animation
   - Jobs visible

**All passed? ✅ Ready to deploy!**

## Automated Testing (Optional)

### Using Playwright/Puppeteer

```javascript
// Example: Test dialog on multiple viewports
const viewports = [
  { width: 375, height: 667 },   // iPhone SE
  { width: 768, height: 1024 },  // iPad
  { width: 1920, height: 1080 }, // Desktop
];

for (const viewport of viewports) {
  await page.setViewport(viewport);
  await page.click('.open-dialog-button');
  // Assert dialog visible
  // Assert no horizontal scroll
  // Take screenshot
}
```

## Screenshot Comparison

Take screenshots at each breakpoint for visual regression testing:

```bash
# Key screens to capture
1. Dialog closed - dashboard view
2. Dialog open - machine selection
3. Dialog with machine selected
4. Build row expanded
5. Slide panel open
```

Compare before/after responsive.css to verify improvements.

## Reporting Issues

When reporting a responsive issue, include:

1. **Device/Browser**: iPhone 12 Pro / Safari iOS 16
2. **Viewport Size**: 390x844 portrait
3. **Screenshot**: [Attach image]
4. **Expected**: Dialog should be 100% width
5. **Actual**: Dialog is cut off on right side
6. **Steps**:
   1. Open admin dashboard
   2. Tap milling stage
   3. Tap waiting tab
   4. Select device

---

**Remember**: Test on REAL devices when possible! Emulation is close but not identical to actual hardware behavior.

## Success Criteria

✅ **All checklist items passed**
✅ **No horizontal scrolling on any device**
✅ **Machine images visible at all sizes**
✅ **Text remains readable (min 14px)**
✅ **Touch targets adequate (min 44px)**
✅ **Animations smooth (60fps)**
✅ **Accessible via keyboard**
✅ **Works on Safari iOS, Chrome, default browsers**

**Status**: Ready for Production ✅
