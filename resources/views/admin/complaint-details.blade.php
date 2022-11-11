
@include('admin/header')
<link href="{{ asset('assets/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">

    <style>
        .badge{
            font-size:14px;
        }

        .error{
             color:red
              }
</style>


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
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Id :</li>
                                                        <li class="list-inline-item data-label"> {{ !empty($complaint_details) ? $complaint_details['complaintid'] : 0 }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint From :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ? $complaint_details['first_name']." ".$complaint_details['last_name'] : 0 }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Reason :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ? $complaint_details['reason_name'] : '' }}
                                                            </li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Date :</li>
                                                        <li class="list-inline-item data-label">{{ !empty($complaint_details) ?  date('d F Y | h:i A',strtotime($complaint_details['created_at'])) : '' }} </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Transaction Type :</li>
                                                        <li class="list-inline-item data-label">
                                                        @if($complaint_details['reason_id']==1) 
                                                                <!-- amount has not recieved in wallet -->
                                                                <i class="ri-arrow-left-right-line"></i>
                                                                @elseif($complaint_details['reason_id']==2)
                                                                <!-- amount has not recieved in bank account -->
                                                                <i class="ri-arrow-left-down-line"></i>
                                                                @elseif($complaint_details['reason_id']==3)
                                                                <i class="ri-arrow-right-up-line"></i>
                                                                <!-- amount has debited twice from wallet -->
                                                                @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
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
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">
                                                            @if($complaint_details['is_active']==0)
                                                                <button class="btn btn-sm table-btn" type="button"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#admin_remark">Remark
                                                                </button> 
                                                             @elseif($complaint_details['is_active']==1)
                                                             <label class="badge bg-success">Resolved</label>
                                                             @else
                                                             <label class="badge bg-danger">Invalid Complaint</label>
                                                             @endif
                                                                                                                    
                                                        </li>
                                                        <!-- <li class="list-inline-item data-label"><span class="me-1">
                                                        <button class="btn btn-sm remark-btn">Invalid Complaint</button>
                                                        </li> -->
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item form-label">Complaint Details :</li>
                                                        <li class="list-inline-item data-label"> {{ !empty($complaint_details) ? $complaint_details['complaint_details'] : "" }}</li>
                                                    </ul>
                                                </div>
                                            </div>

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
                                        <div class="calender-view">
                                                <input type="text" class="form-control calender" id="date_from"
                                                placeholder="Select date">
                                                <span class="close-icon calender-icon" title="clear" data-clear><i class="ri-calendar-line"></i></span>
                                                <span onclick="clear_date()" class="close-icon closed-icon" title="clear" data-clear><i class="ri-close-line"></i></span>
                                        </div>
                                    </div>

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
                                                                                <td>{{ $month_data['from_user']==0 ? 'Admin sent real money to user' : 'Real money sent to admin' }} </td>
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
    <div class="modal fade" id="admin_remark" tabindex="-1" aria-labelledby="payremarkLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header gradient">
                            <h5 class="modal-title" id="payremarkLabel">Remark</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="complaint_form" name="complaint_form">
                            @csrf

                            <input type="hidden" name="complaint_id" id="complaint_id" value="{{ $complaint_details['complaint_id'] }}">
                           
                        <div class="modal-body">
                               
                               <div class="row">

                               <div class="col-sm-6">
                                        <div>
                                            <label class="form-label">Remark</label>
                                            <textarea class="form-control" name="remark" placeholder="Enter remark"></textarea>
                                        </div>
                                    </div>
                               
                                <div class="col-sm-6 mb-3">
                                <label class="form-label">Invalid Complaint</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="is_invalid" type="checkbox" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Is it invalid complaint ? 
                                        </label>
                                    </div>
                                </div>

                            </div>          
                            </div>          
                            
                            <div style="text-align: center;">
                            <span class="complaint-err"></span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




     <!--Complaint Image Modal -->
     <div class="modal fade" id="complaint_img" tabindex="-1" aria-labelledby="payremarkLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header gradient">
                            <h5 class="modal-title" id="payremarkLabel">Complaint Image</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- <form id="complaint_form"> -->
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
                        <!-- </form> -->
                    </div>
                </div>
            </div>


    <!-- page-content" -->
    </div>
    <!-- page-wrapper -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    
    <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/dist/js/common-script.js') }}"></script>    
    <script src="{{ asset('assets/dist/js/moment.min.js') }}"></script>    

    <script>

        $('.complaints').addClass('active');

       
        var searched_date="{{ !empty($search_date) ? $search_date : '' }}";

                
                                        
            if(searched_date=='')
            {   
                $(".calender-icon").css('display','block');
                $(".closed-icon").css('display','none');

            } else {

                $(".calender-icon").css('display','none');
                $(".closed-icon").css('display','block');
            }  

            flatpickr("#date_from", {

                dateFormat: 'Y-m-d',
                defaultDate: "{{ !empty($search_date) ? $search_date : 'null' }}",
                onChange: function (selectedDates, dateStr, instance) {

                    // get_data_by_date();
                    window.location.href = "{{url('/admin/complaint-details')}}?{{ 'complaint_id='.base64_encode($complaint_details['complaint_id']) }}&search_date="+dateStr;

                },

            });

            function clear_date()
            {       
            // $flatpickr.clear();
            window.location.href = "{{url('/admin/complaint-details')}}?{{ 'complaint_id='.base64_encode($complaint_details['complaint_id']) }}";
            }

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


          


        $("#complaint_form").validate({
            rules: {                
                remark: {
                    required: true
                },
                // txn_id: {
                //     required: true
                // },

            },
            messages: {
                           
                remark: {
                    required: "Enter Remark"
                },
                // txn_id: {
                //     required: "Enter Transaction Id"
                // },
            },          
            submitHandler: function (form, message) {
             
                redUrl = "{{url('/admin/admin-remark')}}";             
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

                            $(".complaint-err").css("color", "green");
                            $(".complaint-err").html(res.msg);
                            setTimeout(function () {
                              location.reload();
                            }, 2000);

                        } else {

                            // fp1.close();
                            $(".complaint-err").css("color", "red");
                            $(".complaint-err").html(res.msg);
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