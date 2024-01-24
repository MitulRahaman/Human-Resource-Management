<script src="{{ asset('backend/js/oneui.core.min.js') }}"></script>

<!--
    OneUI JS

    Custom functionality including Blocks/Layout API as well as other vital and optional helpers
    webpack is putting everything together at assets/_js/main/app.js
-->
<script src="{{ asset('backend/js/oneui.app.min.js') }}"></script>

<!-- Page JS Plugins -->
<script src="{{ asset('backend/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/chart.js/Chart.bundle.min.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('backend/js/pages/be_pages_dashboard.min.js') }}"></script>

<!-- Page JS Helpers (jQuery Sparkline Plugins) -->
<script>jQuery(function(){One.helpers(['sparkline']);});</script>
@yield('js_after')
