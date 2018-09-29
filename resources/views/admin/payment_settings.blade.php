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

                    {{ Form::open(['route'=>'save_settings','class' => 'form-horizontal', 'files' => true]) }}

                    <div class="form-group {{ $errors->has('enable_paypal')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                        <div class="col-md-8">
                            <label for="enable_paypal" class="checkbox-inline">
                                <input type="checkbox" value="1" id="enable_paypal" name="enable_paypal" {{ get_option('enable_paypal') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.enable_paypal')
                            </label>

                            {!! $errors->has('type')? '<p class="help-block">'.$errors->first('type').'</p>':'' !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('enable_stripe')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                        <div class="col-md-8">
                            <label for="enable_stripe" class="checkbox-inline">
                                <input type="checkbox" value="1" id="enable_stripe" name="enable_stripe" {{ get_option('enable_stripe') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.enable_stripe')
                            </label>

                            {!! $errors->has('type')? '<p class="help-block">'.$errors->first('type').'</p>':'' !!}
                        </div>
                    </div>

                    <div id="paypal_settings_wrap" style="display: {{ get_option('enable_paypal') == 1 ? 'block' : 'none' }}">
                        <hr />

                        <legend>@lang('app.paypal_settings')</legend>
                        <div class="form-group {{ $errors->has('enable_paypal_sandbox')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_paypal_sandbox') </label>
                            <div class="col-md-8">
                                <label for="enable_paypal_sandbox" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_paypal_sandbox" name="enable_paypal_sandbox" {{ get_option('enable_paypal_sandbox') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_paypal_sandbox')
                                </label>

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('paypal_receiver_email')? 'has-error':'' }}">
                            <label for="paypal_receiver_email" class="col-sm-4 control-label">@lang('app.paypal_receiver_email')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="paypal_receiver_email" value="{{ old('paypal_receiver_email')? old('paypal_receiver_email') : get_option('paypal_receiver_email') }}" name="paypal_receiver_email" placeholder="@lang('app.test_secret_key')">
                                {!! $errors->has('paypal_receiver_email')? '<p class="help-block">'.$errors->first('paypal_receiver_email').'</p>':'' !!}
                                <p class="text-info">@lang('app.paypal_receiver_email_help_text')</p>
                            </div>
                        </div>

                    </div>

                    <div id="stripe_settings_wrap" style="display: {{ get_option('enable_stripe') == 1 ? 'block' : 'none' }}">
                        <hr />

                        <legend>@lang('app.stripe_settings')</legend>
                        <div class="form-group {{ $errors->has('stripe_test_mode')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.test_mode') </label>
                            <div class="col-md-8">
                                <label for="stripe_test_mode" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="stripe_test_mode" name="stripe_test_mode" {{ get_option('stripe_test_mode') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_test_mode')
                                </label>

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('stripe_test_secret_key')? 'has-error':'' }}">
                            <label for="stripe_test_secret_key" class="col-sm-4 control-label">@lang('app.test_secret_key')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="stripe_test_secret_key" value="{{ old('stripe_test_secret_key')? old('stripe_test_secret_key') : get_option('stripe_test_secret_key') }}" name="stripe_test_secret_key" placeholder="@lang('app.test_secret_key')">
                                {!! $errors->has('stripe_test_secret_key')? '<p class="help-block">'.$errors->first('stripe_test_secret_key').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('stripe_test_publishable_key')? 'has-error':'' }}">
                            <label for="stripe_test_publishable_key" class="col-sm-4 control-label">@lang('app.test_publishable_key')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="stripe_test_publishable_key" value="{{ old('stripe_test_publishable_key')? old('stripe_test_publishable_key') : get_option('stripe_test_publishable_key') }}" name="stripe_test_publishable_key" placeholder="@lang('app.test_publishable_key')">
                                {!! $errors->has('stripe_test_publishable_key')? '<p class="help-block">'.$errors->first('stripe_test_publishable_key').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('stripe_live_secret_key')? 'has-error':'' }}">
                            <label for="stripe_live_secret_key" class="col-sm-4 control-label">@lang('app.live_secret_key')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="stripe_live_secret_key" value="{{ old('stripe_live_secret_key')? old('stripe_live_secret_key') : get_option('stripe_live_secret_key') }}" name="stripe_live_secret_key" placeholder="@lang('app.live_secret_key')">
                                {!! $errors->has('stripe_live_secret_key')? '<p class="help-block">'.$errors->first('stripe_live_secret_key').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('stripe_live_publishable_key')? 'has-error':'' }}">
                            <label for="stripe_live_publishable_key" class="col-sm-4 control-label">@lang('app.live_publishable_key')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="stripe_live_publishable_key" value="{{ old('stripe_live_publishable_key')? old('stripe_live_publishable_key') : get_option('stripe_live_publishable_key') }}" name="stripe_live_publishable_key" placeholder="@lang('app.live_publishable_key')">
                                {!! $errors->has('stripe_live_publishable_key')? '<p class="help-block">'.$errors->first('stripe_live_publishable_key').'</p>':'' !!}
                            </div>
                        </div>


                    </div>


                        <legend>@lang('app.commission_settings')</legend>

                        <div class="form-group {{ $errors->has('campaign_owner_commission')? 'has-error':'' }}">
                            <label for="campaign_owner_commission" class="col-sm-4 control-label">@lang('app.campaign_owner_commission') %</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="campaign_owner_commission" value="{{ old('campaign_owner_commission')? old('campaign_owner_commission') : get_option('campaign_owner_commission') }}" max="100" name="campaign_owner_commission" placeholder="@lang('app.commission_percent')">
                                {!! $errors->has('campaign_owner_commission')? '<p class="help-block">'.$errors->first('campaign_owner_commission').'</p>':'' !!}
                            </div>
                        </div>




                    <hr />

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" id="settings_save_btn" class="btn btn-primary">@lang('app.save_settings')</button>
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>   <!-- /#page-wrapper -->

            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->
    </div>
@endsection


@section('page-js')
    <script>
        $(document).ready(function(){
            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' },
                });
            });

            /**
             * show or hide stripe and paypal settings wrap
             */
            $('#enable_paypal').click(function(){
                if ($(this).prop('checked')){
                    $('#paypal_settings_wrap').slideDown();
                }else{
                    $('#paypal_settings_wrap').slideUp();
                }
            });
            $('#enable_stripe').click(function(){
                if ($(this).prop('checked')){
                    $('#stripe_settings_wrap').slideDown();
                }else{
                    $('#stripe_settings_wrap').slideUp();
                }
            });

            /**
             * Send settings option value to server
             */
            $('#settings_save_btn').click(function(e){
                e.preventDefault();

                var this_btn = $(this);
                this_btn.attr('disabled', 'disabled');

                var form_data = this_btn.closest('form').serialize();
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: form_data,
                    success : function (data) {
                        if (data.success == 1){
                            this_btn.removeAttr('disabled');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });
    </script>
@endsection