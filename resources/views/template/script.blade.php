<script src="{{ asset('js/swall.js') }}"></script>
<script src="{{ asset('all.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
<script>
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
        $("#success-alert").slideUp(500);
    });
    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert").slideUp(6000);
    });
</script>


<script src="{{ asset('dist/js/theme.js') }}"></script>
{{-- <script src="{{ asset('js/chat.js') }}"></script> --}}
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

{{-- <script src="{{ asset('firebase-messaging-sw.js') }}"></script> --}}

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset('firebase-messaging-sw.js') }}');
        });
    }
</script>
@stack('script')

@stack('form')
@stack('scriptdinamis')
