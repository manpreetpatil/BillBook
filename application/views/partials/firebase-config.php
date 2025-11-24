<script>
    // Firebase Configuration
    const firebaseConfig = {
        apiKey: "<?php echo $firebase_config['apiKey']; ?>",
        authDomain: "<?php echo $firebase_config['authDomain']; ?>",
        projectId: "<?php echo $firebase_config['projectId']; ?>",
        storageBucket: "<?php echo $firebase_config['storageBucket']; ?>",
        messagingSenderId: "<?php echo $firebase_config['messagingSenderId']; ?>",
        appId: "<?php echo $firebase_config['appId']; ?>"
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
</script>