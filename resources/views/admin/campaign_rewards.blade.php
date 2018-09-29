@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css')}}">
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
                                <h1 class="page-header"> {{ $title }} <a href="{{route('edit_campaign', $campaign->id)}}" class="btn btn-info pull-right"><i class="fa fa-arrow-circle-o-left"></i> @lang('app.back_to_campaign')</a> </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-1 col-xs-12">

                            {{ Form::open(['class' => 'form-horizontal', 'files' => true]) }}


                            <div class="form-group {{ $errors->has('amount')? 'has-error':'' }}">
                                <label for="amount" class="col-sm-4 control-label">@lang('app.amount')</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="amount" value="{{ old('amount') }}" name="amount" placeholder="@lang('app.amount')">
                                    {!! $errors->has('amount')? '<p class="help-block">'.$errors->first('amount').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                                <label for="description" class="col-sm-4 control-label">@lang('app.description')</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="description">{{old('description')}}</textarea>
                                    {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('quantity')? 'has-error':'' }}">
                                <label for="quantity" class="col-sm-4 control-label">@lang('app.quantity')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="quantity" value="{{ old('quantity') }}" name="quantity" placeholder="@lang('app.quantity')">
                                    {!! $errors->has('quantity')? '<p class="help-block">'.$errors->first('quantity').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('estimated_delivery')? 'has-error':'' }}">
                                <label for="estimated_delivery" class="col-sm-4 control-label">@lang('app.estimated_delivery')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="estimated_delivery" value="{{ old('estimated_delivery') }}" name="estimated_delivery" placeholder="@lang('app.estimated_delivery')">
                                    {!! $errors->has('estimated_delivery')? '<p class="help-block">'.$errors->first('estimated_delivery').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">@lang('app.save_reward')</button>
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>

                    </div>

                        @if($rewards->count())
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-bordered categories-lists">
                                        <tr>
                                            <th>@lang('app.amount') </th>
                                            <th>@lang('app.description') </th>
                                            <th>@lang('app.quantity') </th>
                                            <th>@lang('app.estimated_delivery') </th>
                                            <th>@lang('app.action') </th>
                                        </tr>
                                        @foreach($rewards as $reward)
                                            <tr>
                                                <td> {{ get_amount($reward->amount) }}  </td>
                                                <td> {{ $reward->description }}  </td>
                                                <td> {{ $reward->quantity }}  </td>
                                                <td> {{ $reward->estimated_delivery }}  </td>
                                                <td width="100">
                                                    <a href="{{ route('reward_update', [$reward->campaign_id,$reward->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> </a>
                                                    <a href="javascript:;" class="btn btn-danger btn-xs" data-id="{{ $reward->id }}"><i class="fa fa-trash"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif



                </div>   <!-- /#page-wrapper -->




            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->

    </div>
@endsection

@section('page-js')

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        $(function () {
            $('#estimated_delivery').datetimepicker({format: 'YYYY-MM'});
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-danger').on('click', function (e) {
                if (!confirm("@lang('app.are_you_sure_undone')")) {
                    e.preventDefault();
                    return false;
                }

                var selector = $(this);
                var data_id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_reward') }}',
                    data: {data_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                        }
                    }
                });
            });
        });
    </script>
@endsection