@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote.css')}}">
@endsection

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

                    <div class="row">
                        <div class="col-md-10 col-xs-12">

                            <div class="alert alert-info">
                                <h5> <i class="fa fa-money"></i> @lang('app.you_will_get') {{$campaign->campaign_owner_commission}}% @lang('app.of_total_raised')</h5>
                            </div>


                            <ul class="campaign-update-nav">
                                <li><a href="{{route('edit_campaign_rewards', $campaign->id)}}"> @lang('app.rewards') ({{$campaign->rewards->count()}})</a> </li>
                                <li><a href="{{route('edit_campaign_updates',  $campaign->id )}}"> @lang('app.updates') ({{$campaign->updates->count()}})</a> </li>
                                <li><a href="{{route('edit_campaign_faqs', $campaign->id)}}"> @lang('app.faq') ({{$campaign->faqs->count()}})</a> </li>
                            </ul>

                            {{ Form::open(['id'=>'startCampaignForm', 'class' => 'form-horizontal', 'files' => true]) }}
                            <legend>@lang('app.campaign_info')</legend>

                            <div class="form-group  {{ $errors->has('category')? 'has-error':'' }}">
                                <label for="category" class="col-sm-4 control-label">@lang('app.category') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="category">
                                        <option value="">@lang('app.select_a_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($campaign->category_id == $category->id) selected="selected" @endif >{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('category')? '<p class="help-block">'.$errors->first('category').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                                <label for="title" class="col-sm-4 control-label">@lang('app.title') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="title" value="{{ $campaign->title }}" name="title" placeholder="@lang('app.title')">
                                    {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.great_title_info')</p>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('short_description')? 'has-error':'' }}">
                                <label for="short_description" class="col-sm-4 control-label">@lang('app.short_description')</label>
                                <div class="col-sm-8">
                                    <textarea name="short_description" class="form-control" rows="3">{{$campaign->short_description}}</textarea>
                                    {!! $errors->has('short_description')? '<p class="help-block">'.$errors->first('short_description').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                                <label for="description" class="col-sm-3 control-label">@lang('app.description') <span class="field-required">*</span></label>
                                <div class="col-sm-12">
                                    <div class="alert alert-info"> @lang('app.image_insert_limitation') </div>
                                </div>
                                <div class="col-sm-12">
                                    <textarea name="description" class="form-control description" rows="8">{{ $campaign->description}}</textarea>
                                    {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.description_info_text')</p>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('goal')? 'has-error':'' }}">
                                <label for="goal" class="col-sm-4 control-label">@lang('app.goal') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="goal" value="{{ $campaign->goal }}" name="goal" placeholder="@lang('app.goal')">
                                    {!! $errors->has('goal')? '<p class="help-block">'.$errors->first('goal').'</p>':'' !!}
                                </div>
                            </div>
                            
                            {{--<div class="form-group {{ $errors->has('min_amount')? 'has-error':'' }}">
                                <label for="min_amount" class="col-sm-4 control-label">@lang('app.min_amount')</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="min_amount" value="{{ $campaign->min_amount }}" name="min_amount" placeholder="@lang('app.min_amount')">
                                    {!! $errors->has('min_amount')? '<p class="help-block">'.$errors->first('min_amount').'</p>':'' !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('max_amount')? 'has-error':'' }}">
                                <label for="max_amount" class="col-sm-4 control-label">@lang('app.max_amount')</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="max_amount" value="{{ $campaign->max_amount }}" name="max_amount" placeholder="@lang('app.max_amount')">
                                    {!! $errors->has('max_amount')? '<p class="help-block">'.$errors->first('max_amount').'</p>':'' !!}
                                </div>
                            </div>--}}

                            <div class="form-group {{ $errors->has('recommended_amount')? 'has-error':'' }}">
                                <label for="recommended_amount" class="col-sm-4 control-label">@lang('app.recommended_amount')</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="recommended_amount" value="{{ $campaign->recommended_amount}}" name="recommended_amount" placeholder="@lang('app.recommended_amount')">
                                    {!! $errors->has('recommended_amount')? '<p class="help-block">'.$errors->first('recommended_amount').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('amount_prefilled')? 'has-error':'' }}">
                                <label for="amount_prefilled" class="col-sm-4 control-label">@lang('app.amount_prefilled')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amount_prefilled" value="{{ $campaign->amount_prefilled}}" name="amount_prefilled" placeholder="@lang('app.amount_prefilled')">
                                    {!! $errors->has('amount_prefilled')? '<p class="help-block">'.$errors->first('amount_prefilled').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.amount_prefilled_info_text')</p>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('end_method')? 'has-error':'' }}">
                                <label for="end_method" class="col-sm-4 control-label">@lang('app.campaign_end_method')</label>
                                <div class="col-sm-8">

                                    <label>
                                        <input type="radio" name="end_method"  value="goal_achieve" @if($campaign->end_method == 'goal_achieve') checked="checked" @endif > @lang('app.after_goal_achieve')
                                    </label> <br />

                                    <label>
                                        <input type="radio" name="end_method" value="end_date"  @if($campaign->end_method == 'end_date') checked="checked" @endif > @lang('app.after_end_date')
                                    </label> <br />

                                    {{--<label>
                                        <input type="radio" name="end_method" value="both"  @if($campaign->end_method == 'both') checked="checked" @endif > @lang('app.both_need')
                                    </label>--}}

                                    {!! $errors->has('end_method')? '<p class="help-block">'.$errors->first('end_method').'</p>':'' !!}

                                    <p class="text-info"> @lang('app.end_method_info_text')</p>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('video')? 'has-error':'' }}">
                                <label for="video" class="col-sm-4 control-label">@lang('app.video')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="video" value="{{$campaign->video}}" name="video" placeholder="@lang('app.video')">
                                    {!! $errors->has('video')? '<p class="help-block">'.$errors->first('video').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.video_info_text')</p>
                                </div>
                            </div>


                            <div class="form-group  {{ $errors->has('country_id')? 'has-error':'' }}">
                                <label for="country_id" class="col-sm-4 control-label">@lang('app.country')<span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="country_id">

                                        <option value="">@lang('app.select_a_country')</option>

                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" @if($campaign->country_id == $country->id) selected="selected" @endif >{{$country->name}}</option>
                                        @endforeach

                                    </select>
                                    {!! $errors->has('country_id')? '<p class="help-block">'.$errors->first('country_id').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('address')? 'has-error':'' }}">
                                <label for="address" class="col-sm-4 control-label">@lang('app.address')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="address" value="{{ $campaign->address}}" name="address" placeholder="@lang('app.address')">
                                    {!! $errors->has('address')? '<p class="help-block">'.$errors->first('address').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('start_date')? 'has-error':'' }}">
                                <label for="start_date" class="col-sm-4 control-label">@lang('app.start_date')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="start_date" value="{{ $campaign->start_date}}" name="start_date" placeholder="@lang('app.start_date')">
                                    {!! $errors->has('start_date')? '<p class="help-block">'.$errors->first('start_date').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('end_date')? 'has-error':'' }}">
                                <label for="end_date" class="col-sm-4 control-label">@lang('app.end_date')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="end_date" value="{{ $campaign->end_date}}" name="end_date" placeholder="@lang('app.end_date')">
                                    {!! $errors->has('end_date')? '<p class="help-block">'.$errors->first('end_date').'</p>':'' !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('feature_image')? 'has-error':'' }}">
                                <label for="end_date" class="col-sm-4 control-label">@lang('app.feature_image')</label>
                                <div class="col-sm-8">

                                    <label for="feature_image" class="img_upload"><i class="fa fa-cloud-upload"></i> </label>
                                    <input type="file" id="feature_image" name="feature_image" style="display: none;" />
                                    <div id="feature_image_preview">@if($campaign->feature_image) <img src="{{ $campaign->feature_img_url()}}" /> @endif</div>

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">@lang('app.submit_new_campaign')</button>
                                </div>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>



                </div>

            </div>
        </div>
    </div>


@endsection

@section('page-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('assets/plugins/summernote/summernote.js')}}"></script>
    <script>
        $(function () {
            $('#start_date, #end_date').datetimepicker({format: 'YYYY-MM-DD'});
        });

        $(document).ready(function() {
            $('.description').summernote({  height: 300});

            $('#feature_image').change(function(){

                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#feature_image_preview').html('<img src="'+e.target.result+'" />');
                    };
                    reader.readAsDataURL(input.files[0]);
                }

            });
        });

    </script>
@endsection