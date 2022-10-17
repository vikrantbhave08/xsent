

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
                                                            <th>Complaint Image</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($complaints as $sr=>$complaint)
                                                        <tr>
                                                            <td><input type="checkbox"></td>
                                                            <td>{{ $sr+1 }}</td>
                                                            <td>{{ $complaint['complaintid'] }}</td>
                                                            <td>{{ date('d M Y',strtotime($complaint['created_at']))  }}</td>
                                                            <td>{{ $complaint['first_name'].' '.$complaint['last_name'] }}</td>
                                                            <td>{{ $complaint['reason_name'] }}</td>
                                                            <td>
                                                                @if($complaint['reason_id']==1) 
                                                                <!-- amount has not recieved in wallet -->
                                                                <i class="ri-arrow-left-right-line"></i>
                                                                @elseif($complaint['reason_id']==2)
                                                                <!-- amount has not recieved in bank account -->
                                                                <i class="ri-arrow-left-down-line"></i>
                                                                @elseif($complaint['reason_id']==3)
                                                                <i class="ri-arrow-right-up-line"></i>
                                                                <!-- amount has debited twice from wallet -->
                                                                @endif


                                                            </td>

                                                            <td>
                                                                @if(!empty($complaint['complaint_img']))
                                                                <img 
                                                                    class="image_thumb"
                                                                    style="cursor:pointer"
                                                                    src="{{ url('/public/images').$complaint['complaint_img'] }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#complaint_img"
                                                                    imgid="#complaint_image"
                                                                    width="20px" height="100%">
                                                                    @endif
                                                                </td>

                                                            <td>
                                                            @if($complaint['is_active']==0) 
                                                            <button class="pending-btn">Pending</button>
                                                            @elseif($complaint['is_active']==1)
                                                            <button class="resolved-btn">Resolved</button>
                                                            @endif
                                                            </td>

                                                            <td>
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/complaint-details') }}?complaint_id={{ base64_encode($complaint['complaint_id']) }}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <!-- <span class="table-icon"><i
                                                                        class="ri-delete-bin-line"></i></span> -->
                                                            </td>
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

            $(".image_thumb").click(function(){                
                     $($(this).attr("imgid")).attr('src',this.src);                
            });

            $("#reason").select2({
                placeholder: "Select your reason",
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#sendremarkModal')
            });
            $('#complaint').DataTable({
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