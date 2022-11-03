<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Complaints_model;
use App\Models\Payment_history_model;
use App\Models\Wallet_transaction_model;

class Complaints_controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('check_user:admin');
    }

    public function index()
    {         
        $result['user_role']=1;
        $result['complaints']=Complaints_model::select('complaints.*','users.first_name','users.last_name','complaint_reasons.reason_name','user_roles.role_name')
                                                ->leftjoin('users', 'complaints.by_user', '=', 'users.user_id')    
                                                ->leftjoin('user_roles', 'complaints.by_role', '=', 'user_roles.role_id')    
                                                ->leftjoin('complaint_reasons', 'complaints.reason_id', '=', 'complaint_reasons.reason_id')    
                                                ->get()->toArray();
        
        return view('admin/complaints',$result);
    }

    public function admin_remark(Request $request)
    {
        $result=array('status'=>false,'msg'=>'Data not found');

        if($request['complaint_id'])
        {
            $complaint=Complaints_model::where('complaint_id',$request['complaint_id'])->first();

            if(!empty($complaint))
            {
                $complaint->admin_remark=$request['remark'];
                $complaint->is_active=!empty($request['is_invalid']) ? 2 : 1;
                $complaint->save();

                $result=array('status'=>true,'msg'=>'Complaint added');
            }
        }

      echo json_encode($result);

    }

    public function complaint_details(Request $request)
    {
        $result['user_role']=1;
        $complaint=Complaints_model::select('complaints.*','users.first_name','users.last_name','complaint_reasons.reason_name')
                                    ->leftjoin('users', 'complaints.by_user', '=', 'users.user_id')    
                                    ->leftjoin('complaint_reasons', 'complaints.reason_id', '=', 'complaint_reasons.reason_id')    
                                    ->where('complaint_id',base64_decode($request['complaint_id']))
                                    ->first();

        $result['complaint_details']=!empty($complaint) ? $complaint->toArray() : array();
             
        $user_id=$complaint->by_user;

        // $user_id=6;
        // $complaint->by_user=3;
      
            $transaction=array();
       
            $transactions=array();

            $from_year=!empty($request['year']) ? $request['year'] : date('Y') ; //here from year is greater than till year, because we are fetching reverse data.(DESC ORDER latest first)
            $till_year=!empty($request['year']) ? $request['year'] :  2021 ;

            for($from_year; $till_year<=$from_year; $from_year--)
            {           
              for($month=12; $month>=1; $month--)
              {  

            // for($month=1; $month<=12; $month++)
            // {              

                if($complaint->by_role==3)
                {
                        for($i=0; $i<2; $i++) // for two status  bank transfer to user  & user transfer to bank account
                        {

                            $trans=Payment_history_model::from('payment_history as ph')->select('ph.*')                                                
                                                    ->where(function ($query) use ($request,$i,$complaint,$user_id) {                                                    
                                                                                            
                                                        if($i==0) $query->where('ph.from_user', 0);         //bank transfer to user
                                                        if($i==0) $query->where('ph.to_user',$user_id);     //bank transfer to user                                               
                                                        if($i==0) $query->where('ph.to_role',$complaint->by_role);     //for user role 
                                                        
                                                        if($i==1) $query->where('ph.from_user', $user_id);         //user transfer to bank account
                                                        if($i==1) $query->where('ph.from_role',$complaint->by_role);     //for user role 
                                                        if($i==1) $query->where('ph.to_user', 0);     //user transfer to bank account
                                                                                                        
                                                    })
                                                    ->whereYear('ph.created_at',"=",$from_year)
                                                    ->whereMonth('ph.created_at',"=",$month) 
                                                    ->get()->toArray();
                            
            
                                                                
                                        if(!empty($trans))
                                        {
                                                if(!empty($transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))]))
                                                {
                                                    $transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))] = array_merge($transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))], $trans);                
                                                } else {
                                                    $transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))] = $trans ;                
                                                }                                           
                                        }
                                        
                         }
                } else if($complaint->by_role==4) {

                    for($i=0; $i<2; $i++) // for two status  bank transfer to user  & user transfer to bank account
                    { 
                        $trans=Wallet_transaction_model::from('wallet_transaction as wt')
                                                        ->select('wt.*')
                                                        ->where(function ($query) use ($request,$user_id,$i) {                                              
                                                            if ($i==0) $query->where('wt.from_user',$user_id);     // child paid to shop                                             
                                                            if ($i==1) $query->where('wt.user_id',$user_id);     // parent pays to child                                             
                                                        })
                                                        ->whereYear('wt.created_at',"=",$from_year)
                                                        ->whereMonth('wt.created_at',"=",$month)
                                                        ->get()->toArray();

                                                      
                                            if(!empty($trans))
                                            {
                                                    if(!empty($transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))]))
                                                    {
                                                        $transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))] = array_merge($transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))], $trans);                
                                                    } else {
                                                        $transactions[date('F Y', mktime(0,0,0,$month, 1, $from_year))] = $trans ;                
                                                    }                                                          
                                            }
                    }

                }
                  
              }
            }

                          
              $all_transactions=array();
              foreach($transactions as $key=>$val)
              {
                usort($val, function($a, $b) {
                    return strtotime($a['created_at']) - strtotime($b['created_at']);
                });

                $all_transactions[$key]=$val;                                
              } 

             if($complaint->by_role==3) { $transaction['parent'] = $all_transactions ; } 
             if($complaint->by_role==4) { $transaction['child'] = $all_transactions ; } 
        

            $result['transaction']=$transaction;  
                                        
            return view('admin/complaint-details',$result);
    }
}
