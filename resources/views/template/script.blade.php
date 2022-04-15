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
                success: function(data) {
                    let content = '';
                    let modul = '';
                    $.each(data.data, function(i, val) {
                        let url = '{{ route('aduan.notification', ':id') }}';
                        url = url.replace(':id', val.modul_id);
                        let body = val.body;
                        let title = val.title;

                        content += `<a href="${url}" class="media"  id="modul_${val.modul_id}">
                        <span class="d-flex">
                            <i class="ik ik-bell"></i>
                        </span>
                        <span class="media-body">
                            <span class="heading-font-family media-heading">${val.title}</span>
                            <span class="media-content">${val.body}</span>
                        </span>
                    </a>`;
                        console.log(val);
                        if (val != null) {
                            console.log(val.modul_id);
                            let modul_id = val.modul_id;
                            if ($('#modul_' + modul_id).length === 0) {
                                $('#notifikasi').append(content);
                                console.log(body);
                                console.log(title);
                                console.log(url);
                                // (async () => {
                                //     // create and show the notification
                                //     const showNotification = (title, body,
                                //         url) => {
                                //         // create a new notification

                                //         let icon =
                                //             "{{ asset('img/logo.png') }}";

                                //         const notification =
                                //             new Notification(title, {
                                //                 body: body,
                                //                 icon: icon
                                //             });

                                //         // close the notification after 10 seconds
                                //         setTimeout(() => {
                                //             notification.close();
                                //         }, 10 * 1000);

                                //         // navigate to a URL when clicked
                                //         notification.addEventListener(
                                //             'click', () => {

                                //                 window.open(url);
                                //             });
                                //     }

                                //     // show an error message
                                //     const showError = () => {
                                //         const error = document
                                //             .querySelector('.error');
                                //         error.style.display = 'block';
                                //         error.textContent =
                                //             'You blocked the notifications';
                                //     }

                                //     // check notification permission
                                //     let granted = false;

                                //     if (Notification.permission === 'granted') {
                                //         granted = true;
                                //     } else if (Notification.permission !==
                                //         'denied') {
                                //         let permission = await Notification
                                //             .requestPermission();
                                //         granted = permission === 'granted' ?
                                //             true : false;
                                //     }

                                //     // show notification or error
                                //     granted ? showNotification() : showError();

                                // })();
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
