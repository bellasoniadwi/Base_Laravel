<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-firestore.js"></script>
<script>
    // Initialize Firebase
    var firebaseConfig = {
        apiKey: "AIzaSyB4dI2Feq6SPpG0Ln14t1sNb-GqD_8vkqY",
        authDomain: "project-sinarindo.firebaseapp.com",
        projectId: "project-sinarindo",
        storageBucket: "project-sinarindo.appspot.com",
        messagingSenderId: "874679851467",
        appId: "1:874679851467:web:7c1bd747299d1a2c2d3e4e",
        measurementId: "G-JQEGQ518XG"
    };
    firebase.initializeApp(config);
    var facebookProvider = new firebase.auth.FacebookAuthProvider();
    var googleProvider = new firebase.auth.GoogleAuthProvider();
    var facebookCallbackLink = '/login/facebook/callback';
    var googleCallbackLink = '/login/google/callback';
    async function socialSignin(provider) {
        var socialProvider = null;
        if (provider == "facebook") {
            socialProvider = facebookProvider;
            document.getElementById('social-login-form').action = facebookCallbackLink;
        } else if (provider == "google") {
            socialProvider = googleProvider;
            document.getElementById('social-login-form').action = googleCallbackLink;
        } else {
            return;
        }
        firebase.auth().signInWithPopup(socialProvider).then(function(result) {
            result.user.getIdToken().then(function(result) {
                document.getElementById('social-login-tokenId').value = result;
                document.getElementById('social-login-form').submit();
            });
        }).catch(function(error) {
            // do error handling
            console.log(error);
        });
    }
</script>
