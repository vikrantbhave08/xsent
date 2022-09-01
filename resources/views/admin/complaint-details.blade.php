
@include('admin/header')
<link href="{{ asset('assets/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">


            <main class="content-holder">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="main-wrapper">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-sm-6">
                                        <h4 class="page-title">Complaint Details</h4>
                                    </div>

                                </div>
                                <div class="content-wrapper">
                                    <div class="row">
                                        <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Id :</li>
                                                        <li class="list-inline-item data-label"> 541023</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint From :</li>
                                                        <li class="list-inline-item data-label">Amarion Saladin</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Reason :</li>
                                                        <li class="list-inline-item data-label">Amount has not received
                                                            in wallet</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item form-label">Complaint Date :</li>
                                                        <li class="list-inline-item data-label">12 July
                                                            2022 | 10.30 am</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item form-label">Transaction Type :</li>
                                                        <li class="list-inline-item data-label"><span class="me-1"><i
                                                                    class="ri-arrow-left-right-line"></i></span> Wallet
                                                            to Wallet</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                            <label class="form-label">Reason</label>
                                            <input type="text" class="form-control" placeholder="Pending">
                                            <!-- <div class="custom-dropdown">
                                                <select id="status">
                                                    <option value=""></option>
                                                    <option value="status1">Status 1</option>
                                                    <option value="status2">Status 2</option>
                                                    <option value="status3">Status 3</option>
                                                    <option value="status4">Status 4</option>
                                                </select>
                                            </div> -->
                                        </div>

                                    </div>

                                </div>

                                <!-- TRANSACTION HISTORY -->
                                <div class="mt-3">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                            <h4 class="page-title">Transaction History</h4>
                                        </div>
                                        <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                            <div class="calender-view ">
                                                <input type=" text" class="form-control calender" id="date_from"
                                                    placeholder="Select date">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="content-wrapper transaction-history">
                                        <div class="table-design">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12">
                                                    <div>
                                                        <table id="UserDetails" class="row-border" style="width:100%">
                                                            <h5 class="table-month-title">July </h5>
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Description</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>12/07/2022</td>
                                                                    <td>Top up from Bank Account </td>
                                                                    <td class="strongdata">AED 1500.00 <span
                                                                            class="ms-2"><i
                                                                                class="ri-arrow-left-down-line"></i></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>5/07/2022</td>
                                                                    <td>Top up from Bank Account </td>
                                                                    <td class="strongdata">AED 850.00 <span
                                                                            class="ms-2"><i
                                                                                class="ri-arrow-left-down-line"></i></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>1/07/2022</td>
                                                                    <td>Transfered from wallet to Bank Account </td>
                                                                    <td class="strongdata"><strong>AED
                                                                            1200.00</strong><span class="ms-2"><i
                                                                                class="ri-arrow-right-up-line"></i></span>
                                                                    </td>
                                                                </tr>

                                                            </tbody>

                                                        </table>
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
            </main>

        </main>
    </div>


    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

    
    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>    
    <script src="{{ asset('assets/dist/js/moment.min.js') }}"></script>
    

    <script>

        $('.complaints').addClass('active');

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
        $(document).ready(function () {
            $("#status").select2({
                placeholder: "Select your reason",
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#sendremarkModal')
            });
            $('#UserDetails').DataTable({
                // "bPaginate": false,
                "ordering": false,
                "bLengthChange": false,
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


    </script>
</body>

</html>