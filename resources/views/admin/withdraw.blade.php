@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('content')


    <div class="dashboard-wrap">
        <div class="container">
            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">

                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }}  </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')


                       <table class="table table-bordered table-striped">
                           <tr>
                               <th>@lang('app.campaign_title')</th>
                               <th>@lang('app.raised')</th>
                               <th>@lang('app.your_commission')</th>
                           </tr>



                           @foreach($campaigns as $campaign)

                               <tr>

                                   <td>{{$campaign->title}}</td>
                                   <td>{{get_amount($campaign->amount_raised()->amount_raised)}}</td>
                                   <td>{{get_amount($campaign->amount_raised()->campaign_owner_commission)}} ({{$campaign->campaign_owner_commission}}%)</td>

                               </tr>


                           @endforeach

                       </table>



                </div>

            </div>
        </div>
    </div>


@endsection