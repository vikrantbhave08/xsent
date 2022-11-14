
@include('admin/header')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/jquery.dataTables.min.css') }}">
    <!-- <link href="{{ asset('assets/dist/css/checkout.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/dist/sass/main.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
            <main class="content-holder">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="main-wrapper">
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-sm-9 col-md-9 col-lg-10 col-xl-10">
                                        <h4 class="page-title">Registered Users</h4>
                                    </div>
                                    <form id="payment-form" action="{{url('/admin/payment')}}"  method="POST">                                      
                                        @csrf
                                        <button type="submit" id="checkout-button">Checkout</button>
                                    </form>

                                    <!-- <form id="payment-form">
                                    <div id="payment-element">
                                   
                                    </div>
                                    <button id="submit">
                                        <div class="spinner hidden" id="spinner"></div>
                                        <span id="button-text">Pay now</span>
                                    </button>
                                    <div id="payment-message" class="hidden"></div>
                                    </form> -->                                    


                                    <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 ms-auto">
                                        <div class="calender-view">
                                            <input type=" text" class="form-control calender" id="date_from"
                                                placeholder="Select date">
                                            <span class="close-icon calender-icon" title="clear" data-clear><i class="ri-calendar-line"></i></span>
                                            <span onclick="clear_date()" class="close-icon closed-icon" title="clear" data-clear><i class="ri-close-line"></i></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="table-design">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div>
                                            
                                                <table id="registereUser" class="row-border check" style="width:100%">
                                                    <thead>
                                                        <tr>                                                          
                                                            <th>Sr. No</th>
                                                            <!-- <th>User ID</th> -->
                                                            <th>User Name</th>
                                                            <th>Email Id</th>
                                                            <th>Registered As</th>
                                                            <!-- <th>Parent Name</th>
                                                            <th>Parent Email</th> -->
                                                            <th>Wallet Balance</th>
                                                            <th>Created At</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($users as $usr_no => $user)   
                                                       <tr>
                                                            <!-- <td><input type="checkbox"></td>  -->                                                           
                                                            <td>{{ $usr_no+1 }}</td> 
                                                            <!-- <td>541023</td> -->
                                                            <td>{{ $user['first_name'].' '.$user['last_name'] }}</td>
                                                            <td>{{ $user['email'] }}</td>
                                                            <td>{{ $user['role_name'] }}</td>
                                                            <!-- <td>{{ $user['parent_name'] }}</td>
                                                            <td>{{ $user['parent_email'] }}</td> -->
                                                            <td>{{ $user['balance']!=null ? 'AED '.$user['balance'] : 'AED 0' }}</td>
                                                            <td>{{ date('Y-m-d',strtotime($user['created_at'])) }}</td>
                                                            <td><button class="active-btn">Active</button></td>
                                                            <td> 
                                                                <span class="table-icon"><a
                                                                        href="{{ url('/admin/register-user-details?uid=') }}{{ base64_encode($user['user_id'])}}"><i
                                                                            class="ri-eye-line"></i></a></span>
                                                                <!-- <span class="table-icon"><i class="ri-delete-bin-line"></i></span>  -->
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


    <div id="lean-link"></div>

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
    <script src="{{ asset('assets/dist/js/jquery.validate.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <!-- <script src="{{ asset('assets/dist/js/checkout.js') }}"></script> -->

    <script src="https://cdn.leantech.me/link/sdk/web/latest/Lean.min.js"></script>


    <script>

        $('.register_uesrs').addClass('active');

        var searched_date="{{ !empty($search_date) ? $search_date : '' }}";
                                    
        if(searched_date=='')
        {   
            $(".calender-icon").css('display','block');
            $(".closed-icon").css('display','none');

        } else {

            $(".calender-icon").css('display','none');
            $(".closed-icon").css('display','block');
        } 

        // SELECT DATE
        flatpickr("#date_from", {

            dateFormat: 'Y-m-d',
            defaultDate: "{{ !empty($search_date) ? $search_date : 'null' }}",
            onChange: function (selectedDates, dateStr, instance) {

                window.location.href = "{{url('/admin/register-users')}}?search_date="+dateStr;

            },

        });

        function clear_date()
       {       
        // $flatpickr.clear();
        window.location.href = "{{url('/admin/register-users')}}";
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
            $("#reason").select2({
                placeholder: "Select your reason",
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#sendremarkModal')
            });
            $('#registereUser').DataTable({
                "searching": true,
                scrollY: 500,
                scrollX: true,
                // "bPaginate": false,
                "ordering": true,
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