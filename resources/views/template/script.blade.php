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
    // if ('serviceWorker' in navigator) {
    //     window.addEventListener('load', () => {
    //         navigator.serviceWorker.register('{{ asset('firebase-messaging-sw.js') }}');
    //     });
    // }
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
                success: function(result) {
                    let content = '';
                    let modul = '';
                    const {
                        total,
                        data
                    } = result.data;
                    $('#total_notification').text(total);
                    $.each(data, function(i, val) {
                        let id = val.id;
                        let lenght = $('#modul_' + id).length;
                        let url = '{{ route('aduan.notification', ':id') }}';
                        url = url.replace(':id', val.modul_id);

                        content = `<a href="${url}" class="media"  id="modul_${val.id}">
                        <span class="d-flex">
                            <i class="ik ik-bell"></i>
                        </span>
                        <span class="media-body">
                            <span class="heading-font-family media-heading">${val.title}</span>
                            <span class="media-content">${val.body}</span>
                        </span>
                    </a>`;
                        if (val != null) {
                            let body = val.body;
                            let title = val.title;
                            let modul_id = val.modul_id;

                            let url = '{{ route('aduan.notification', ':id') }}';
                            url = url.replace(':id', val.modul_id);

                            console.log(lenght);
                            if (lenght === 0) {
                                $('#notifikasi').append(content);

                                let granted = false;
                                let icon = "{{ asset('img/logo.png') }}";

                                if (body && title && url) {
                                    let permission = Notification.permission;
                                    if (permission === "granted") {
                                        showNotification(body, title,
                                            url);
                                    } else if (permission === "default") {
                                        requestAndShowPermission(body, title, url);
                                    } else {
                                        alert("Use normal alert");
                                    }

                                    function showNotification(body, title, url) {

                                        let notification = new Notification(title, {
                                            body,
                                            icon
                                        });
                                        notification.onclick = () => {
                                            notification.close();
                                            window.open(url);
                                            window.parent.focus();
                                        }
                                    }

                                    function requestAndShowPermission(body, title,
                                        url) {
                                        Notification.requestPermission(function(
                                            permission) {
                                            if (permission === "granted") {
                                                showNotification(body, title,
                                                    url);
                                            }
                                        });
                                    }
                                }

                            }
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
