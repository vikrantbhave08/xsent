<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Validator;


use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Shop_transaction_model;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;
use App\Models\Amount_requests_model;
use App\Models\Payment_history_model;

class Requests_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index(Request $request)
    {
        
        $result['search_date']=!empty($request['search_date']) ? $request['search_date'] :'';
        $result['requests']=Amount_requests_model::select('users.email','users.first_name','users.last_name','amount_requests.*','shops.shop_name','wallet.balance')
                                                ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
                                                ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id') 
                                                ->leftjoin('shops', 'amount_requests.by_user', '=', 'shops.owner_id') 
                                                ->whereIn('wallet.user_role',array(2,3))
                                                ->where(function ($query) use ($request) {
                                                    if (!empty($request['search_date'])) $query->whereDate('amount_requests.created_at',$request['search_date']);
                                                 })                                                 
                                                // ->whereIn('amount_requests.by_role',array(2,3))
                                                // ->whereYear('amount_requests.created_at', '=', date('Y'))
                                                // ->whereMonth('amount_requests.created_at',"=",$i)
                                                ->get()->toArray();

                                                // $result['requests']=array();
                                                // echo "<pre>";
                                                // print_r($result);
                                                // exit;

        return view('admin/request',$result);
    }

    public function add_payment(Request $request)
    {  
        $result=array('status'=>false,'msg'=>'Data not found');

        $rules = [           
            'txn_id' => 'required',
            'bank_detail_id' => 'required',
            'amt_request_id' => 'required',
        ];    
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validator=Validator::make($request->all(),$rules,$customMessages);

        if(empty($request['bank_detail_id'])){ return array('status'=>false,'msg'=>'Bank details not added.'); }
    
             
        if($validator->fails())
        {          
            
            $messages=$validator->messages();
            $errors=$messages->all();
           
            $result['errors']=$errors;           

        } else {
           
            $users_request=Amount_requests_model::where('amount_requests.amt_request_id',$request['amt_request_id'])
                                                    ->where('amount_requests.status',0)
                                                    ->first();
                                              
                                               
            if(!empty($users_request))
            {              
                $check_transaction=Payment_history_model::where('pay_txn_id',$request['txn_id'])->first();

                if(empty($check_transaction))
                {
                  $add_payment=array(
                                'pay_txn_id'=>$request['txn_id'],
                                'from_user'=>0,
                                'to_user'=>$users_request->by_user,
                                'to_role'=>$users_request->by_role,
                                'amt_request_id'=>$users_request->amt_request_id,
                                'bank_detail_id'=>$request['bank_detail_id'],
                                'amount'=>$users_request->request_amount,
                                'remark'=>$request['remark'],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=>date('Y-m-d H:i:s')
                                );

                    $payment_added=Payment_history_model::create($add_payment)->payment_id; 
                    if($payment_added)    
                    {
                        $users_request->status=1;
                        $users_request->save();

                        $beneficiery_user = User_model::select('users.*','auth_user.auth_id','auth_user.users_token')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where('auth_user.user_id', $users_request->by_user)
                        ->where('auth_user.user_role', $users_request->by_role)
                        ->first();

                        $notification_body=array(
                            'title'=>'Payment Recieved',
                            'msg'=>'',
                            'body'=>'Xsent has transfered '.$users_request->request_amount.' AED amount to your bank',
                            'to'=>$beneficiery_user->fcm_token, 
                        );

                        Dashboard_controller::send_notification($notification_body);

                       
                             Notifications_model::create([   
                                    'to_user' => $beneficiery_user->user_id,
                                    'notify_of' => 0,
                                    'status' => 0,
                                    'title' => 'Payment Recieved',
                                    'notification_msg' => 'Xsent has transfered '.$users_request->request_amount.' AED amount to your bank',
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                    ])->notification_id;
                       
                        
                        // Wallet_transaction_model::create([          
                        //     'txn_id'=>"txn".md5(date('smdHyi').$users_request->by_user.mt_rand(1111,9999)),
                        //     'from_user' => 0,
                        //     'from_role' => 1,
                        //     'user_id' => $users_request->by_user,
                        //     'to_role' => $users_request->by_role,
                        //     'wallet_id' => 0,
                        //     'credit' => $users_request->request_amount,
                        //     'debit' => '',
                        //     'payment_gate_id' => $payment_added,
                        //     'status_msg' => 'Added money from parent to student',
                        //     'created_at' => date('Y-m-d H:i:s'),
                        //     'updated_at' => date('Y-m-d H:i:s')
                        //     ])->wallet_id;    

                        $result=array('status'=>true,'msg'=>'Payment added successfully');
                    }else{
                        $result=array('status'=>false,'msg'=>'Something went wrong');
                    }
                } else {

                    $result=array('status'=>false,'msg'=>'Transaction id already used');

                }
            } else {
                 $result=array('status'=>false,'msg'=>'Payment already done');
            }
        }

        return $result;
    }

    public function get_payment_details(Request $request)
    {        
        $result=array('status'=>false,'msg'=>'Data not found');
        
        if($request['request_id'])
        {
            $payment_details=array();
            // $result['requests']=Amount_requests_model::select('*')->get()->toArray();
            $payment_details=Amount_requests_model::select('users.first_name','user_roles.role_name','users.last_name','bank_details.account_no','bank_details.bank_name','bank_details.iban_no',
                                                            'amount_requests.*','shops.shop_name','wallet.balance','bank_details.bank_detail_id','bank_details.bank_detail_id')
                                                            ->leftjoin('users', 'amount_requests.by_user', '=', 'users.user_id') 
                                                            ->leftjoin('wallet', 'users.user_id', '=', 'wallet.user_id') 
                                                            ->leftjoin('shops', 'amount_requests.by_user', '=', 'shops.owner_id') 
                                                            ->leftjoin('bank_details', 'amount_requests.by_user', '=', 'bank_details.user_id') 
                                                            ->leftjoin('user_roles', 'amount_requests.by_role', '=', 'user_roles.role_id') 
                                                            ->where('amount_requests.amt_request_id',$request['request_id'])         
                                                            ->first();
                                                            
                                                            $result=array('status'=>true,'msg'=>'Data found','pay_details'=>!empty($payment_details) ? $payment_details->toArray() : array() );
                                                        }
                                                        
                                                        echo json_encode($result);
      }

        public function getall_transactions(Request $request)
        {

            $result['search_date']=!empty($request['search_date']) ? $request['search_date'] : "";
            
            $real_transactions=Payment_history_model::from('payment_history as pt')->select('pt.*','pt.to_user as user_id',
                                                             DB::raw('CONCAT(u1.first_name," ", u1.last_name) AS debited'),
                                                             DB::raw('CONCAT(u2.first_name," ", u2.last_name) AS credited'),'ur1.role_name as from_role_name','ur2.role_name as to_role_name')
                                                ->leftjoin('users as u1', 'pt.from_user', '=', 'u1.user_id') 
                                                ->leftjoin('users as u2', 'pt.to_user', '=', 'u2.user_id') 
                                                ->leftjoin('user_roles as ur1', 'pt.from_role', '=', 'ur1.role_id') 
                                                ->leftjoin('user_roles as ur2', 'pt.to_role', '=', 'ur2.role_id') 
                                                ->where(function ($query) use ($request) {                                            
                                                    if (!empty($request['search_date'])) $query->whereDate('pt.created_at',$request['search_date']);     // user (child) history                                              
                                                })                                            
                                               ->orderBy('pt.created_at', 'DESC')->get()->toArray();

            $virtual_transactions=Wallet_transaction_model::from('wallet_transaction as wt')
                                                ->select('wt.*','wt.credit as amount',
                                                 DB::raw('CONCAT(u1.first_name," ", u1.last_name) AS debited'),
                                                  DB::raw('CONCAT(u2.first_name," ", u2.last_name) AS credited'),'ur1.role_name as from_role_name','ur2.role_name as to_role_name')
                                                ->leftjoin('users as u1', 'wt.from_user', '=', 'u1.user_id') 
                                                ->leftjoin('users as u2', 'wt.user_id', '=', 'u2.user_id') 
                                                ->leftjoin('user_roles as ur1', 'wt.from_role', '=', 'ur1.role_id') 
                                                ->leftjoin('user_roles as ur2', 'wt.to_role', '=', 'ur2.role_id') 
                                                ->where(function ($query) use ($request) {                                            
                                                    if (!empty($request['search_date'])) $query->whereDate('wt.created_at',$request['search_date']);     // user (child) history                                              
                                                })                                            
                                               ->orderBy('wt.created_at', 'DESC')->get()->toArray();


                                               
                                               $transactions=array_merge($real_transactions,$virtual_transactions);
                                            

                             usort($transactions, function($a, $b) {
                                                return strtotime($b['created_at'])-strtotime($a['created_at']);
                                            });

                                            // echo "<pre>";
                                            // print_r($transactions);
                                            // exit;

                                            $result['transactions']=$transactions;


              return view('admin/transactions',$result);                                          

        }
                                           
    }
