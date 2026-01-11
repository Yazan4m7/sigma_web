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
        justify-content: space-between  !important;
        flex-wrap: nowrap !important;
        background-color:transparent;
        padding: 0;

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
        justify-content: flex-start;
        position: relative;
        height: 100%;
        padding: 0;
        margin: 0;
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
        width: 200px;
        background: rgba(255, 255, 255, 0.95);
        position: relative;
        z-index: 1;
        transition: all .3s cubic-bezier(0.4, 0.0, 0.2, 1);
        cursor: text;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
        width: 240px;
        border-color: #2b7b7d;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(43, 123, 125, 0.15);
    }

    .SBF2 {
        position: relative;
        display: flex;
        align-items: center;

    }

    #wrapp #search_submit {
        display: none;
    }

    /* Responsive search box */
    @media (max-width: 991px) {
        #wrapp input[type="text"] {
            width: 160px;
            font-size: 13px;
        }

        #wrapp input[type="text"]:focus {
            width: 180px;
        }
    }

    @media (max-width: 768px) {
        #wrapp input[type="text"] {
            width: 140px;
            max-width: 180px;
        }

        #wrapp input[type="text"]:focus {
            width: 160px;
            max-width: 200px;
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
            display: none;
        }

        .pageTitleMobile {
            display: inline-flex;
            align-items: center;
        }

        .dotsDiv {
            gap: 8px;
        }
    }

    @media screen and (max-width: 768px) {
        .pageTitleMobile {
            display: none !important;
        }
    }

    /* User Dropdown Styling */
    .user-dropdown-menu {
        min-width: 280px;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 0;
        margin-top: 8px;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
        background: white;
        overflow: hidden;
    }

    .user-info-header {
        padding: 0 !important;
        margin: 0 !important;
        cursor: default;
    }

    .user-info-header:hover {
        background: transparent !important;
    }

    .user-info-content {
        display: flex;
        align-items: center;
        padding: 20px;
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .user-avatar-large {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .user-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo.placeholder-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        color: #6b7280;
    }

    .photo.placeholder-avatar i {
        font-size: 16px;
        line-height: 1;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        color: #6b7280;
        font-size: 24px;
        margin: 0;
        padding: 0;
    }

    .avatar-placeholder i {
        margin: 0;
        padding: 0;
        line-height: 1;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        margin-left: 16px;
        flex: 1;
    }

    .user-name {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.3;
        color: #111827;
        margin-bottom: 4px;
    }

    .user-dropdown-menu .dropdown-divider {
        margin: 0;
        border-color: rgba(0, 0, 0, 0.06);
    }

    .user-dropdown-menu .logout-link {
        color: #374151;
        font-weight: 500;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        background: white;
    }

    .user-dropdown-menu .logout-link i {
        margin-right: 12px;
        font-size: 16px;
        color: #6b7280;
    }

    .user-dropdown-menu .logout-link:hover {
        background-color: #f9fafb;
        color: #111827;
        padding-left: 24px;
    }

    .user-dropdown-menu .logout-link:hover i {
        color: #374151;
    }

    .headerRow {
        display: flex;
        align-items: baseline;
    }

    .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .header-actions-inner {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: nowrap;
        width: 100%;
    }

    .header-actions .header-search {
        flex: 0 1 200px;
        max-width: 240px;
        min-width: 0;
    }

    .header-actions .header-search #wrapp {
        width: 100%;
        justify-content: flex-end;
    }

    .header-actions .header-search #wrapp input[type="text"] {
        width: 100%;
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
        padding: 8px;
        border: none;
        background: transparent;
        margin: 0;
    }

    .dotsDiv .navbar-toggler:focus {
        outline: none;
    }

    .dotsDiv .navbar-collapse {
        margin: 0;
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
        .dotsDiv .navbar-collapse {
            position: fixed;
            right: 15px;
            top: 70px;
            background: transparent;
        }

        .headerRow {
            align-items: center;
            position: relative;
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
            padding: 6px;
        }

        .dotsDiv .navbar-collapse.show .dropdown.nav-item > a.dropdown-toggle {
            display: none;
        }

        .dotsDiv .navbar-collapse.show .user-dropdown-menu {
            display: block;
            position: static;
            float: none;
            min-width: 160px;
            box-shadow: none;
            border: 0;
            margin-top: 0;
        }

        .dotsDiv .navbar-collapse.show .user-dropdown-menu .user-info-header,
        .dotsDiv .navbar-collapse.show .user-dropdown-menu .dropdown-divider,
        .dotsDiv .navbar-collapse.show .user-dropdown-menu .close-btn {
            display: none !important;
        }

        .dotsDiv .navbar-collapse.show .user-dropdown-menu .logout-link {
            justify-content: center;
            padding: 12px 16px;
        }
    }

    /* Fix 3 dots positioning on phone */
    @media (max-width: 767px) {
        .dotsDiv {
            display: flex;
            align-items: center;
            height: auto;
            margin: 0;
        }

        .dotsDiv .navbar-toggler {
            margin: 0;
        }

        .dotsDiv .navbar-collapse {
            top: 65px;
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

</style>

@php
$permissions = safe_permissions();
$user = Auth::user();
$hasProfilePhoto = $user && $user->has_photo;
$profileImage = $hasProfilePhoto ? asset('users/' . $user->id . '/profile_picture.png') : null;
@endphp
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent sigma-app-header">
    <div class="container-fluid noPadOnMobile">
        <div class="row headerRow">

            <!-- Logo and title -->
            <div class="col-lg-7 col-md-7 col-sm-5 col-3 noPadOnMobile" style="padding-left: 0">
                <div class="container-fluid noPadOnMobile" style="padding-left: 0;">
                    <div class="row left-toggler-container" id="left-toggler" style="background-color:transparent;padding: 0;flex-wrap: nowrap;align-items: center;">

                        <!-- Logo and mobile bars -->
                    <div class=" logo-col noPadOnMobile" style="position: relative; z-index: 1050;">
                        <div class="" style="display: flex; align-items: center; gap: 10px;">
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
                    <div class=" col-sm-9 col-lg-6 noPadOnMobile pageTitleContainer">
                        <span class="navbar-brand pageTitle" style="font-weight: 800;">{{$pageSlug ?? "SIGMA"}}</span>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-7 col-9 noPadOnMobile header-actions">
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
                    <div class="dotsDiv noPadOnMobile mb-1">
                        <span class="navbar-brand pageTitle pageTitleMobile">{{$pageSlug ?? "SIGMA"}}</span>
                        <button style= "flex-grow: 3" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}" >
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="collapse navbar-collapse" style = "flex-grow: 3" id="navigation">
                    <ul class="navbar-nav ml-auto">

                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" style=" background-color:transparent;border:none;padding: 8px 12px">
                                @if($hasProfilePhoto)
                                    <div class="photo">
                                        <img src="{{ $profileImage }}" alt="{{ $user ? ($user->first_name . ' ' . $user->last_name) : __('Profile Photo') }}">
                                    </div>
                                @else
                                    <div class="photo placeholder-avatar" aria-hidden="true">
                                        <i class="fa fa-user"></i>
                                    </div>
                                @endif

                                <p class="d-lg-none"></p>
                            </a>
                            <ul class="dropdown-menu dropdown-navbar user-dropdown-menu">
                                <button class="close-btn" onclick="closeDropdown()">&times;</button>
                                <li class="nav-link user-info-header">
                                    <div class="user-info-content">

                                        <div class="user-details">
                                            <span class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                            @if(Auth::user()->type === 'admin' || collect($permissions)->contains('name', 'admin'))
                                                <div class="admin-badge" style="color: #059669; font-weight: 600; font-size: 12px; margin-top: 4px;">You are Admin</div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li class="nav-link">
                                    <a href="{{ route('logout') }}" class="nav-item dropdown-item logout-link" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                        <i class="tim-icons icon-button-power"></i>
                                        {{ __('Log out') }}
                                    </a>
                                </li>
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
        // Close the dropdown by removing the 'show' class from the collapse element
        var dropdown = document.getElementById('navigation');
        if (dropdown.classList.contains('show')) {
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
</script>
