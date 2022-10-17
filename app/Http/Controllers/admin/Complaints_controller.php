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
        $result['complaints']=Complaints_model::select('complaints.*','users.first_name','users.last_name','complaint_reasons.reason_name')
                                                ->leftjoin('users', 'complaints.by_user', '=', 'users.user_id')    
                                                ->leftjoin('complaint_reasons', 'complaints.reason_id', '=', 'complaint_reasons.reason_id')    
                                                ->get()->toArray();
        
        return view('admin/complaints',$result);
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
            for($month=1; $month<=12; $month++)
            {              

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
                                                    ->whereMonth('ph.created_at',"=",$month) 
                                                    ->get()->toArray();
                            
            
                                                                
                                        if(!empty($trans))
                                        {
                                                if(!empty($transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))]))
                                                {
                                                    $transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))] = array_merge($transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))], $trans);                
                                                } else {
                                                    $transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))] = $trans ;                
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
                                                        ->whereMonth('wt.created_at',"=",$month) 
                                                        ->get()->toArray();

                                                      
                                            if(!empty($trans))
                                            {
                                                    if(!empty($transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))]))
                                                    {
                                                        $transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))] = array_merge($transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))], $trans);                
                                                    } else {
                                                        $transactions[date('F Y', mktime(0,0,0,$month, 1, date('Y')))] = $trans ;                
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
