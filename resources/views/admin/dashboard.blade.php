@include('admin/header')
<link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">

            <main class="content-holder">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="main-wrapper">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                        <h4 class="page-title">Dashboard</h4>
                                    </div>
                                    <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                        <div class="calender-view ">
                                            <input type="text" class="form-control calender" id="date_from"
                                                placeholder="Select date">
                                        </div>
                                        <!-- <div class="calender-view d-flex justify-content-around align-items-center">
                                            <span>View</span>
                                            <input type=" text" class="form-control calender" id="date_from"
                                                placeholder="Select date">
                                        </div> -->

                                    </div>
                                </div>
                                <div class="advance-search">
                                    <div class="card shadow-none border-0">
                                        <div class="card-body px-0">
                                            <div class="row">
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                                    <div class="count-box">
                                                        <div
                                                            class="icon-box gradient  blue-gradient d-flex justify-content-center align-items-center">
                                                            <img src="{{ asset('assets/dist/images/icon/register-user.png') }}">
                                                        </div>
                                                        <div class="count-data">
                                                            <h4 class="count">{{ !empty($users) ? count($users)  : 0 }}</h4>
                                                            <p class="count-title">Total Registred Users</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                                    <div class="count-box">
                                                        <div
                                                            class="icon-box gradient  blue-gradient d-flex justify-content-center align-items-center">
                                                            <img src="{{ asset('assets/dist/images/icon/register-shop.png') }}">
                                                        </div>
                                                        <div class="count-data">
                                                            <h4 class="count">{{ !empty($shops) ? count($shops)  : 0 }}</h4>
                                                            <p class="count-title">Total Registred Shops</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                                                    <div class="count-box">
                                                        <div
                                                            class="icon-box gradient  blue-gradient d-flex justify-content-center align-items-center">
                                                            <img src="{{ asset('assets/dist/images/icon/amount-received.png') }}">
                                                        </div>
                                                        <div class="count-data">
                                                            <h4 class="count">AED 2500</h4>
                                                            <p class="count-title">Total Amount Received from
                                                                Shops</p>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="graph">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card shadow-none border-0 mb-3">
                                                <h4 class="content-title">Financial Statistics</h4>
                                                <div class="card-body">
                                                    <div id="financeStatistic"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card shadow-none border-0 mb-3">
                                                <h4 class="content-title">Shop’s Financial Statistics</h4>
                                                <div class="card-body">
                                                    <div id="shopStatistic"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </main>
    </div>


    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->

    <script>

        var shops=JSON.parse('<?php if(!empty($shops)){ echo json_encode(array_column($shops,'shop_name')); } else { echo json_encode(array()); }?>');
        var shops_earn=JSON.parse('<?php if(!empty($shops)){ echo json_encode(array_column($shops,'shops_earn')); } else { echo json_encode(array()); }?>');
        console.log(shops);
        console.log(shops_earn);

     </script>

    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>
    <script src="{{ asset('assets/dist/js/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/dist/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/chart.js') }}"></script>

    <script>

        $('.dashboard').addClass('active');
        // SELECT DATE
        flatpickr("#date_from", {

            dateFormat: 'Y-m-d',

            onChange: function (selectedDates, dateStr, instance) {

                get_data_by_date();

            },

        });
        function resizeData() {
            var winWidth = $(window).width();
            var winHeight = $(window).height();
            var sidebarBrandHeight = $(".sidebar-brand").innerHeight();
            var sidebarHeaderHeight = $(".sidebar-header").innerHeight();
            //Content Holder 
            var pageHeadingHeight = $(".page-heading").innerHeight();
            $(".content-holder").css("margin-top", pageHeadingHeight + "px")

            var calc = winHeight - (sidebarBrandHeight + sidebarHeaderHeight);
            $(".sidebar-menu").height(calc);

            if (winWidth > 1024) {
                $(".page-wrapper").addClass("toggled");
            } else {
                $(".page-wrapper").removeClass("toggled");
            }
            var contentHolder = winHeight - pageHeadingHeight - 15;

            $(".content-holder").height(contentHolder);
        }

        resizeData();

        $(window).resize(function () {
            resizeData();
        })

    </script>
</body>

</html>