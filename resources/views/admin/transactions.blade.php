@include('admin/header')
<link href="{{ asset('assets/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">

    <style>
        .error {
            color:red;
        }      
        </style>

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
                                                <!-- <a class="input-button" title="clear" data-clear>sdfs;f
                                                    <i class="icon-close"></i>
                                                </a> -->
                                                <span onclick="clear_date()" class="close-icon" title="clear" data-clear><i class="ri-close-line"></i></span>
                                        </div>
                                    </div>

                                </div>

                                @csrf

                                <div class="table-design">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div>
                                                <table id="request" class="row-border check" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Sr. No</th>
                                                            <th>Transaction Id</th>
                                                            <th>Debited From</th>
                                                            <th>Credited To</th>
                                                            <th>Amount</th>
                                                            <th>Money Type</th>
                                                            <th>Created Date</th>
                                                            <!-- <th>Action</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        @foreach($transactions as $key=>$trans)
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>{{ isset($trans['pay_txn_id']) ? $trans['pay_txn_id'] : $trans['txn_id'] }}</td>

                                                            

                                                            <td>{{  $trans['from_user']==0 ? 'Admin' : $trans['debited']." (".($trans['from_user']==0 ? 'Admin' : $trans['from_role_name']) .")"  }}</td>
                                                            <td>{{  $trans['user_id']==0 ? 'Admin' : $trans['credited']. " (".($trans['user_id']==0 ? 'Admin' : $trans['to_role_name']) .")"  }}</td>
                                                            <td>   <?php echo isset($trans['pay_txn_id']) ? '<span class="me-2"> <i class="ri-bank-card-fill"></i></span>' : '<span class="me-2"> <i class="ri-hand-coin-fill"></i></span>' ?> {{ 'AED '.$trans['amount']  }}</td>
                                                            <td>{{  isset($trans['pay_txn_id']) ? 'Real Money' : 'Virtual Money'  }}</td>
                                                            <td>{{  $trans['created_at']  }}</td>                                                          
                                                           
                                                        </tr>
                                                        @endforeach
                                                      
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

           
    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->


    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>
    


    <script>
        
        $('.getall_transactions').addClass('active');


        // SELECT DATE
        const $flatpickr = flatpickr("#date_from", {

            dateFormat: 'Y-m-d',
            defaultDate: "{{ !empty($search_date) ? $search_date : 'null' }}",
            onChange: function (selectedDates, dateStr, instance) {
                // get_data_by_date();
                // alert();
                window.location.href = "{{url('/admin/getall-transactions')}}?search_date="+dateStr;
            },

        });

       function clear_date()
       {       
        // $flatpickr.clear();
        window.location.href = "{{url('/admin/getall-transactions')}}";
       }
                       
          

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
          

            $("#transfer_by").select2({
                placeholder: "Select transfer by",
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#transfer_money')
            });

            $('#request').DataTable({
                "searching": true,
                scrollY: 650,
                scrollX: true,
                // "bPaginate": false,
                "ordering": true,
                "bLengthChange": false,
                "bFilter": false,
                "bInfo": false,
                "bAutoWidth": false,
                "pageLength": 15,
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