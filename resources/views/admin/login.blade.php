<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Font - Noto Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <!-- Font - Remix Icons -->
    <link href="{{ asset('assets/dist/css/remixicon.css') }}" rel="stylesheet" />
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet"> -->
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Custom CSS -->
    <link href="{{ asset('assets/dist/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/select2.min.css') }}" rel="stylesheet" />
    <title>Xsent</title>
    <style>
    .error{
        color:red
    }

    .spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: -0.125em;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: .75s linear infinite spinner-border;
    animation: .75s linear infinite spinner-border;
}
        </style>
</head>

<body>
    <div class="main py-4 d-flex align-items-center">
        <div class="container h-100">
            <div class="row h-100">
                <!-- <div class="col-xl-10 offset-xl-1 h-100"> -->
                <div class="col-xl-10 offset-xl-1 col-lg-10 offset-lg-1 col-md-10 offset-md-1">
                    <div class="login-bg h-100">
                        <div class="row h-100">
                            <div class="col-xxl-7 col-xl-7 col-lg-7 col-7 h-100">
                                <div class="left-panel h-100 d-flex flex-column justify-content-center">
                                    <div class="logo-holder">
                                        <img src="{{ asset('assets/dist/images/logo.png') }}" alt="Xsent" class="d-block mx-auto" />
                                    </div>
                                    <div class="login-image-holder">
                                        <img src="{{ asset('assets/dist/images/login.png') }}" alt="Xsent" class="d-block mx-auto img-fluid" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-xl-5 col-lg-5 col-5 h-100 d-flex align-items-center gradient">
                                <div class="right-panel w-100 h-100 py-4 d-flex align-items-center">
                                    <div class=" row">
                                        <div class="card scrollbar">
                                            <div class="card-header text-center py-0">
                                                <h5>Login</h5>

                                            </div>

                                            <form id="login_form" action="" method="post">
                                                @csrf

                                                <input type="hidden" name="user_role" value="{{ $user_role }}">
                                            <div class="card-body pb-0">
                                                <div class="register-form" id="reisterForm">
                                                    <div class="row mt-4">

                                                        <div class="col-xl-12 col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="username">Username</label>
                                                                <input type="text" name="username" id="username" value="" class="form-control"
                                                                    placeholder="Enter username" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Password</label>
                                                                <input type="password" name="password" value="" class="form-control"
                                                                    placeholder="Enter password" />
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-xl-12 col-lg-12">
                                                            <p class="float-end"><a href="login.html">
                                                                    Forgot password ? </a></p>
                                                        </div> -->


                                                    </div>

                                                    <span class="text-center login-err"></span>
                                                   

                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <Button class="btn btn-primary btn-register w-100"
                                                            type="submit">Login
                                                        
                                                            <div class="spinner-border" hidden role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </Button>
                                                    </div>

                                                </div>
                                            </div>

                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <!-- jQuery base library needed -->
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <!-- <script src="js/custom-select.js"></script> -->
    <script src="{{ asset('assets/dist/js/jquery.validate.js') }}"></script>


    <script>
        $("#login_form").validate({
            rules: {                
                username: {
                    required: true
                },
                password: {
                    required: true
                },

            },
            messages: {
                           
                username: {
                    required: "Enter Username"
                },
                password: {
                    required: "Enter Password"
                },
            },          
            submitHandler: function (form, message) {
             
                    redUrl = "{{url('/admin/loginme')}}";      
                    
                    $(".btn-register").prop('disabled',true);
                    $(".spinner-border").prop('hidden',false);

                $.ajax({
                    url: redUrl,
                    type: 'post',
                    data: new FormData(form),
                    dataType: 'json',
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false, // To send DOMDocument or non processed data file it is set to false
                    success: function (res) {

                        if (res.flag) {

                            $(".login-err").css("color", "#ffcb99");
                            $(".login-err").html(res.msg);
                            setTimeout(function () {
                                window.location.href = "{{ url('/admin/dashboard') }}";
                            }, 3000);



                        } else {
                            // fp1.close();
                            $(".login-err").css("color", "red");
                            $(".login-err").html(res.msg);
                            setTimeout(function () {
                                // location.reload();
                            }, 3000);
                        }

                        $(".btn-register").prop('disabled',false);
                        $(".spinner-border").prop('hidden',true);


                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // console.log(xhr.responseJSON);
                        if(xhr.status==419)
                        {
                            $(".login-err").css("color", "red");
                            $(".login-err").html("Server error ! refresh page.");
                        }   
                        $(".btn-register").prop('disabled',false);
                        $(".spinner-border").prop('hidden',true);
                    }
                });

            }
        }); 
    </script>

</body>

</html>
















