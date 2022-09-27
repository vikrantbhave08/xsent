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
                                                            <h4 class="count">AED {{ $admin_recieve[0]['parent_pays'] }} </h4>
                                                            <p class="count-title">Total Amount Received from
                                                                Parent</p>
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
                                                            <h4 class="count">AED {{ $admin_recieve[0]['shops_earn'] }} </h4>
                                                            <p class="count-title">Total Amount Paid To
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
        var cat_sales_by_month=JSON.parse('<?php if(!empty($cat_sales_by_month)){ echo json_encode($cat_sales_by_month); } else { echo json_encode(array()); }?>');
        console.log(shops);
        console.log(shops_earn);
        console.log(cat_sales_by_month);

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


        
var options = {
    // series: [{
    //     name: 'Food',
    //     data: [40, 50, 55, 55, 60, 55, 65, 75, 89, 55, 45, 60]
    // }, {
    //     name: 'Stationery',
    //     data: [53, 32, 33, 52, 13, 44, 32, 56, 32, 87, 90, 67]
    // },
    // {
    //     name: 'Library',
    //     data: [45, 67, 98, 34, 67, 53, 32, 33, 52, 13, 44, 32]
    // }],
    series:cat_sales_by_month,
    chart: {
        type: 'bar',
        height: 430
    },
    plotOptions: {
        bar: {
            vertical: true,
            dataLabels: {
                position: 'top',
            },
        }
    },
    dataLabels: {
        enabled: false,
        offsetX: -6,
        style: {
            fontSize: '12px',
            colors: ['#fff']
        }
    },
    stroke: {
        show: true,
        width: 1,
        colors: ['#fff']
    },
    tooltip: {
        shared: true,
        intersect: false

    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: {
        title: {
            text: 'AED'
        }
    },
};

var chart = new ApexCharts(document.querySelector("#financeStatistic"), options);
chart.render();



        

var options = {
    series: [{
        name: 'AED',
        data: shops_earn
    }],
    chart: {
        type: 'bar',
        height: 350,
        // width: 750
    },
    colors: ["#66b860"],
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
    },

    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: shops,
    },
    yaxis: {
        title: {
            text: 'AED'
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#shopStatistic"), options);
chart.render();

    </script>
</body>

</html>