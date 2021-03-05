<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use Razorpay\Api\Api;
use Session;

class MainCotroller extends Controller
{
    public function index()
    {
        return view('payment.index');
    }
    public function success()
    {
        return view('payment.success');
    }
    // rzp_test_OIMPvGtjyjIMiz
    // jLOK8F9ykYP4OAc07yN89ag2
    public function payment(Request $request)
    {
        $name = $request->input('name');
        $amount = $request->input('amount');


        $api =new Api('rzp_test_OIMPvGtjyjIMiz','jLOK8F9ykYP4OAc07yN89ag');
        $order  = $api->order->create(array('receipt' => '123', 'amount' => $amount * 100 , 'currency' => 'INR')); // Creates order
        $orderId = $order['id']; 

        $user_pay = new Payment();
        $user_pay->name =$name;
        $user_pay->amount =$amount;
        $user_pay->payment_id =$orderId;
        $user_pay->save();


        Session::put('order_id',$orderId);
        Session::put('amount',$amount);

        return redirect('/');
    }
}
