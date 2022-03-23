importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyAiIdOVXPc1C90tWcDrpG984rzidIgU9Kk",
    authDomain: "pdam-work-order.firebaseapp.com",
    projectId: "pdam-work-order",
    storageBucket: "pdam-work-order.appspot.com",
    messagingSenderId: "167105139450",
    appId: "1:167105139450:web:cf92428440b90382686f43",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function ({
    data: { title, body, icon },
}) {
    return self.registration.showNotification(title, { body, icon });
});
