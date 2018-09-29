<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(){
        
        $user = Auth::user();
        
        $title = trans('app.dashboard');
        $user_count = User::all()->count();
        $pending_campaign_count = Campaign::pending()->count();
        $blocked_campaign_count = Campaign::blocked()->count();
        $active_campaign_count = Campaign::active()->count();
        
        $payment_created = Payment::success()->count();
        $payment_amount = Payment::success()->sum('amount');


        if ($user->is_admin()){
            $pending_campaigns = Campaign::pending()->orderBy('id', 'desc')->take(10)->get();
            $last_payments = Payment::success()->orderBy('id', 'desc')->take(10)->get();

        }else{
            $campaign_ids = $user->my_campaigns()->pluck('id')->toArray();

            $pending_campaigns = Campaign::pending()->whereUserId($user->id)->orderBy('id', 'desc')->take(10)->get();

            $last_payments = Payment::success()->whereIn('campaign_id', $campaign_ids)->orderBy('id', 'desc')->take(10)->get();

        }


        return view('admin.dashboard', compact('title','user_count', 'active_campaign_count', 'pending_campaign_count', 'blocked_campaign_count', 'payment_created', 'payment_amount', 'pending_campaigns', 'last_payments'));
    }
}
