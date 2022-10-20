


@include('admin/header')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">

            <main class="content-holder">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="main-wrapper">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-sm-6">
                                        <h4 class="page-title">Registered User Details</h4>
                                    </div>

                                </div>
                                <div>
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                                           @if(!empty($shop_user_details['user_role']))
                                            <a class="nav-link active gradient" id="nav-shop-tab" data-bs-toggle="tab"
                                                href="#nav-shop" role="tab" aria-controls="nav-shop"
                                                aria-selected="true">Shop</a>
                                                @endif

                                                @if(!empty($parent_user_details['user_role']))
                                            <a class="nav-link {{ empty($shop_user_details['user_role']) ? 'active' : '' }}  gradient" id="nav-parent-tab" data-bs-toggle="tab"
                                                href="#nav-parent" role="tab" aria-controls="nav-parent"
                                                aria-selected="false">Parent</a>
                                                @endif

                                        </div>
                                    </nav>

                                    @if(!empty($shop_user_details['user_role']))
                                  
                                    <div class="tab-content" id="nav-tabContent">
                                        <!-- SHOP DETAILS -->
                                        <div class="tab-pane fade show active" id="nav-shop" role="tabpanel"
                                            aria-labelledby="nav-shop-tab">
                                            <div class="content-wrapper">
                                                <div class="row">
                                                    <!-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">User Id :</li>
                                                            <li class="list-inline-item data-label"> 541023</li>
                                                        </ul>
                                                    </div> -->
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">User Name :</li>
                                                            <li class="list-inline-item data-label">{{ !empty($shop_user_details) ? $shop_user_details['first_name']." ".$shop_user_details['last_name']  : "" }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Registered As:</li>
                                                            <li class="list-inline-item data-label"> {{ !empty($shop_user_details) ? ( $shop_user_details['user_role']==2 || $shop_user_details['user_role']==3  ? "Shop"  : $shop_user_details['role_name'] ) : ""  }}  </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Wallet Balance:</li>
                                                            <li class="list-inline-item data-label">AED {{ !empty($shop_user_details) ? $shop_user_details['wallet_balance']  : "0" }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Status:</li>
                                                            <li class="list-inline-item data-label"><button
                                                                    class="active-btn">Active</button></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item form-label">Registered On :</li>
                                                            <li class="list-inline-item data-label">{{ !empty($shop_user_details) ? date("d M Y | H:i A",strtotime($shop_user_details['created_at']))  : "" }} 
                                                                </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- <div class="row">
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item form-label">Registered On :</li>
                                                            <li class="list-inline-item data-label">1 July 2022 | 10.30
                                                                am</li>
                                                        </ul>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <!-- TRANSACTION HISTORY -->
                                            <div class="mt-3">
                                                <div class="row d-flex align-items-center mb-4">
                                                    <div class="col-sm-6">
                                                        <h4 class="page-title">Transaction History</h4>
                                                    </div>

                                                    <!-- <div class="col-sm-2 ms-auto">
                                                        <div class="calender-view">
                                                            <input type=" text" class="form-control calender"
                                                                id="date_from" placeholder="Select date">
                                                        </div>
                                                    </div> -->

                                                </div>
                                                <div class="content-wrapper transaction-history" style="height: calc( 100vh - 250px );
                                                overflow-y: auto;">
                                                    <div class="table-design">
                                                        <div class="row">
                                                            <div class="col-xl-12 col-lg-12 col-md-12">
                                                                <div>
                                                                    <table id="shopDetails" class="row-border"
                                                                        style="width:100%">

                                                                        @if(!empty($transaction['shop']))

                                                                        @foreach($transaction['shop'] as $month=>$month_transaction)
                                                                        
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="3">{{ $month }}</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                        @foreach($month_transaction as $key=>$month_data)
                                                                        <!-- <i class="ri-arrow-right-up-line"></i> -->
                                                                        <!-- <i class="ri-arrow-left-down-line"></i> -->
                                                                            <tr>
                                                                                <td>{{ date('d/m/Y',strtotime($month_data['created_at']))." | ".date('h:i A',strtotime($month_data['created_at'])) }}</td>
                                                                                <td>{{ $month_data['from_user']==0 ? 'Admin sent real money to user' : 'Real money sent to admin' }} </td> 
                                                                                <td class="strongdata">AED {{ $month_data['amount'] }} <span
                                                                                        class="ms-2"> <i class="{{ $month_data['from_user']==0 ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line' }}"></i></span>
                                                                                </td>
                                                                            </tr>
                                                                           
                                                                            @endforeach

                                                                        </tbody>
                                                                         
                                                                        @endforeach

                                                                        @endif

                                                                       

                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($parent_user_details['user_role']))
                                        <!-- PARENT DETAILS -->
                                        <div class="tab-pane fade {{ empty($shop_user_details['user_role']) ? 'show active' : '' }}" id="nav-parent" role="tabpanel"
                                            aria-labelledby="nav-parent-tab">
                                            <div class="content-wrapper">
                                                <div class="row">
                                                    <!-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">User Id :</li>
                                                            <li class="list-inline-item data-label"> 541023</li>
                                                        </ul>
                                                    </div> -->
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">User Name :</li>
                                                            <li class="list-inline-item data-label">{{ !empty($parent_user_details) ? $parent_user_details['first_name']." ".$parent_user_details['last_name']  : "" }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Registered As:</li>
                                                            <li class="list-inline-item data-label">{{ !empty($parent_user_details) ? ( $parent_user_details['user_role']==2 || $parent_user_details['user_role']==3  ? "Parent"  : $parent_user_details['role_name'] ) : ""  }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Wallet Balance:</li>
                                                            <li class="list-inline-item data-label"> AED {{ !empty($parent_user_details) ? $parent_user_details['wallet_balance']  : "0" }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item form-label">Status:</li>
                                                            <li class="list-inline-item data-label"><button
                                                                    class="active-btn">Active</button></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item form-label">Registered On :</li>
                                                            <li class="list-inline-item data-label">{{ !empty($parent_user_details) ? date("d M Y | H:i A",strtotime($parent_user_details['created_at']))  : "" }} 
                                                                </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item form-label">Registered On :</li>
                                                            <li class="list-inline-item data-label">1 July 2022 | 10.30
                                                                am</li>
                                                        </ul>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <!-- TRANSACTION HISTORY -->
                                            <div class="mt-3">
                                                <div class="row d-flex align-items-center mb-4">
                                                    <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                                        <h4 class="page-title">Transaction History</h4>
                                                    </div>

                                                    <!-- <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                                        <div class="calender-view">
                                                            <input type=" text" class="form-control calender"
                                                                id="date_from" placeholder="Select date">
                                                        </div>
                                                    </div> -->

                                                </div>
                                                <div class="content-wrapper transaction-history" style="height: calc( 100vh - 250px );
                                                overflow-y: auto;">
                                                    <div class="table-design">
                                                        <div class="row">
                                                            <div class="col-xl-12 col-lg-12 col-md-12">
                                                                <div>
                                                                <table id="shopDetails" class="row-border"
                                                                        style="width:100%">
                                                                </table>
                                                                    <table id="parentDetails" class="row-border"
                                                                        style="width:100%">

                                                                        @if(!empty($transaction['parent']))
                                                                        @foreach($transaction['parent'] as $month=>$month_transaction)
                                                                       
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="3">{{ $month }}</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                        @foreach($month_transaction as $key=>$month_data)
                                                                        <!-- <i class="ri-arrow-right-up-line"></i> -->
                                                                        <!-- <i class="ri-arrow-left-down-line"></i> -->
                                                                            <tr>
                                                                                <td>{{ date('d/m/Y',strtotime($month_data['created_at']))." | ".date('h:i A',strtotime($month_data['created_at'])) }}</td>
                                                                                <td>{{ $month_data['from_user']==0 ? 'Admin sent real money to user' : 'Real money sent to admin' }} </td>
                                                                                <td class="strongdata">AED {{ $month_data['amount'] }} <span
                                                                                        class="ms-2"> <i class="{{ $month_data['from_user']==0 ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line' }}"></i></span>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            @endforeach

                                                                        </tbody>

                                                                        @endforeach

                                                                        @endif


                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                               </div> <!-- Content wrapper transactionnhistory close -->

                                           </div> <!-- Transction history mt-3 close  -->

                                        </div> <!-- Parent Details close  -->

                                        @endif

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
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
   


    
    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>
    <script src="{{ asset('assets/dist/js/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/dist/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/chart.js') }}"></script>

    <script>

$('.register_uesrs').addClass('active');

        // SELECT DATE
        // flatpickr("#date_from", {

        //     dateFormat: 'Y-m-d',

        //     onChange: function (selectedDates, dateStr, instance) {

        //         get_data_by_date();

        //     },

        // });

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
        $(document).ready(function () {
            // $("#reason").select2({
            //     placeholder: "Select your reason",
            //     minimumResultsForSearch: Infinity,
            //     dropdownParent: $('#sendremarkModal')
            // });
            $('#shopDetails').DataTable({
                // "bPaginate": false,
                "ordering": false,
                "bLengthChange": false,
                scrollY: 500,
            scrollX: true,
                "bFilter": false,
                "bInfo": false,
                "bAutoWidth": false,
                language: {
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

        });
        $('#parentDetails').DataTable({
            "bPaginate": true,
            "ordering": false,
            "bLengthChange": false,
            scrollY: 500,
            scrollX: true,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            language: {
                paginate: {
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>'
                }
            }
        });

    </script>
</body>

</html>