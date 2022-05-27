<script src="{{ asset('js/swall.js') }}"></script>
<script src="{{ asset('all.js') }}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
<script>
    $("#success-alert").fadeTo(15000, 500).slideUp(15000, function() {
        $("#success-alert").slideUp(15000);
    });
    $(".alert").fadeTo(15000, 500).slideUp(500, function() {
        $(".alert").slideUp(15000);
    });

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }
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
                url: "{{ route('notification') }}",
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
                        let url =
                            '{{ route('penunjukan_pekerjaan.notification', ':id') }}';
                        url = url.replace(':id', val.id);

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

                            let url =
                                '{{ route('penunjukan_pekerjaan.notification', ':id') }}';
                            url = url.replace(':id', val.id);

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
                                        // alert("Mohon allow notification");
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
<script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>

@stack('script')

@stack('form')
@stack('scriptdinamis')
