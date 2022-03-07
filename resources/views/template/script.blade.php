<script src="{{ asset('js/swall.js') }}"></script>
<script src="{{ asset('all.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
@stack('script')

<script src="{{ asset('dist/js/theme.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script type=" text/javascript">
    $(function() {
        @stack('scriptdinamis')
    });
</script>
@stack('form')
