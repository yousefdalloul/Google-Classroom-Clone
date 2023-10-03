// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getMessaging, getToken } from "firebase/messaging"; // Corrected import

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDXVgaMuoAFJ8zWd9_27_TX3QiM-HJZi_I",
    authDomain: "classrooms-da2ad.firebaseapp.com",
    projectId: "classrooms-da2ad",
    storageBucket: "classrooms-da2ad.appspot.com",
    messagingSenderId: "1024951162698",
    appId: "1:1024951162698:web:8e30e25802528c9d5f93b3",
    measurementId: "G-XXNBQ8DM0X"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const messaging = getMessaging(app); // Initialize Firebase Messaging

// Add the public key generated from the console here.
getToken(messaging, { vapidKey: "BJwunK77wjvNLZFZ9hb_2gs-5B4nHVtwEWoZFaNLtDxQLwSZeK1mln2aIlUS0BWRzBTJXo9KGS-GRyOiww86CsE" }) // Replace with your VAPID key
    .then((currentToken) => {
        console.log(currentToken);
        if (currentToken) {
            $.post('/api/v1/devices', {
                token: currentToken
            }, () => { })
        } else {
            // Show permission request UI
            console.log('No registration token available. Request permission to generate one.');
            // ...
        }
    }).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    // ...
});
