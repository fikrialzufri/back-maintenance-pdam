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
@stack('script')

<script src="{{ asset('dist/js/theme.js') }}"></script>
{{-- <script src="{{ asset('js/chat.js') }}"></script> --}}
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
{{-- Firebase --}}
<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.6.9/firebase-app.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyAiIdOVXPc1C90tWcDrpG984rzidIgU9Kk",
        authDomain: "pdam-work-order.firebaseapp.com",
        projectId: "pdam-work-order",
        storageBucket: "pdam-work-order.appspot.com",
        messagingSenderId: "167105139450",
        appId: "1:167105139450:web:cf92428440b90382686f43"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
</script>
@stack('form')
@stack('scriptdinamis')
