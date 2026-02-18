<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    @import url('https://fonts.googleapis.com/css?family=Lato:100,300,400,700&display=swap');

    @media screen and (min-width: 999px) {
        .logo-col {
            flex: 0 0 223px;
            max-width: 223px;
            padding-left: 0;
        }
        .logo{

        }

        #searchText{
            position: absolute;
            top: 20px;
        }
        /* Ensure sidebar logo is clickable on large screens */
        .sidebar .logo {
            position: relative;
            z-index: 1060 !important;
        }
        .sidebar .logo a {
            position: relative;
            z-index: 1061 !important;
            pointer-events: auto !important;
        }
        /* Prevent navbar from covering sidebar on large screens */
        .navbar {
            pointer-events: auto;
        }
        .navbar * {
            pointer-events: auto;
        }

    }

    .navbar-wrapper{
        display:none;
    }
    .headerRow {
        display: flex;
        align-items: center;
        justify-content: space-between !important;
        flex-wrap: nowrap !important;
        width: 100%;
        margin: 0;
        background-color: transparent;
        padding: 0;
    }

    .header-left {
        display: flex;
        align-items: center;
        min-width: 0;
        padding-left: 0;
    }

    .headerRow > .header-left,
    .headerRow > .header-actions {
        padding-left: 0;
        padding-right: 0;
    }

    .left-toggler-container {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        margin: 0;
        padding: 0;
        flex-wrap: nowrap;
    }

    .mobile-brand-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #sidebar-hamburger {
        height: 38px;
        padding: 0 8px;
        display: inline-flex;
        align-items: flex-start;
        justify-content: center;
        margin: 0;
        line-height: 1;
        flex-direction: column;
        flex-wrap: nowrap;
    }

    /** SEARCH BOXES  **/
    :root {
        --tab-color-white: #f9f9f9;
        --tab-color-black: #004345;
        --tab-color-cadet: #2b7b7d;
        --tab-color-fighter: #315f5f;
        --tab-color-space: #383961;
        --tab-color-gray: #d7d9d7;
        --tab-color-english: #2b7b7d;
        /*
         --------
         * CSS Vars
         --------
         */
        --tab-bg-color: var(--tab-color-cadet);
        --tab-text-color: var(--tab-color-gray);
        --tab-border-color: var(--tab-color-space);
        --tab-active-bg-color: var(--tab-color-white);
        --tab-active-text-color: var(--tab-color-black);
        --tab-active-border-color: var(--tab-color-english);
        --tab-focus-bg-color: var(--tab-color-fighter);
        --tab-focus-text-color: var(--tab-color-white);
        --tab-focus-text-secondary-color: var(--tab-color-english);
        --tab-focus-border-color: var(--tab-color-fighter);
        --color-light: white;
        --color-dark: #212121;
        --color-signal: #37b44a;
        --color-background:#f5f6fa;
        --color-text: var(--color-dark);
        --color-accent: var(--color-signal);
        --size-bezel: 0.2rem;
        --size-radius: 5px;
        --global-heading-font-family: "Lato", sans-serif;

    }

    .outerSBLabel {
        position: relative;
        margin-bottom:0 !important;
    }
    .outerSBLabel:focus-visible ,searchBox:focus-visible,.searchBox,.outerSBLabel {
        outline: none;
    }
    input:-internal-autofill-selected{
        background-color: transparent !important;
    }
    /**SEACRH BOX 2 **/




    body {
        /*background: #DDD;*/
        /*font-size: 15px;*/
    }
    #wrapp {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        position: relative;
        height: 100%;
        padding: 0;
        margin: 0;
        width: 100%;
        min-width: 0;
    }
    .fa-user{
        display:none;
        left: 5.05rem !important;

    }
    #wrapp input[type="text"] {
        height: 38px;
        font-size: 14px;
        display: block;
        font-family: "Lato", sans-serif;
        border: 1px solid rgba(43, 123, 125, 0.2);
        border-radius: 6px;
        outline: none;
        color: #2b7b7d;
        padding: 8px 12px;
        width: 100%;
        max-width: 100%;
        background: rgba(255, 255, 255, 0.95);
        position: relative;
        z-index: 1;
        transition: all .3s cubic-bezier(0.4, 0.0, 0.2, 1);
        cursor: text;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    #wrapp input[type="text"]::placeholder {
        color: rgba(43, 123, 125, 0.5);
        font-weight: 400;
    }

    #wrapp input[type="text"]:hover {
        border-color: rgba(43, 123, 125, 0.2);
        box-shadow: 0 2px 8px rgba(43, 123, 125, 0.1);
    }

    #wrapp input[type="text"]:focus {
        width: 100%;
        border-color: #2b7b7d;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(43, 123, 125, 0.15);
    }

    .SBF2 {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        min-width: 0;
    }

    #wrapp #search_submit {
        display: none;
    }

    /* Responsive search box */
    @media (max-width: 991px) {
        #wrapp input[type="text"] {
            font-size: 13px;
        }
    }



    /** END SEARCH BOX 2 **/

    .searchBox_label {
        position: absolute;
        left: 5px;
        top: 0;
        padding: calc(var(--size-bezel) * 0.75) calc(var(--size-bezel) * 0.5);
        margin: calc(var(--size-bezel) * 0.75 + 3px) calc(var(--size-bezel) * 0.5);
        background: pink;
        white-space: nowrap;
        transform: translate(0, 0);
        transform-origin: 0 0;
        background: var(--color-background);
        transition: transform 120ms ease-in;
        color: #1d253b80;
        line-height: 1.2;
    }
    .searchBox {
        box-sizing: border-box;
        display: block;
        width: 100%;
        border: 1px solid #1d253b80;
        padding: calc(var(--size-bezel) * 1.5) var(--size-bezel);
        background: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' fill='%231d253b80' class='bi bi-search' viewBox='0 0 16 16'> <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'></path> </svg>") no-repeat ;
        background-position: 96% 45%;
        padding-right: 25px;
        background-size: 16px;
        border-radius: var(--size-radius);
    }
    .searchBox:not(:-moz-placeholder-shown) + .searchBox_label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-accent);
    }
    .searchBox:not(:-ms-input-placeholder) + .searchBox_label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-accent);
    }
    .searchBox:focus + .searchBox_label, .searchBox:not(:placeholder-shown) + .searchBox_label {
        transform: translate(0.25rem, -65%) scale(0.8);
        color: var(--color-accent);
    }

    .pageTitleMobile {
        display: none;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 800;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 991px) {
        .pageTitleContainer {
            display: block;
            flex: 1 1 auto;
            min-width: 0;
        }

        .pageTitleContainer .pageTitle {
            display: block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pageTitleMobile {
            display: none !important;
        }

        .dotsDiv {
            gap: 8px;
        }
    }

    @media screen and (max-width: 768px) {
        .pageTitleContainer {
            display: none;
        }

        .pageTitleMobile {
            display: none !important;
        }
    }

    /* Modern User Dropdown - Glassmorphism */
    .user-dropdown-menu {
        min-width: 260px;
        max-width: 300px;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0;
        margin-top: 12px;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: hidden;
        z-index: 1200;
    }

    .user-info-header {
        padding: 0 !important;
        margin: 0 !important;
        cursor: default;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }

    .user-info-header:hover {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
    }

    .user-info-content {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        gap: 14px;
    }

    .user-info-content .user-avatar-dropdown {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.25);
        flex-shrink: 0;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-info-content .user-avatar-dropdown img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 15px;
        font-weight: 600;
        line-height: 1.3;
        color: #fff;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .admin-badge {
        color: #94a3b8 !important;
        font-weight: 500 !important;
        font-size: 11px !important;
        margin-top: 3px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-dropdown-menu .dropdown-divider {
        display: none;
    }

    .user-dropdown-menu .menu-options {
        padding: 10px 8px;
        background: rgba(255, 255, 255, 0.5);
    }

    .user-dropdown-menu .menu-item {
        padding: 0 !important;
        margin: 0 !important;
    }

    .user-dropdown-menu .menu-link {
        color: #1e293b;
        font-weight: 500;
        font-size: 14px;
        padding: 11px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        background: transparent;
        text-decoration: none;
        cursor: pointer;
        border-radius: 10px;
        margin: 2px 0;
    }

    .user-dropdown-menu .menu-link i {
        font-size: 15px;
        color: #64748b;
        width: 20px;
        text-align: center;
        transition: color 0.2s ease;
    }

    .user-dropdown-menu .menu-link:hover {
        background: rgba(30, 41, 59, 0.08);
        color: #0f172a;
    }

    .user-dropdown-menu .menu-link:hover i {
        color: #1e293b;
    }

    .user-dropdown-menu .menu-link.logout-link {
        color: #64748b;
        margin-top: 4px;
    }

    .user-dropdown-menu .menu-link.logout-link i {
        color: #94a3b8;
    }

    .user-dropdown-menu .menu-link.logout-link:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .user-dropdown-menu .menu-link.logout-link:hover i {
        color: #dc2626;
    }

    /* Hide old close button */
    .user-dropdown-menu .close-btn {
        display: none;
    }

    /* Mobile adjustments */
    @media (max-width: 991px) {
        .user-dropdown-menu {
            position: fixed !important;
            top: 58px !important;
            right: 8px !important;
            left: auto !important;
            min-width: 240px;
        }
    }

    .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        min-width: 0;
    }

    .header-actions-inner {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: nowrap;
        width: auto;
        margin-left: auto;
        max-width: 100%;
        min-width: 0;
    }

    .header-actions .header-search {
        flex: 0 1 clamp(190px, 22vw, 260px);
        width: clamp(190px, 22vw, 260px);
        max-width: clamp(190px, 22vw, 260px);
        min-width: 170px;
        margin: 0;
    }

    .header-actions .header-search .searchBox2 {
        width: 100%;
        min-width: 0;
    }

    .header-actions .header-search #wrapp {
        width: 100%;
        justify-content: flex-end;
    }

    .header-actions .header-search #wrapp input[type="text"] {
        width: 100%;
    }

    .header-actions .header-search,
    .header-actions .dotsDiv {
        display: flex;
        align-items: center;
    }

    .header-actions .dotsDiv {
        flex: 0 0 auto;
        margin: 0 !important;
    }

    /* Keep profile trigger vertically centered and stable across breakpoints */
    .sigma-app-header .dotsDiv #navigation {
        display: flex;
        align-items: center;
        flex-basis: auto;
        width: auto;
        margin: 0;
    }

    .sigma-app-header .dotsDiv #navigation .navbar-nav {
        display: flex;
        align-items: center;
        margin: 0 !important;
        padding: 0;
    }

    .sigma-app-header .dotsDiv #navigation .dropdown.nav-item {
        display: flex;
        align-items: center;
    }

    .sigma-app-header .dotsDiv #navigation .dropdown.nav-item > a.dropdown-toggle.nav-link {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 !important;
        margin: 0;
        line-height: 1;
    }

    .sigma-app-header .dotsDiv #navigation .photo {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .sigma-app-header .dotsDiv #navigation .photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (min-width: 992px) {
        .sigma-app-header .dotsDiv .navbar-nav {
            align-items: center;
        }

        .sigma-app-header .dotsDiv .dropdown.nav-item > a.dropdown-toggle.nav-link {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px !important;
            margin: 0;
        }
    }

    /* Fix 3 dots button and dropdown positioning */
    .dotsDiv {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        position: relative;
        z-index: 1040;
        padding-left: 0;
    }

    .dotsDiv .navbar-toggler {
        height: 38px;
        padding: 0 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        margin: 0;
        line-height: 1;
        flex-direction: column;
    }

    .dotsDiv .navbar-toggler:focus {
        outline: none;
    }

    .dotsDiv .navbar-collapse {
        margin: 0;
    }

    .dotsDiv #navigation.profile-nav {
        display: flex;
        align-items: center;
        flex: 0 0 auto;
        width: auto;
        min-width: 0;
        margin: 0;
        padding: 0;
        background: transparent;
        border: 0;
        box-shadow: none;
    }

    .dotsDiv #navigation.profile-nav .navbar-nav {
        display: flex;
        flex-direction: row;
        align-items: center;
        margin: 0 !important;
        padding: 0;
    }

    .dropdown.nav-item {
        position: relative;
    }

    .dropdown-navbar {
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 1051;
    }

    /* Ensure dropdown aligns to right edge */
    @media (max-width: 991px) {
        .headerRow > .header-left {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
            padding-right: 8px;
        }

        .headerRow > .header-actions {
            flex: 1 1 auto !important;
            width: auto !important;
            max-width: none !important;
        }

        .logo-col {
            flex: 0 0 auto !important;
            max-width: none !important;
        }

        .left-toggler-container {
            gap: 8px;
        }

        .left-toggler-container .pageTitleContainer {
            display: none;
        }

        .dotsDiv .navbar-collapse {
            position: static;
            right: auto;
            top: auto;
            background: transparent;
            margin: 0;
            flex-basis: auto;
        }

        .dotsDiv #navigation.profile-nav {
            position: static !important;
            display: flex !important;
            align-items: center !important;
            flex: 0 0 auto !important;
            flex-basis: auto !important;
            flex-grow: 0 !important;
            width: auto !important;
            max-width: none !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            transform: none !important;
        }

        .dotsDiv .navbar-collapse.collapse,
        .dotsDiv .navbar-collapse.collapsing,
        .dotsDiv .navbar-collapse.collapse.show {
            display: flex !important;
            align-items: center;
            visibility: visible;
            height: auto !important;
            width: auto !important;
        }

        .headerRow {
            align-items: center;
            position: relative;
        }

        .header-actions .header-search {
            flex: 1 1 180px;
            max-width: 200px;
            min-width: 110px;
        }

        .header-actions-inner {
            gap: 8px;
            width: 100%;
            margin-left: 0;
        }

        .dotsDiv {
            position: static;
            width: auto;
            max-width: none;
            flex: 0 0 auto;
            padding: 0;
            margin: 0;
            height: auto;
        }

        .dotsDiv .navbar-toggler {
            display: none !important;
            padding: 0 6px;
        }

        .dotsDiv .navbar-collapse .dropdown.nav-item > a.dropdown-toggle {
            display: inline-flex !important;
            align-items: center;
        }

        .dotsDiv .navbar-collapse .dropdown.nav-item {
            position: static;
        }

        .dotsDiv #navigation.profile-nav .navbar-nav {
            flex-direction: row !important;
            align-items: center !important;
        }

        .dotsDiv .navbar-nav {
            margin-left: 0 !important;
            align-items: center;
        }
    }

    /* Fix 3 dots positioning on phone */
    @media (max-width: 767px) {
        #sidebar-hamburger {
            height: 34px;
            padding: 0 6px;
        }

        .header-actions .header-search {
            flex: 1 1 140px;
            max-width: 170px;
            min-width: 95px;
        }

        .header-actions .header-search #wrapp input[type="text"] {
            height: 34px;
            font-size: 12px;
            padding: 6px 10px;
        }

        .dotsDiv {
            display: flex;
            align-items: center;
            height: auto;
            margin: 0;
        }

        .dotsDiv .navbar-collapse {
            top: auto;
        }
    }

    /* Close button styling */
    .close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        color: #6b7280;
        font-size: 20px;
        cursor: pointer;
        z-index: 10;
        transition: color 0.2s ease;
        line-height: 1;
    }

    .close-btn:hover {
        color: #374151;
    }

    /* Permissions toggle styling */
    .permissions-toggle {
        background: none;
        border: none;
        color: #6b7280;
        font-size: 13px;
        cursor: pointer;
        padding: 6px 0;
        margin-top: 8px;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .permissions-toggle:hover {
        color: #374151;
    }

    .permissions-toggle i {
        margin-left: 6px;
        font-size: 12px;
        transition: transform 0.2s ease;
    }

    .permissions-toggle.expanded i {
        transform: rotate(180deg);
    }

    .permissions-list {
        margin-top: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        display: none;
        max-height: 150px;
        overflow-y: auto;
    }

    .permissions-list div {
        color: #374151;
        font-size: 12px;
        padding: 4px 0;
        text-transform: capitalize;
        border-bottom: 1px solid #f3f4f6;
    }

    .permissions-list div:last-child {
        border-bottom: none;
    }

    /* Override navbar-nav styles for profile dropdown */
    .dotsDiv .navbar-nav {
        position: static !important;
        background: transparent !important;
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
    }

    /* Profile toggle button */
    .profile-toggle-btn {
        display: inline-flex !important;
        align-items: center !important;
        padding: 4px !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        cursor: pointer;
    }

    /* Remove Bootstrap caret */
    .profile-toggle-btn::after {
        display: none !important;
    }

    .profile-toggle-btn:hover,
    .profile-toggle-btn:focus {
        background: transparent !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .profile-toggle-btn .photo {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
        box-shadow: none;
        transition: border-color 0.2s ease;
    }

    .profile-toggle-btn:hover .photo {
        border-color: #2b7b7d;
    }

    .profile-toggle-btn .photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Dropdown container - high z-index to show above filters */
    .dotsDiv .dropdown.nav-item {
        position: relative;
        display: flex;
        align-items: center;
        z-index: 1100;
    }

    @media (max-width: 991px) {
        .profile-toggle-btn .photo {
            width: 32px;
            height: 32px;
        }

        .dotsDiv,
        .dotsDiv .navbar-collapse,
        .dotsDiv #navigation.profile-nav,
        .dotsDiv .dropdown.nav-item,
        .dotsDiv .profile-toggle-btn {
            position: static !important;
        }

        .dotsDiv .dropdown.nav-item {
            position: relative !important;
            z-index: 1100;
        }
    }

    @media (max-width: 767px) {
        .profile-toggle-btn .photo {
            width: 30px;
            height: 30px;
        }
    }

</style>

@php
$permissions = safe_permissions();
$user = Auth::user();
$defaultAvatar = asset('assets/images/avatars/default.png');
$profileImage = $defaultAvatar;
if ($user) {
    $avatarPath = $user->avatar_path;
    if (preg_match('~^https?://~i', $avatarPath)) {
        $profileImage = $avatarPath;
    } else {
        $profileImage = asset(str_replace('\\', '/', ltrim($avatarPath, '/')));
    }
}
@endphp
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent sigma-app-header">
    <div class="container-fluid">
        <div class="row headerRow">

            <!-- Logo and title -->
            <div class="col-lg-7 col-md-7 col-sm-5 col-3 header-left">
                <div class="left-toggler-container" id="left-toggler">

                        <!-- Logo and mobile bars -->
                    <div class="logo-col" style="position: relative; z-index: 1050;">
                        <div class="mobile-brand-group">
                            <div class="navbar-toggle d-inline d-lg-none">
                                <button type="button" class="navbar-toggler" id="sidebar-hamburger">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </div>
                            <a class="navbar-brand logo-navbar d-lg-none" href="{{ route('home') }}" style="margin: 0; position: relative; z-index: 1051;">
                                <img class ="logo" src="{{ asset('assets') }}/images/logo_horiz.svg" />
                            </a>
                        </div>
                    </div>

                        <!--Page Title -->
                    <div class=" col-sm-9 col-lg-6 pageTitleContainer">
                        <span class="navbar-brand pageTitle" style="font-weight: 800;">{{$pageSlug ?? "SIGMA"}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-7 col-9 header-actions">
                <div class="header-actions-inner">
                    <form action="{{route('global-search')}}"
                          method="GET" class="header-search">
                        <div id="wrapp" class="searchBox2" >
                            <div  class="SBF2">
                                <input id="search" name="searchText" type="text" placeholder="Patient Name?">
                                <span id="search_submit"  ></span>
                            </div>
                        </div>
                    </form>
{{--                    <x-weather-widget></x-weather-widget>--}}
                    <div class="dotsDiv">
                        <span class="navbar-brand pageTitle pageTitleMobile">{{$pageSlug ?? "SIGMA"}}</span>
                        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}" >
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="navbar-collapse profile-nav" id="navigation">
                    <ul class="navbar-nav ml-auto">

                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link profile-toggle-btn" data-toggle="dropdown">
                                <div class="photo">
                                    <img src="{{ $profileImage }}" onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';" alt="{{ $user ? ($user->first_name . ' ' . $user->last_name) : __('Profile Photo') }}">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-navbar user-dropdown-menu">
                                <li class="nav-link user-info-header">
                                    <div class="user-info-content">
                                        <div class="user-avatar-dropdown">
                                            <img src="{{ $profileImage }}" onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';" alt="">
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                            @if(Auth::user()->type === 'admin' || collect($permissions)->contains('name', 'admin'))
                                                <div class="admin-badge">Administrator</div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                <div class="menu-options">
                                    <li class="menu-item table-widths-menu-item">
                                        <a href="#" class="menu-link" onclick="event.preventDefault(); openTableWidthsPanel();">
                                            <i class="fas fa-columns"></i>
                                            Table Widths
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('user-settings') }}" class="menu-link">
                                            <i class="fas fa-sliders-h"></i>
                                            User Settings
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('logout') }}" class="menu-link logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i>
                                            {{ __('Log out') }}
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </li>

                    </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</nav>
<script>
    document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
            //your function call here
            document.searchFrom.submit();
        }
    }

    function closeDropdown() {
        // Close user dropdown in both desktop and mobile layouts.
        if (window.jQuery) {
            var $toggle = $('.dotsDiv .dropdown.nav-item > a.dropdown-toggle');
            if ($toggle.length) {
                $toggle.dropdown('hide');
            }
        }

        // Legacy fallback for old collapse-based behavior.
        var dropdown = document.getElementById('navigation');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }

    function togglePermissions() {
        var permissionsList = document.getElementById('permissions-list');
        if (permissionsList.style.display === 'none' || permissionsList.style.display === '') {
            permissionsList.style.display = 'block';
        } else {
            permissionsList.style.display = 'none';
        }
    }

    function openTableWidthsPanel() {
        var panel = document.getElementById('columnConfigPanel');
        if (panel) {
            panel.classList.add('active');
            closeDropdown();
        }
    }

    // Hide table widths menu item on non-dashboard pages
    document.addEventListener('DOMContentLoaded', function() {
        var panel = document.getElementById('columnConfigPanel');
        var menuItems = document.querySelectorAll('.table-widths-menu-item');
        if (!panel) {
            menuItems.forEach(function(item) {
                item.style.display = 'none';
            });
        }

        var dropdownToggle = document.querySelector('.dropdown.nav-item > a.dropdown-toggle');

        if (dropdownToggle) {
            dropdownToggle.setAttribute('data-toggle', 'dropdown');
        }
    });
</script>
