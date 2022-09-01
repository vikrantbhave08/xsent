

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
                                    <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                        <h4 class="page-title">Complaints</h4>
                                    </div>
                                    <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                        <div class="calender-view">
                                            <input type=" text" class="form-control calender" id="date_from"
                                                placeholder="Select date">
                                        </div>

                                    </div>
                                </div>

                                <div class="table-design">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div>
                                                <table id="complaint" class="row-border" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Sr. No</th>
                                                            <th>Complaint Id</th>
                                                            <th>Date</th>
                                                            <th>Complaint From</th>
                                                            <th>Complaint Reason</th>
                                                            <th>Transaction Type</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>01</td>
                                                            <td>541023</td>
                                                            <td>12 July 22</td>
                                                            <td>Amarion Saladin</td>
                                                            <td>Amount has not received in wallet</td>
                                                            <td><i class="ri-arrow-left-right-line"></i></td>
                                                            <td><button class="pending-btn">Pending</button></td>
                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>02</td>
                                                            <td>985327</td>
                                                            <td>8 july 22</td>
                                                            <td>Maysa Shahid</td>
                                                            <td>Amount has not received in bank account</td>
                                                            <td><i class="ri-arrow-left-down-line"></i></td>
                                                            <td><button class="pending-btn">Pending</button></td>
                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>03</td>
                                                            <td>894107</td>
                                                            <td>5 july 22</td>
                                                            <td>Anaum Aly</td>
                                                            <td>Amount has debited twice from wallet</td>
                                                            <td><i class="ri-arrow-right-up-line"></i></td>
                                                            <td><button class="resolved-btn">Resolved</button></td>
                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>04</td>
                                                            <td>325689</td>
                                                            <td>1 july 22</td>
                                                            <td>Sued Baluch</td>
                                                            <td>Amount has not received in wallet</td>
                                                            <td><i class="ri-arrow-left-right-line"></i></td>
                                                            <td><button class="pending-btn">Pending</button></td>
                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>05</td>
                                                            <td>894107</td>
                                                            <td>30 june 22</td>
                                                            <td>Aasira Fares</td>
                                                            <td>Amount has not received in wallet</td>
                                                            <td><i class="ri-arrow-left-right-line"></i></td>
                                                            <td><button class="resolved-btn">Resolved</button></td>
                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span>
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
            </main>

        </main>
    </div>


    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>/ -->
  
    <script src="{{ asset('assets/dist/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script> -->
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <!-- <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script> -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>
    <script src="{{ asset('assets/dist/js/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/dist/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/chart.js') }}"></script> 
 

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
            $("#reason").select2({
                placeholder: "Select your reason",
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#sendremarkModal')
            });
            $('#complaint').DataTable({
                scrollY: 200,
                scrollX: true,
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