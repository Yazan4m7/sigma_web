<style>
    body,
    html, .wrapper {
        height: 100%;
    }

    <!-- tooltop -->
     .tooltipY {
         position: relative;
         display: inline-block;
         border-bottom: 1px dotted black;
     }

    .tooltipY .tooltiptextY {
        visibility: hidden;
        width: auto;
        background-color: #5a6c7d;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;

        /* Fade in tooltip - takes 1 second to go from 0% to 100% opac: */
        opacity: 0;
        transition: opacity 1s;
    }

    .tooltipY:hover .tooltiptextY {
        visibility: visible;
        opacity: 1;
    }
    <!-- End tooltop -->


    <!-- Sidebar -->
    a {
        color: {{config('site_vars.sidebarFirstColor')}} ;
    }
    a:hover, a:focus {
        color: {{config('site_vars.sidebarSecondColor')}};
    }
    .sidebar, .off-canvas-sidebar {
        background: #5a6c7d;
        background:linear-gradient(4deg, #568a8c 0%, #2b7b7d 100%);
    }
    .main-panel {
        border-top: 2px solid #5a6c7d;
    }

    <!-- Buttons -->
    .deleteBtn{
        padding:{{config('site_vars.delBtnHorizPadding')}}px , {{config('site_vars.delBtnVertPadding')}}px;
        background-color:{{config('site_vars.delBtnVertPadding')}};
    }
    .btn-primary {
    {{--background: #37b44a;--}}
    {{--background-image: -webkit-linear-gradient(0deg,  {{config('site_vars.primaryBtnColor1')}} 0%, {{config('site_vars.primaryBtnColor2')}} 100%);--}}
    {{--background-image: -o-linear-gradient(0deg,  {{config('site_vars.primaryBtnColor1')}} 0%, {{config('site_vars.primaryBtnColor2')}} 100%);--}}
    {{--background-image: -moz-linear-gradient(0deg,  {{config('site_vars.primaryBtnColor1')}} 0%, {{config('site_vars.primaryBtnColor2')}} 100%);--}}
    {{--background-image: linear-gradient(0deg,  {{config('site_vars.primaryBtnColor1')}} 0%, {{config('site_vars.primaryBtnColor2')}} 100%);--}}
{{--}--}}
}
    .btn-secondary {
        {{--background-color: {{config('site_vars.secondryBtnColor')}};--}}
    }
    .btn:hover, .btn:focus, .btn-primary:hover, .btn-primary:focus {
        {{--background-color: {{config('site_vars.btnOnHoverColor')}}  !important;--}}
        {{--background-image: linear-gradient(0deg, {{config('site_vars.btnOnHoverColor')}} 0%, {{config('site_vars.btnOnHoverColor')}} 100%);--}}
    }

</style>
