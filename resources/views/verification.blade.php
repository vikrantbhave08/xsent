<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Xsent</title>
    <!-- Font - Noto Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet" />
        
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- <link href="{{ asset('assets/dist/css/remixicon.css') }}" rel="stylesheet" /> -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/dist/css/verification.css') }}" rel="stylesheet" />
</head>

<body id="page-top">

    <!-- Masthead-->
    <section class="top-content gradient">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <!-- <div class="d-flex align-items-center justify-content-center"> -->
                    <div class="logo-img">
                        <img src="{{ asset('assets/dist/images/verification_xsent-logo.png') }}">
                    </div>
                    <h5 style="@if(!$flag) {{ 'color:#e12f2f' }} @endif">{{ $msg }}</h5>
                    <p>@if($status==3) {{ "Looks like the verification link has expired. Not to worry we can send the link again" }} @endif</p>
                    <!-- </div> -->

                </div>
            </div>
        </div>

    </section>
    <!-- About Section-->
    <section class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    <div class="banner-image">
                        <img src="{{ asset('assets/dist/images/verification_login.png') }}">
                    </div>
                    @if($status==3) <span></span> <button onclick=send_verification_link("{{ $access_tkn }}") class="btn btn-primary gradient">Resend Verification Link</button> @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="contact-details">
                        <ul class="list-style-inline">
                            <li class="list-inline-item d-flex align-items-center justify-content-center">
                                <span class="icon"><i class="ri-phone-line"></i></span>
                                <span class="data pb-2"> Contact: +97152301468</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="contact-details">
                        <ul class="list-style-inline">
                            <li class="list-inline-item d-flex align-items-center justify-content-center">
                                <span class="icon"><i class="ri-mail-line"></i></span>
                                <span class="data pb-2"> Email:connect_us@xsent.com</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer gradient">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <p>Copyright@2022 | X-sent.com</p>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <p class=" float-end">All Rights are Reserved</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap core JS-->
    <!-- <script src=" js/jquery-1.11.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script> -->
        
    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <!-- jQuery base library needed -->
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script>

        function send_verification_link(access_tkn)
        {

            redUrl = "{{url('/api/send-verification-link')}}?access_tkn="+access_tkn;             

            $.ajax({
                url: redUrl,
                type: 'GET',
                dataType: 'json',
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function (res) {

                    console.log(res);

                    // if (res.flag) {

                    //     $(".login-err").css("color", "green");
                    //     $(".login-err").html(res.msg);
                    //     setTimeout(function () {
                    //         window.location.href = "{{ url('/admin/dashboard') }}";
                    //     }, 3000);



                    // } else {
                    //     // fp1.close();
                    //     $(".login-err").css("color", "red");
                    //     $(".login-err").html(res.msg);
                    //     setTimeout(function () {
                    //         // location.reload();
                    //     }, 3000);
                    // }


                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });
        }
        
        </script>



</body>

</html>