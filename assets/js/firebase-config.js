// Firebase Configuration
// Get your config from: https://console.firebase.google.com
// Project Settings > General > Your apps > Web app

const firebaseConfig = {
    apiKey: "<?php echo $this->config->item('firebase')['apiKey']; ?>",
    authDomain: "<?php echo $this->config->item('firebase')['authDomain']; ?>",
    projectId: "<?php echo $this->config->item('firebase')['projectId']; ?>",
    storageBucket: "<?php echo $this->config->item('firebase')['storageBucket']; ?>",
    messagingSenderId: "<?php echo $this->config->item('firebase')['messagingSenderId']; ?>",
    appId: "<?php echo $this->config->item('firebase')['appId']; ?>"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Get Firebase Auth instance
const auth = firebase.auth();

// Configure Google Sign-In
const googleProvider = new firebase.auth.GoogleAuthProvider();
googleProvider.setCustomParameters({
    prompt: 'select_account'
});
