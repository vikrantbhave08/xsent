<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xsent-Dashboard</title>
    <!-- Font - Noto Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <!-- <link href="{{ asset('assets/dist/css/remixicon.css') }}" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/flatpickr.min.css') }}">

    <link href="{{ asset('assets/dist/css/sidebar-nav.css') }}" rel="stylesheet">


</head>

<body>
    <div class="page-wrapper chiller-theme toggled">
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">

                <div class="sidebar-menu">
                    <ul class="nav menu">
                        <li>
                            <a class="nav-link dashboard" href="{{ url('/admin/dashboard') }}">
                                <span class="icon dashboard-icon"></span>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link requests" href="{{ url('/admin/requests') }}">
                                <span class="icon request-icon"></span>
                                <span>Requests</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link register_uesrs" href="{{ url('/admin/register-users') }}">
                                <span class="icon register-user-icon"></span>
                                <span>Registered Users</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link getall_transactions" href="{{ url('/admin/getall-transactions') }}">                              
                                <span class="icon transactions-icon"></span>
                                <span>Transactions</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link complaints" href="{{ url('/admin/complaints') }}">
                                <span class="icon complaint-icon"></span>
                                <span>Complaints</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sidebar-footer p-3">
                <small>Copyright &copy; 2022.</small>
            </div>
        </nav>
        <main class="page-content">
            <div class="page-heading">
                <div class="container-fluid">

                    <div class="row d-flex align-items-center">
                        <div class="col-lg-9 col-md-9 col-9 d-flex align-items-center">
                            <div class="float-start">
                                <a href="#" class="navbar-brand">
                                    <img src="{{ asset('assets/dist/images/web-logo.png') }}" />
                                </a>
                            </div>
                            <div>
                                <a id="show-sidebar" class="btn btn-dark toggleBtn" href="#">
                                    <img src="{{ asset('assets/dist/images/menu-icon.png') }}" />
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-3">
                            <div class="dropdown  float-end">
                                <button class="btn btn-secondary dropdown-toggle d-flex align-items-center"
                                    type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <div class="user-info">
                                        <span class="user-name">Mohammed Obaid</span>
                                    </div>
                                    <div class="user-pic d-flex justify-content-center align-items-center">
                                        <p>MO</p>
                                    </div>
                                    <i class="ri-arrow-drop-down-line"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <!-- <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a> -->
                                    <a class="dropdown-item" href="{{ url('/admin/logout') }}">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          <script> 
                // Import the functions you need from the SDKs you need
                import { initializeApp } from "firebase/app";
                import { getAnalytics } from "firebase/analytics";
                // TODO: Add SDKs for Firebase products that you want to use
                // https://firebase.google.com/docs/web/setup#available-libraries

                // Your web app's Firebase configuration
                // For Firebase JS SDK v7.20.0 and later, measurementId is optional
                const firebaseConfig = {
                apiKey: "AIzaSyB3C_aKpbVKTt_8ff_WqoVPsGXAlEUZBIU",
                authDomain: "xsent-57c7a.firebaseapp.com",
                projectId: "xsent-57c7a",
                storageBucket: "xsent-57c7a.appspot.com",
                messagingSenderId: "43330800927",
                appId: "1:43330800927:web:6bfb8d79fdbeb0f6b2742a",
                measurementId: "G-46EN96JTYT"
                };

                // Initialize Firebase
                const app = initializeApp(firebaseConfig);
                const analytics = getAnalytics(app);

            </script>