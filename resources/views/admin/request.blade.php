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
                                        <h4 class="page-title">Requests</h4>
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
                                                <table id="request" class="row-border check" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Sr. No</th>
                                                            <th>Request From</th>
                                                            <th>Request Amount</th>
                                                            <th>User Type</th>
                                                            <th>Wallet Balance</th>
                                                            <th>Request At</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        @foreach($requests as $key=>$val)
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>@if($val['by_role']==2) {{ $val['shop_name'] }} @else {{ $val['first_name']." ".$val['last_name'] }} @endif</td>
                                                            <td>{{ 'AED'.' '.$val['request_amount'] }}</td>
                                                            <td>{{ $val['by_role']==2 ? "Owner" : "Parent" }}</td>
                                                            <td>{{ 'AED'.' '.$val['balance'] }}</td>
                                                            <td>{{ date('Y-m-d',strtotime($val['created_at'])) }}</td>
                                                            <td><button class="btn btn-sm table-btn">Pay</button></td>
                                                        </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>01</td>
                                                            <td>Cafe Peter</td>
                                                            <td>AED 2000</td>
                                                            <td>AED 2200</td>
                                                            <td>541023</td>
                                                            <td><button class="btn btn-sm table-btn">Pay</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>02</td>
                                                            <td>Pimlico</td>
                                                            <td>AED 1500</td>
                                                            <td>AED 1900</td>
                                                            <td>985327</td>
                                                            <td><button class="btn btn-sm table-btn">Pay</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>03</td>
                                                            <td>TGS Stationery</td>
                                                            <td>AED 1420</td>
                                                            <td>AED 1800</td>
                                                            <td>894107</td>
                                                            <td><button class="btn btn-sm table-btn">Pay</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>04</td>
                                                            <td>Water Restaurant</td>
                                                            <td>AED 2100</td>
                                                            <td>AED 1500</td>
                                                            <td>325689</td>
                                                            <td><button class="btn btn-sm remark-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#sendremarkModal">Send
                                                                    Remark</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>05</td>
                                                            <td>TGS Stationery</td>
                                                            <td>AED 1420</td>
                                                            <td>AED 1800</td>
                                                            <td>894107</td>
                                                            <td><button class="btn btn-sm table-btn">Pay</button></td>
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


            <!--Send Remark Modal -->
            <div class="modal fade" id="sendremarkModal" tabindex="-1" aria-labelledby="sendremarkLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header gradient">
                            <h5 class="modal-title" id="sendremarkLabel">Transfer Money To Bank Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Wallet Balance</label><br />
                                        <label class="data-label">AED 1500</label>
                                    </div>
                                    <div class="col-sm-6 mb-3">

                                        <label class="form-label">Request Amount</label><br />
                                        <label class="data-label">AED 2100</label>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Wallet Balance</label><br />
                                        <label class="data-label">AED 1500</label>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">Reason</label>
                                        <div class="custom-dropdown">
                                            <select id="reason">
                                                <option value=""></option>
                                                <option value="Reason1">Reason 1</option>
                                                <option value="Reason2">Reason 2</option>
                                                <option value="Reason3">Reason 3</option>
                                                <option value="Reason4">Reason 4</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div>
                                            <label class="form-label">Remark</label>
                                            <textarea class="form-control" placeholder="Enter remark"></textarea>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->


    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>
    


    <script>
        
        $('.requests').addClass('active');

        // SELECT DATE
        flatpickr("#date_from", {

            dateFormat: 'Y-m-d',

            onChange: function (selectedDates, dateStr, instance) {

                get_data_by_date();

            },

        });
        function resizeData() {
            setTimeout(function () {
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
            },1000)
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
            $('#request').DataTable({
                "searching": true,
                scrollY: 600,
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