# TODO - Login icon behavior fix

Context:
- Login page: resources/views/login.blade.php
- Icons are implemented via CSS pseudo-elements on `.input-group::before` (user/email: \f007, lock/password: \f023).
- Requirement:
  - Icons must not disappear or move when input is focused.
  - On focus, icon color should change to `#38b44a` and revert when focus is lost.
  - Do not change the UI of the page except the icons in the two inputs.
  - No need to change/adjust the left positioning.

Steps:
1. Update focus color
   - Change `.input-group:focus-within::before` color from `#5d78ff` to `#38b44a`.
   - File: resources/views/login.blade.php
   - Status: Pending

2. Ensure icon visibility on focus (no movement or disappearance)
   - Reinforce `.input-group::before` with:
     - `opacity: 1 !important;`
     - `visibility: visible !important;`
     - `pointer-events: none !important;`
   - Avoid altering `left` positioning (as requested).
   - File: resources/views/login.blade.php
   - Status: Pending

3. Verify behavior
   - Icons remain visible and fixed for both fields (email and password).
   - Icons highlight to `#38b44a` on focus and revert on blur.
   - No other UI changes observed.
   - Status: Pending
