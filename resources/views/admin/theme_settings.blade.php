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

                    <h4>@lang('app.variable_info')</h4>
                    <pre>[year], [copyright_sign], [site_name]</pre>
                    <hr />

                    <div class="form-group">
                        <label for="additional_css" class="col-sm-4 control-label">@lang('app.additional_css') </label>
                        <div class="col-sm-8">
                            <textarea name="additional_css" class="form-control">{{ get_option('additional_css') }}</textarea>
                            <p class="text-info">@lang('app.additional_css_help_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="additional_js" class="col-sm-4 control-label">@lang('app.additional_js') </label>
                        <div class="col-sm-8">
                            <textarea name="additional_js" class="form-control">{{ get_option('additional_js') }}</textarea>
                            <p class="text-info">@lang('app.additional_js_help_text')</p>
                        </div>
                    </div>

                    <legend>@lang('app.footer')</legend>

                    <div class="form-group">
                        <label for="footer_about_us" class="col-sm-4 control-label">@lang('app.footer_about_us') </label>
                        <div class="col-sm-8">
                            <textarea name="footer_about_us" class="form-control">{{ get_option('footer_about_us') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="footer_address" class="col-sm-4 control-label">@lang('app.footer_address') </label>
                        <div class="col-sm-8">
                            <textarea name="footer_address" class="form-control">{{ get_option('footer_address') }}</textarea>
                            <p>@lang('app.example')</p>
<pre>{{'<li><i class="fa fa-map-marker"></i> <span>4122 Riverside Drive
<br /> Clarkesville, GA 30523 <br /> United States</span></li>
<li><i class="fa fa-phone"></i> <span>+01 234 567890</span></li>
<li><i class="fa fa-envelope-o"></i> <span>demo@stackthemes.net</span></li>'}}</pre>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="copyright_text" class="col-sm-4 control-label">@lang('app.copyright_text') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="copyright_text" value="{{ get_option('copyright_text') }}" name="copyright_text" placeholder="@lang('app.copyright_text')">
                            <pre>[copyright_sign] [year] [site_name], All rights reserved.</pre>
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
    </div> <!-- /#dashboard wrap -->
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