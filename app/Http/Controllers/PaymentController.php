<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    
    public function index(Request $request){
        $user = Auth::user();
        $title = trans('app.payments');

        if ($user->is_admin()){
            if ($request->q){
                $payments = Payment::success()->where('email', 'like', "%{$request->q}%")->orderBy('id', 'desc')->paginate(20);
            }else{
                $payments = Payment::success()->orderBy('id', 'desc')->paginate(20);
            }
        }else{
            $campaign_ids = $user->my_campaigns()->pluck('id')->toArray();

            if ($request->q){
                $payments = Payment::success()->whereIn('campaign_id', $campaign_ids)->where('email', 'like', "%{$request->q}%")->orderBy('id', 'desc')->paginate(20);
            }else{
                $payments = Payment::success()->whereIn('campaign_id', $campaign_ids)->orderBy('id', 'desc')->paginate(20);
            }

        }




        return view('admin.payments', compact('title', 'payments'));
    }
    
    public function view($id){
        $title = trans('app.payment_details');
        $payment = Payment::find($id);
        return view('admin.payment_view', compact('title', 'payment'));
    }

    public function withdraw(){
        $user = Auth::user();
        $title = trans('app.withdraw');
        $campaigns = $user->my_campaigns;
        
        return view('admin.withdraw', compact('title', 'campaigns'));
    }


}
