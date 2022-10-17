
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
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Id :</li>
                                                        <li class="list-inline-item data-label"> {{ !empty($complaint_details) ? $complaint_details['complaintid'] : 0 }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint From :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ? $complaint_details['first_name']." ".$complaint_details['last_name'] : 0 }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Reason :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ? $complaint_details['reason_name'] : '' }}
                                                            in wallet</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item form-label">Complaint Date :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ?  date('d F Y | h:i A',strtotime($complaint_details['created_at'])) : '' }} </li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item form-label">Complaint Image :</li>
                                                        <li class="list-inline-item data-label">
                                                        @if(!empty($complaint_details['complaint_img']))
                                                                <img                                                                     
                                                                    class="image_thumb"
                                                                    style="cursor:pointer"
                                                                    src="{{ url('/public/images').$complaint_details['complaint_img'] }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#complaint_img"
                                                                    imgid="#complaint_image"
                                                                    width="20px" height="100%">
                                                         @endif
                                                         </li>
                                                    </ul>
                                                </div>
                                                <!-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                                                    <ul class="list-inline mb-0">
                                                        <li class="list-inline-item form-label">Transaction Type :</li>
                                                        <li class="list-inline-item data-label"><span class="me-1"><i
                                                                    class="ri-arrow-left-right-line"></i></span> Wallet
                                                            to Wallet</li>
                                                    </ul>
                                                </div> -->
                                            </div>
                                        </div>

                                        <!-- <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                            <label class="form-label">Reason</label>
                                            <input type="text" class="form-control" placeholder="Pending"> -->
                                            <!-- <div class="custom-dropdown">
                                                <select id="status">
                                                    <option value=""></option>
                                                    <option value="status1">Status 1</option>
                                                    <option value="status2">Status 2</option>
                                                    <option value="status3">Status 3</option>
                                                    <option value="status4">Status 4</option>
                                                </select>
                                            </div> -->
                                        <!-- </div> -->

                                    </div>

                                </div>

                                <!-- TRANSACTION HISTORY -->
                                <div class="mt-3">
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                            <h4 class="page-title">Transaction History</h4>
                                        </div>
                                        <!-- <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                            <div class="calender-view ">
                                                <input type=" text" class="form-control calender" id="date_from"
                                                    placeholder="Select date">
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="content-wrapper transaction-history">
                                        <div class="table-design">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12">
                                                    <div>
                                                        <table id="UserDetails" class="row-border" style="width:100%">

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
                                                                                <td>{{ $month_data['from_user']==0 ? 'Top up from Bank Account' : 'Transfered from wallet to Bank Account' }} </td>
                                                                                <td class="strongdata">AED {{ $month_data['amount'] }} <span
                                                                                        class="ms-2"> <i class="{{ $month_data['from_user']==0 ? 'ri-arrow-left-down-line' : 'ri-arrow-right-up-line' }}"></i></span>
                                                                                </td>
                                                                            </tr>
                                                                           
                                                                            @endforeach

                                                                        </tbody>

                                                                        @endforeach

                                                                        @endif 

                                                              @if(!empty($transaction['child']))

                                                                        @foreach($transaction['child'] as $month=>$month_transaction)
                                                                       
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="3">{{ $month }}</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                        @foreach($month_transaction as $key=>$month_data)                                                                         
                                                                                                                                               
                                                                            <tr>
                                                                                <td>{{ date('d/m/Y',strtotime($month_data['created_at']))." | ".date('h:i A',strtotime($month_data['created_at'])) }}</td>
                                                                                <td>{{ $month_data['from_user']==$complaint_details['by_user'] ? 'Paid to shop' : 'Parent trasfer to child' }} </td>
                                                                                <td class="strongdata">AED {{ $month_data['credit'] }} <span
                                                                                        class="ms-2"> <i class="{{ $month_data['from_user']==$complaint_details['by_user'] ? 'ri-arrow-right-up-line' : 'ri-arrow-left-down-line'  }}"></i></span>
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

                        </div>
                    </div>
                </div>
            </main>

        </main>
    </div>




     <!--Complaint Image Modal -->
     <div class="modal fade" id="complaint_img" tabindex="-1" aria-labelledby="payremarkLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header gradient">
                            <h5 class="modal-title" id="payremarkLabel">Complaint Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="payment_form">
                            @csrf
                            <input type="hidden" id="amt_request_id"  name="amt_request_id">
                            <input type="hidden" id="bank_detail_id"  name="bank_detail_id">
                        <div class="modal-body">
                                <div class="row">
                                  
                                <img 
                                    style="cursor:pointer"
                                    src=""
                                    id="complaint_image"
                                    width="500px" >

                                </div>                                
                            </div>                           
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <!-- <button type="submit" class="btn btn-primary">Pay</button> -->
                            </div>
                        </form>
                    </div>
                </div>
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

            $(".image_thumb").click(function(){                
                     $($(this).attr("imgid")).attr('src',this.src);                
            });

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