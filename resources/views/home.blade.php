@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase.js"></script>
    <script>
        $(document).ready(function(){
            const config = {
                apiKey: "AIzaSyB1At72oAcihfSa4p7KSYy_E3-Nk1VAXtw",
                authDomain: "taqsema.firebaseapp.com",
                databaseURL: "https://taqsema.firebaseio.com",
                projectId: "taqsema",
                storageBucket: "taqsema.appspot.com",
                messagingSenderId: "194276851086",
                appId: "1:194276851086:web:94e1ae1d34db4508bf38df",
                measurementId: "G-HX9KMF81H0"

            };
            firebase.initializeApp(config);
            const messaging = firebase.messaging();

            messaging.requestPermission()
            .then(function() {
            console.log('Notification permission granted.');
            return messaging.getToken();
            })
            .then(function(token) {
            console.log(token); // Display user token
            })
            .catch(function(err) { // Happen if user deney permission
            console.log('Unable to get permission to notify.', err);
            });


            messaging.onMessage(function(payload) {
                console.log('onMessage',payload);
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });

            // navigator.serviceWorker.register('./public/firebase-messaging-sw.js').then(registration => {
            //     firebase.messaging().useServiceWorker(registration)
            // })
        });
    </script>
