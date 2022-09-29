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
                                                            <th>Request From</th>
                                                            <th>Email</th>
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
                                                            <td>@if($val['by_role']==2) 
                                                                {{ $val['shop_name']." (".$val['first_name']." ".$val['last_name'].")" }} 
                                                                @else 
                                                                {{ $val['first_name']." ".$val['last_name'] }}                                                             
                                                                @endif</td>
                                                            <td>{{ $val['email'] }}</td>
                                                            <td>{{ 'AED'.' '.$val['request_amount'] }}</td>
                                                            <td>{{ $val['by_role']==2 ? "Owner" : "Parent" }}</td>
                                                            <td>{{ 'AED'.' '.$val['balance'] }}</td>
                                                            <td>{{ date('Y-m-d',strtotime($val['created_at'])) }}</td>
                                                            <td>
                                                            @if($val['status']==0)

                                                                <button class="btn btn-sm table-btn"
                                                                    onclick="get_payment_details({{$val['amt_request_id']}})"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#transfer_money">Pay</button>

                                                            @else
                                                            
                                                            <span class="badge bg-success" style="font-size: 1.2em;">Paid</span>

                                                            @endif

                                                                </td>
                                                            <!-- <td><button onclick="get_payment_details({{$val['amt_request_id']}})" class="btn btn-sm table-btn">Pay</button></td> -->
                                                        </tr>
                                                        @endforeach
                                                        <!-- <tr>
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
                                                                    data-bs-target="#transfer_money">Send
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
                                                        </tr> -->
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
            <div class="modal fade" id="transfer_money" tabindex="-1" aria-labelledby="payremarkLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header gradient">
                            <h5 class="modal-title" id="payremarkLabel">Transfer Money To Bank Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="payment_form">
                            @csrf
                            <input type="hidden" id="amt_request_id"  name="amt_request_id">
                            <input type="hidden" id="bank_detail_id"  name="bank_detail_id">
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Request From</label><br />
                                        <label class="data-label req_from"></label>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">User Type</label><br />
                                        <label class="data-label user_type"></label>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Request Amount</label><br />
                                        <label class="data-label req_amt"></label>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Wallet Balance</label><br />
                                        <label class="data-label wallet_balance"></label>
                                    </div>
                                </div>

                                <div class="row">
                                   <div class="col-sm-6 mb-3">
                                        <label class="form-label">Bank Name</label><br />
                                        <label class="data-label bank_name">bank name</label>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Account No</label><br />
                                        <label class="data-label account_no">acc no</label>
                                    </div>
                                   <div class="col-sm-6 mb-3">
                                        <label class="form-label">IBAN No</label><br />
                                        <label class="data-label iban_no">iban no</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">Transfered By</label>
                                        <div class="custom-dropdown">
                                            <select name="transfered_by" id="transfer_by">
                                                <!-- <option value=""></option> -->
                                                <option value="Net Banking">Net Banking</option>
                                                <!-- <option value="Cash">Cash</option> -->
                                                <!-- <option value="Reason3">Reason 3</option> -->
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-6">
                                        <label class="form-label" for="bank_name">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" id="bank_name" value="">
                                    </div> -->
                                    <div class="col-sm-6">
                                        <label class="form-label" for="txn_id">Transaction Id</label>
                                        <input type="text" name="txn_id" class="form-control" id="txn_id" value="">
                                    </div>

                                    <div class="col-sm-6">
                                        <div>
                                            <label class="form-label">Remark</label>
                                            <textarea class="form-control" placeholder="Enter remark"></textarea>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div style="text-align: center;">
                            <span class="payment-err"></span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Pay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>


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
        
        $('.requests').addClass('active');


        // SELECT DATE
        const $flatpickr = flatpickr("#date_from", {

            dateFormat: 'Y-m-d',
            defaultDate: "{{ !empty($search_date) ? $search_date : 'null' }}",
            onChange: function (selectedDates, dateStr, instance) {
                // get_data_by_date();
                // alert();
                window.location.href = "{{url('/admin/requests')}}?search_date="+dateStr;
            },

        });

       function clear_date()
       {       
        // $flatpickr.clear();
        window.location.href = "{{url('/admin/requests')}}";
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

        function get_payment_details(request_id)
        {
            $('.payment-err, .error').html("");

            redUrl = "{{url('/admin/get-payment-details')}}";             

                $.ajax({
                    url: redUrl,
                    type: 'post',
                    data: {request_id:request_id,  "_token": "{{ csrf_token() }}"},
                    dataType: 'json',
                    // contentType: false, // The content type used when sending data to the server.while using formdata
                    // cache: false, // To unable request pages to be cached .while using formdata
                    // processData: false, // To send DOMDocument or non processed data file it is set to false .while using formdata
                    success: function (res) {

                        console.log(res);

                        if (res.status) {

                                var res= res.pay_details;

                                if(res.bank_detail_id==null)
                                {
                                    $(".payment-err").css("color", "red");
                                    $(".payment-err").html("Bank details not added.");
                                }

                                $(".req_from").html(res.first_name+" "+res.last_name);
                                $(".user_type").html(res.role_name);
                                $(".req_amt").html("AED "+res.request_amount);
                                $(".wallet_balance").html("AED "+res.balance);
                                $(".bank_name").html("AED "+res.bank_name);
                                $(".account_no").html("AED "+res.account_no);
                                $(".iban_no").html("AED "+res.iban_no);
                                $("#amt_request_id").val(res.amt_request_id);
                                $("#bank_detail_id").val(res.bank_detail_id);  
                                
                            }


                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });

        }


        $("#payment_form").validate({
            rules: {                
                // bank_name: {
                //     required: true
                // },
                txn_id: {
                    required: true
                },

            },
            messages: {
                           
                // bank_name: {
                //     required: "Enter Bank Name"
                // },
                txn_id: {
                    required: "Enter Transaction Id"
                },
            },          
            submitHandler: function (form, message) {
             
                    redUrl = "{{url('/admin/add-payment')}}";             

                $.ajax({
                    url: redUrl,
                    type: 'post',
                    data: new FormData(form),
                    dataType: 'json',
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false, // To send DOMDocument or non processed data file it is set to false
                    success: function (res) {

                        if (res.status) {

                            $(".payment-err").css("color", "green");
                            $(".payment-err").html(res.msg);
                            setTimeout(function () {
                              location.reload();
                            }, 2000);

                        } else {
                            // fp1.close();
                            $(".payment-err").css("color", "red");
                            $(".payment-err").html(res.msg);
                            setTimeout(function () {
                                // location.reload();
                            }, 3000);
                        }


                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });

            }
        }); 


    </script>
</body>

</html>