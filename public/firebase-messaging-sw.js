importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyAEcTgFnE5gzg4QeXqO_blBNGB0h3ZySO8",
    authDomain: "pdam-work-order-3ee03.firebaseapp.com",
    projectId: "pdam-work-order-3ee03",
    storageBucket: "pdam-work-order-3ee03.appspot.com",
    messagingSenderId: "171277949524",
    appId: "1:171277949524:web:a5d04bf00c73851c74ebc1",
    measurementId: "G-H2S25462WF",
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload);
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});
