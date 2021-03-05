<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $razorpayId = "rzp_test_Dh2YAfEmfOjPDJ";
    private $razorpayKey = "kRU8BwFjeUwdQBaRIfoBAf5N";

    public function index()
    {
        return view('payment.payment-initiate');
    }

    public function initiate(Request $request)
    {
        $receiptId = Str::random(20);
        $api = new Api($this->razorpayId, $this->razorpayKey);

        $order = $api->order->create(
            array(
                'receipt' => $receiptId,
                'amount' => $request->all()['amount'] * 100,
                'currency' => 'INR'
            )
        );

        $response = [
            'orderId' => $order['id'],
            'razorpayId' => $this->razorpayId,
            'amount' => $request->all()['amount'] * 100,
            'name' => $request->all()['name'],
            'currency' => 'INR',
            'email' => $request->all()['email'],
            'contactNumber' => $request->all()['contactNumber'],
            'address' => $request->all()['address'],
            'description' => 'Testing description',
        ];

        $res=new Payment();
            $res->name = request('name');
            $res->amount = request('amount');
            // $res->payment_id = $receiptId;
            $res->razorpay_id = $order['id'];
            // dd($res);
            $res->save();

            $data = array(
                'order_id' => $order['id'],
                
            );

        // Let's checkout payment page is it working
        return view('payment.payment-page', compact('response'));
    }

    public function complete(Request $request)
    {
        
        // print_r($request->all());
        // exit();
        // Now verify the signature is correct . We create the private function for verify the signature
        $signatureStatus = $this->SignatureVerify(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );

        // If Signature status is true We will save the payment response in our database
        // In this tutorial we send the response to Success page if payment successfully made
        if ($signatureStatus == true) {
            return view('payment.payment-success-page');
        } else {
            // You can create this page
            return view('payment.payment-failed-page');
        }
    }
    private function SignatureVerify($_signature, $_paymentId, $_orderId )
    {
        
        try {
            // Create an object of razorpay class
            $api = new Api($this->razorpayId, $this->razorpayKey);
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            // $res->payment_id = $_paymentId;
            // $data = $request->all();
            // $user = Payment::where('razorpay_order_id','razorpay_payment_id')->first();
            // $user->payment_done = true;
            // $user->payment_id = $_paymentId;
            // $user->save();
            // dd($_paymentId);
            return true;
        } catch (\Exception $e) {
            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }
}
