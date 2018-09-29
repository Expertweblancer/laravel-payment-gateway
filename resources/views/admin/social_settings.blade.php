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


                    <div id="social_login_settings_wrap">

                        <legend>@lang('app.social_login')</legend>
                        <div class="form-group {{ $errors->has('enable_social_login')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-8">
                                <label for="enable_social_login" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_social_login" name="enable_social_login" {{ get_option('enable_social_login') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_social_login')
                                </label>

                                <p class="text-info">@lang('app.enable_social_login_help_text')</p>
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('enable_facebook_login')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-8">
                                <label for="enable_facebook_login" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_facebook_login" name="enable_facebook_login" {{ get_option('enable_facebook_login') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_facebook_login')
                                </label>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('enable_google_login')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-8">
                                <label for="enable_google_login" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_google_login" name="enable_google_login" {{ get_option('enable_google_login') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_google_login')
                                </label>
                            </div>
                        </div>




                        <div id="facebook_login_api_wrap" style="display: {{ get_option('enable_facebook_login') == 1 ? 'block' : 'none' }};">
                            <hr />
                            <div class="form-group">
                                <label for="fb_app_id" class="col-sm-4 control-label">@lang('app.facebook') @lang('app.app_id')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fb_app_id" value="{{ get_option('fb_app_id') }}" name="fb_app_id" placeholder="@lang('app.fb_app_id')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fb_app_secret" class="col-sm-4 control-label">@lang('app.facebook') @lang('app.app_secret')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fb_app_secret" value="{{ get_option('fb_app_secret') }}" name="fb_app_secret" placeholder="@lang('app.app_secret')">
                                </div>
                            </div>

                        </div>


                        <div id="google_login_api_wrap" style="display: {{ get_option('enable_google_login') == 1 ? 'block' : 'none' }};">
                            <hr />
                            <div class="form-group">
                                <label for="google_client_id" class="col-sm-4 control-label">@lang('app.google') @lang('app.client_id')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="google_client_id" value="{{ get_option('google_client_id') }}" name="google_client_id" placeholder="@lang('app.google_client_id')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="google_client_secret" class="col-sm-4 control-label">@lang('app.google') @lang('app.client_secret')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="google_client_secret" value="{{ get_option('google_client_secret') }}" name="google_client_secret" placeholder="@lang('app.app_secret')">
                                </div>
                            </div>

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
    </div> <!-- /#dashboard -->
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
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' }
                });
            });

            /**
             * show or hide stripe and paypal settings wrap
             */
            $('#enable_facebook_login').click(function(){
                if ($(this).prop('checked')){
                    $('#facebook_login_api_wrap').slideDown();
                }else{
                    $('#facebook_login_api_wrap').slideUp();
                }
            });
            $('#enable_google_login').click(function(){
                if ($(this).prop('checked')){
                    $('#google_login_api_wrap').slideDown();
                }else{
                    $('#google_login_api_wrap').slideUp();
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