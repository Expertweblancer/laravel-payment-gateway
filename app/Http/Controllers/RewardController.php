<?php

namespace App\Http\Controllers;

use App\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $rules = [
            'amount'                => 'required',
            'quantity'              => 'required',
            'estimated_delivery'    => 'required',
        ];
        $this->validate($request, $rules);

        $user_id = request()->user()->id;

        $data = array_merge(array_except($request->input(), '_token'), [
            'user_id'       => $user_id,
            'campaign_id'   => $id,
        ]);

        $create = Reward::create($data );

        if ($create){
            return redirect(route('edit_campaign_rewards', $id))->with('success', trans('app.reward_created'));
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function show(Reward $reward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function edit(Reward $reward, $campaign_id, $reward_id)
    {
        $user_id = request()->user()->id;
        $title = trans('app.edit_reward');
        $reward = Reward::find($reward_id);
        if ($campaign_id != $reward->campaign_id || $user_id != $reward->user_id){
            die(trans('app.unauthorised_access'));
        }
        return view('admin.campaign_rewards_edit', compact('title', 'campaign', 'reward'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reward $reward, $campaign_id, $reward_id)
    {
        $rules = [
            'amount'                => 'required',
            'quantity'              => 'required',
            'estimated_delivery'    => 'required',
        ];
        $this->validate($request, $rules);

        $data = array_merge(array_except($request->input(), '_token'));

        $update = Reward::whereId($reward_id)->update($data );

        if ($update){
            return redirect(route('edit_campaign_rewards', $campaign_id))->with('success', trans('app.reward_created'));
        }
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reward  $reward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reward $reward, Request $request)
    {
        $user_id = request()->user()->id;
        $data_id = $request->data_id;
        $r = $reward::find($data_id);
        if ($r->user_id != $user_id){
            die(trans('app.unauthorised_access'));
        }
        $r->delete();
        return ['success' => 1];
    }
}
