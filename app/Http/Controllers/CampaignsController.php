<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Category;
use App\Country;
use App\Payment;
use App\Reward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('app.start_a_campaign');
        $categories = Category::all();
        $countries = Country::all();

        return view('admin.start_campaign', compact('title', 'categories', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = [
            'category'      => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'goal'          => 'required',
            'end_method'    => 'required',
            'country_id'    => 'required',
        ];

        $this->validate($request, $rules);

        $user_id = Auth::user()->id;

        $slug = unique_slug($request->title);

        //feature image has been moved to update
        $data = [
            'user_id'           => $user_id,
            'category_id'       => $request->category,
            'title'             => $request->title,
            'slug'              => $slug,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'campaign_owner_commission'              => get_option('campaign_owner_commission'),
            'goal'              => $request->goal,
            'min_amount'        => $request->min_amount,
            'max_amount'        => $request->max_amount,
            'recommended_amount' => $request->recommended_amount,
            'amount_prefilled'  => $request->amount_prefilled,
            'end_method'        => $request->end_method,
            'video'             => $request->video,
            'feature_image'     => '',
            'status'            => 0,
            'country_id'        => $request->country_id,
            'address'           => $request->address,
            'is_funded'         => 0,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
        ];

        $create = Campaign::create($data);

        if ($create){
            return redirect(route('edit_campaign', $create->id))->with('success', trans('app.campaign_created'));
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }


    public function myCampaigns(){
        $title = trans('app.my_campaigns');
        $user = request()->user();
        //$my_campaigns = $user->my_campaigns;
        $my_campaigns = Campaign::whereUserId($user->id)->orderBy('id', 'desc')->get();

        return view('admin.my_campaigns', compact('title', 'my_campaigns'));
    }

    public function myPendingCampaigns(){
        $title = trans('app.pending_campaigns');
        $user = request()->user();
        $my_campaigns = Campaign::pending()->whereUserId($user->id)->orderBy('id', 'desc')->get();

        return view('admin.my_campaigns', compact('title', 'my_campaigns'));
    }


    
    public function allCampaigns(){
        $title = trans('app.all_campaigns');
        $campaigns = Campaign::active()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }

    public function staffPicksCampaigns(){
        $title = trans('app.staff_picks');
        $campaigns = Campaign::staff_picks()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }
    public function fundedCampaigns(){
        $title = trans('app.funded');
        $campaigns = Campaign::funded()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }


    public function blockedCampaigns(){
        $title = trans('app.blocked_campaigns');
        $campaigns = Campaign::blocked()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }
    public function pendingCampaigns(){
        $title = trans('app.pending_campaigns');
        $campaigns = Campaign::pending()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }

    public function expiredCampaigns(){
        $title = trans('app.expired_campaigns');
        $campaigns = Campaign::active()->expired()->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }


    public function searchAdminCampaigns(Request $request){
        $title = trans('app.campaigns_search_results');
        $campaigns = Campaign::where('title', 'like', "%{$request->q}%")->orderBy('id', 'desc')->paginate(20);
        return view('admin.all_campaigns', compact('title', 'campaigns'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $slug = null)
    {
        $campaign = Campaign::find($id);
        $title = $campaign->title;

        $enable_discuss = get_option('enable_disqus_comment_in_blog');
        return view('campaign_single', compact('campaign', 'title', 'enable_discuss'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = request()->user()->id;
        $campaign = Campaign::find($id);
        //todo: checked if admin then he can access...
        if ($campaign->user_id != $user_id){
            exit('Unauthorized access');
        }

        $title = trans('app.edit_campaign');
        $categories = Category::all();
        $countries = Country::all();

        return view('admin.edit_campaign', compact('title', 'categories', 'countries', 'campaign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        $rules = [
            'category'      => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'goal'          => 'required',
            'country_id'    => 'required',
        ];

        $this->validate($request, $rules);

        $campaign = Campaign::find($id);

        $image_name = $campaign->feature_image;
        if ($request->hasFile('feature_image')){
            $upload_dir = public_path('uploads/images/');
            if ( ! file_exists($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }
            $thumb_dir = public_path('uploads/images/thumb/');
            if ( ! file_exists($thumb_dir)){
                mkdir($thumb_dir, 0777, true);
            }

            //Delete old image
            if ($image_name){
                if (file_exists($upload_dir.$image_name)){
                    unlink($upload_dir.$image_name);
                }
                if (file_exists($thumb_dir.$image_name)){
                    unlink($thumb_dir.$image_name);
                }
            }

            $image = $request->file('feature_image');
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $full_image = Image::make($image)->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resized = Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $thumbFileName = $thumb_dir.$image_name;
            $imageFileName = $upload_dir.$image_name;

            try{
                //Uploading original image
                $full_image->save($imageFileName);
                //Uploading thumb
                $resized->save($thumbFileName);
            } catch (\Exception $e){
                return $e->getMessage();
            }
        }

        $data = [
            'category_id'       => $request->category,
            'title'             => $request->title,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'goal'              => $request->goal,
            'min_amount'        => $request->min_amount,
            'max_amount'        => $request->max_amount,
            'recommended_amount' => $request->recommended_amount,
            'amount_prefilled'  => $request->amount_prefilled,
            'end_method'        => $request->end_method,
            'video'             => $request->video,
            'feature_image'     => $image_name,
            'country_id'        => $request->country_id,
            'address'           => $request->address,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
        ];

        $update = Campaign::whereId($id)->update($data);

        if ($update){
            return redirect(route('edit_campaign', $id))->with('success', trans('app.campaign_created'));
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showBackers($id){
        $campaign = Campaign::find($id);
        $title = trans('app.backers').' | '.$campaign->title;
        return view('campaign_backers', compact('campaign', 'title'));

    }

    public function showUpdates($id){
        $campaign = Campaign::find($id);
        $title = $campaign->title;
        return view('campaign_update', compact('campaign', 'title'));
    }

    public function showFaqs($id){
        $campaign = Campaign::find($id);
        $title = $campaign->title;
        return view('campaign_faqs', compact('campaign', 'title'));
    }

    /**
     * @param $id
     * @return mixed
     * 
     * todo: need to be moved it to reward controller
     */
    public function rewardsInCampaignEdit($id){
        $title = trans('app.campaign_rewards');
        $campaign = Campaign::find($id);
        $rewards = Reward::whereCampaignId($campaign->id)->get();
        return view('admin.campaign_rewards', compact('title', 'campaign', 'rewards'));
    }

    /**
     * @param Request $request
     * @param int $reward_id
     * @return mixed
     */
    public function addToCart(Request $request, $reward_id = 0){
        if ($reward_id){
            //If checkout request come from reward
            session( ['cart' =>  ['cart_type' => 'reward', 'reward_id' => $reward_id] ] );

            $reward = Reward::find($reward_id);
            if($reward->campaign->is_ended()){
                $request->session()->forget('cart');
                return redirect()->back()->with('error', trans('app.invalid_request'));
            }
        }else{
            //Or if comes from donate button
            session( ['cart' =>  ['cart_type' => 'donation', 'campaign_id' => $request->campaign_id, 'amount' => $request->amount ] ] );
        }


        return redirect(route('checkout'));
    }

    public function statusChange($id, $status = null){

        $campaign = Campaign::find($id);
        if ($campaign && $status){

            if ($status == 'approve'){
                $campaign->status = 1;
                $campaign->save();

            }elseif($status == 'block'){
                $campaign->status = 2;
                $campaign->save();
            }elseif($status == 'funded'){
                $campaign->is_funded = 1;
                $campaign->save();
            }elseif ($status == 'add_staff_picks'){
                $campaign->is_staff_picks = 1;
                $campaign->save();

            }elseif($status == 'remove_staff_picks'){
                $campaign->is_staff_picks = 0;
                $campaign->save();
            }

        }
        return back()->with('success', trans('app.status_updated'));
    }

    /**
     * @return mixed
     *
     * Checkout page
     */
    public function checkout(){
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('checkout_empty', compact('title'));
        }

        $reward = null;
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
        }
        if (session('cart')){
            return view('checkout', compact('title', 'campaign', 'reward'));
        }
        return view('checkout_empty', compact('title'));
    }

    public function checkoutPost(Request $request){
        $title = trans('app.checkout');

        if ( ! session('cart')){
            return view('checkout_empty', compact('title'));
        }

        $cart = session('cart');
        $input = array_except($request->input(), '_token');
        session(['cart' => array_merge($cart, $input)]);

        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
        }
        
        //dd(session('cart'));
        return view('payment', compact('title', 'campaign'));
    }

    /**
     * @param Request $request
     * @return mixed
     *
     * Payment gateway PayPal
     */
    public function paypalRedirect(Request $request){
        if ( ! session('cart')){
            return view('checkout_empty', compact('title'));
        }
        //Find the campaign
        $cart = session('cart');

        $amount = 0;
        if(session('cart.cart_type') == 'reward'){
            $reward = Reward::find(session('cart.reward_id'));
            $amount = $reward->amount;
            $campaign = Campaign::find($reward->campaign_id);
        }elseif (session('cart.cart_type') == 'donation'){
            $campaign = Campaign::find(session('cart.campaign_id'));
            $amount = $cart['amount'];
        }
        $currency = get_option('currency_sign');
        $user_id = null;
        if (Auth::check()){
            $user_id = Auth::user()->id;
        }
        //Create payment in database


        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $payments_data = [
            'name' => session('cart.full_name'),
            'email' => session('cart.email'),

            'user_id'               => $user_id,
            'campaign_id'           => $campaign->id,
            'reward_id'             => session('cart.reward_id'),

            'amount'                => $amount,
            'payment_method'        => 'paypal',
            'status'                => 'initial',
            'currency'              => $currency,
            'local_transaction_id'  => $transaction_id,

            'contributor_name_display'  => session('cart.contributor_name_display'),
        ];
        //Create payment and clear it from session
        $created_payment = Payment::create($payments_data);
        $request->session()->forget('cart');

        // PayPal settings
        $paypal_action_url = "https://www.paypal.com/cgi-bin/webscr";
        if (get_option('enable_paypal_sandbox') == 1)
            $paypal_action_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

        $paypal_email = get_option('paypal_receiver_email');
        $return_url = route('payment_success',$transaction_id);
        $cancel_url = route('checkout');
        $notify_url = route('paypal_notify', $transaction_id);

        $item_name = $campaign->title." [Contributing]";

        // Check if paypal request or response
        $querystring = '';

        // Firstly Append paypal account to querystring
        $querystring .= "?business=".urlencode($paypal_email)."&";

        // Append amount& currency (£) to quersytring so it cannot be edited in html
        //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
        $querystring .= "item_name=".urlencode($item_name)."&";
        $querystring .= "amount=".urlencode($amount)."&";
        $querystring .= "currency_code=".urlencode($currency)."&";

        $querystring .= "first_name=".urlencode(session('cart.full_name'))."&";
        //$querystring .= "last_name=".urlencode($ad->user->last_name)."&";
        $querystring .= "payer_email=".urlencode(session('cart.email') )."&";
        $querystring .= "item_number=".urlencode($created_payment->local_transaction_id)."&";

        //loop for posted values and append to querystring
        foreach(array_except($request->input(), '_token') as $key => $value){
            $value = urlencode(stripslashes($value));
            $querystring .= "$key=$value&";
        }

        // Append paypal return addresses
        $querystring .= "return=".urlencode(stripslashes($return_url))."&";
        $querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
        $querystring .= "notify_url=".urlencode($notify_url);

        // Append querystring with custom field
        //$querystring .= "&custom=".USERID;

        // Redirect to paypal IPN
        header('location:'.$paypal_action_url.$querystring);
        exit();
    }

    /**
     * @param Request $request
     * @param $transaction_id
     *
     * Check paypal notify
     */
    public function paypalNotify(Request $request, $transaction_id){
        //todo: need to  be check
        $payment = Payment::whereLocalTransactionId($transaction_id)->where('status','!=','success')->first();

        $verified = paypal_ipn_verify();
        if ($verified){
            //Payment success, we are ready approve your payment
            $payment->status = 'success';
            $payment->charge_id_or_token = $request->txn_id;
            $payment->description = $request->item_name;
            $payment->payer_email = $request->payer_email;
            $payment->payment_created = strtotime($request->payment_date);
            $payment->save();
        }else{
            $payment->status = 'declined';
            $payment->description = trans('app.payment_declined_msg');
            $payment->save();
        }
        // Reply with an empty 200 response to indicate to paypal the IPN was received correctly
        header("HTTP/1.1 200 OK");
    }


    /**
     * @return array
     * 
     * receive card payment from stripe
     */
    public function paymentStripeReceive(Request $request){

        $user_id = null;
        if (Auth::check()){
            $user_id = Auth::user()->id;
        }

        $stripeToken = $request->stripeToken;
        \Stripe\Stripe::setApiKey(get_stripe_key('secret'));
        // Create the charge on Stripe's servers - this will charge the user's card
        try {
            $cart = session('cart');

            //Find the campaign
            $amount = 0;
            if(session('cart.cart_type') == 'reward'){
                $reward = Reward::find(session('cart.reward_id'));
                $amount = $reward->amount;
                $campaign = Campaign::find($reward->campaign_id);
            }elseif (session('cart.cart_type') == 'donation'){
                $campaign = Campaign::find(session('cart.campaign_id'));
                $amount = $cart['amount'];
            }
            $currency = get_option('currency_sign');

            //Charge from card
            $charge = \Stripe\Charge::create(array(
                "amount"        => ($amount * 100), // amount in cents, again
                "currency"      => $currency,
                "source"        => $stripeToken,
                "description"   => $campaign->title." [Contributing]"
            ));

            if ($charge->status == 'succeeded'){
                //Save payment into database
                $data = [
                    'name' => session('cart.full_name'),
                    'email' => session('cart.email'),
                    'amount' => ($charge->amount / 100 ),

                    'user_id'               => $user_id,
                    'campaign_id'           => $campaign->id,
                    'reward_id'             => session('cart.reward_id'),
                    'payment_method'        => 'stripe',
                    'currency'              => $currency,
                    'charge_id_or_token'    => $charge->id,
                    'description'           => $charge->description,
                    'payment_created'       => $charge->created,

                    //Card Info
                    'card_last4'        => $charge->source->last4,
                    'card_id'           => $charge->source->id,
                    'card_brand'        => $charge->source->brand,
                    'card_country'      => $charge->source->US,
                    'card_exp_month'    => $charge->source->exp_month,
                    'card_exp_year'     => $charge->source->exp_year,

                    'contributor_name_display'  => session('cart.contributor_name_display'),
                    'status'                    => 'success',
                ];

                Payment::create($data);

                $request->session()->forget('cart');
                return ['success'=>1, 'msg'=> trans('app.payment_received_msg'), 'response' => $this->payment_success_html()];
            }
        } catch(\Stripe\Error\Card $e) {
            // The card has been declined
            return ['success'=>0, 'msg'=> trans('app.payment_declined_msg'), 'response' => $e];
        }
    }
    
    public function payment_success_html(){
        $html = ' <div class="payment-received">
                            <h1> <i class="fa fa-check-circle-o"></i> '.trans('app.payment_thank_you').'</h1>
                            <p>'.trans('app.payment_receive_successfully').'</p>
                            <a href="'.route('home').'" class="btn btn-filled">'.trans('app.home').'</a>
                        </div>';
        return $html;
    }
    
    public function paymentSuccess(Request $request, $transaction_id = null){
        if ($transaction_id){
            $payment = Payment::whereLocalTransactionId($transaction_id)->whereStatus('initial')->first();
            if ($payment){
                $payment->status = 'pending';
                $payment->save();
            }
        }

        $title = trans('app.payment_success');
        return view('payment_success', compact('title'));
    }


}
