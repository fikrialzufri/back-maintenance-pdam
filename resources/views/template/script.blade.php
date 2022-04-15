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

{{-- Notifikasi --}}
<script>
    $(function() {
        

        

        let dataNotifikasi = [];
        let notifikasi = function() {
            $.ajax({
                type: 'GET',
                url: '/notification',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    let content = '';
                    $.each(data.data, function (i, val) {
                        var url = '{{ route("aduan.notification", ":id") }}';
                        url = url.replace(':id', val.modul_id);
                        content += `<a href="${url}" class="media">
                        <span class="d-flex" id="modul_${val.modul_id}">
                            <i class="ik ik-bell"></i> 
                        </span>
                        <span class="media-body">
                            <span class="heading-font-family media-heading">${val.title}</span> 
                            <span class="media-content">${val.body}</span>
                        </span>
                    </a>`;
                    let modul_id = $('#modul_', + val.modul_id).length;
                    console.log(modul_id);
                    if ($('#modul_', + val.modul_id).length === 0) {
                        $('#notifikasi').append(content);
                    }
                    });
                }
            });
        };
        notifikasi();
        setInterval(() => {
            notifikasi();
        }, 5000);
    });
</script>
@stack('script')

@stack('form')
@stack('scriptdinamis')
