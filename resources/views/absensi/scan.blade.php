@extends('template.app')
@section('title', 'Scan QR Code Absensi')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    @if (Session::has('success'))
                        <div class="alert bg-success alert-success text-white" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @elseif (Session::has('error'))
                        <div class="alert bg-danger alert-danger text-white" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div id="qr-reader" style="width:300px"></div>
                    <div id="qr-reader-results"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    var resultContainer = document.getElementById('qr-reader-results');
    var lastResult, countResults = 0;

    function onScanSuccess(decodedText, decodedResult) {
        if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            // Handle on success condition with the decoded message.
            // var $tes = 'https://192.168.1.19/absensi-qr/public/absen/' + decodedText + '/hadir';
            var $tes = decodedText;
            window.location.href = $tes;
        }
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", {
            fps: 10,
            qrbox: 250
        });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush