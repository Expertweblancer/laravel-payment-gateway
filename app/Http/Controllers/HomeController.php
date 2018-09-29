<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $title = trans('app.banner_main_header');
        
        $categories = Category::orderBy('category_name', 'asc')->take(8)->get();
        $staff_picks = Campaign::active()->staff_picks()->orderBy('id', 'desc')->take(8)->get();
        $new_campaigns = Campaign::active()->orderBy('id', 'desc')->paginate(20);
        $funded_campaigns = Campaign::active()->funded()->orderBy('id', 'desc')->take(8)->get();
        
        $new_campaigns->withPath('ajax/new-campaigns');

        return view('home', compact('title','categories', 'staff_picks', 'new_campaigns', 'funded_campaigns'));
    }

    /**
     * @return mixed
     */
    public function newCampaignsAjax(){
        $new_campaigns = Campaign::whereStatus(1)->orderBy('id', 'desc')->paginate(20);
        $new_campaigns->withPath('ajax/new-campaigns');

        if ($new_campaigns->count()){
            return view('new_campaigns_ajax', compact('new_campaigns'));
        }
        return ['success' => false];
    }
    
}
