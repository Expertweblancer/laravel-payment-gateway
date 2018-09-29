@extends('layouts.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div class="dashboard-wrap">

        <div class="container">

            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">
                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }} <a href="{{route('edit_campaign', $campaign_id)}}" class="btn btn-info pull-right"><i class="fa fa-arrow-circle-o-left"></i> @lang('app.back_to_campaign')</a> </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-1 col-xs-12">

                            {{ Form::open(['class' => 'form-horizontal', 'files' => true]) }}


                            <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                                <label for="title" class="col-sm-4 control-label">@lang('app.title')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="title" value="{{ old('title') }}" name="title" placeholder="@lang('app.title')">
                                    {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                                <label for="description" class="col-sm-4 control-label">@lang('app.description')</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="description">{{old('description')}}</textarea>
                                    {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">@lang('app.save_update')</button>
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>

                    </div>

                        @if($updates->count())
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-bordered categories-lists">
                                        <tr>
                                            <th>@lang('app.title') </th>
                                            <th>@lang('app.description') </th>
                                            <th>@lang('app.action') </th>
                                        </tr>
                                        @foreach($updates as $update)
                                            <tr>
                                                <td> {{ $update->title }}  </td>
                                                <td> {{ $update->description }}  </td>
                                                <td width="100">
                                                    <a href="{{ route('update_update', [$update->campaign_id,$update->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> </a>
                                                    <a href="javascript:;" class="btn btn-danger btn-xs" data-id="{{ $update->id }}"><i class="fa fa-trash"></i> </a>
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
                    url: '{{ route('delete_update') }}',
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