<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}">
    <title>
        Login
    </title>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-200">
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-100"
            style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="login-form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                                    </div>
                                    {{-- <p class="mt-4 text-sm text-center">
                                        Don't have an account?
                                        <a href="../pages/sign-up.html" class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.1.0') }}"></script>
    
    <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-auth.js"></script>
      <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-firestore.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
</body>

</html>
