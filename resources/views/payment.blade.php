@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <section class="campaign-details-wrap">
        @include('single_campaign_header')
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="checkout-wrap">

                        <div class="contributing-to">
                            <p class="contributing-to-name"><strong> @lang('app.you_are_contributing_to') {{$campaign->user->name}}</strong></p>
                            <h3>{{$campaign->title}}</h3>
                        </div>

                        <hr />

                        <div class="row">
                            @if(get_option('enable_stripe') == 1)
                                <div class="col-md-6">
                                    <div class="stripe-button-container">
                                        <script
                                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="{{ get_stripe_key() }}"
                                                data-amount="{{ session('cart.amount') * 100 }}"
                                                data-email="{{session('cart.email')}}"
                                                data-name="{{ get_option('site_name') }}"
                                                data-description="{{ $campaign->title." Contributing" }}"
                                                data-currency="USD"
                                                data-image="{{ asset('assets/images/stripe_logo.jpg') }}"
                                                data-locale="auto">
                                        </script>
                                    </div>
                                </div>
                            @endif

                            @if(get_option('enable_paypal') == 1)
                                <div class="col-md-6">
                                    {{ Form::open(['route' => 'payment_paypal_receive']) }}
                                    <input type="hidden" name="cmd" value="_xclick" />
                                    <input type="hidden" name="no_note" value="1" />
                                    <input type="hidden" name="lc" value="UK" />
                                    <input type="hidden" name="currency_code" value="{{get_option('currency_sign')}}" />
                                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                    <button type="submit" class="btn btn-info"> <i class="fa fa-paypal"></i> @lang('app.pay_with_paypal')</button>
                                    {{ Form::close() }}
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@section('page-js')

    <script>
        $(function() {
            $('.stripe-button').on('token', function(e, token){
                $('#stripeForm').replaceWith('');

                $.ajax({
                    url : '{{route('payment_stripe_receive')}}',
                    type: "POST",
                    data: { stripeToken : token.id, _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        if (data.success == 1){
                            $('.checkout-wrap').html(data.response);
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });
    </script>

@endsection