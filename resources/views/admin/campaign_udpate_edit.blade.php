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
                                <h1 class="page-header"> {{ $title }}  </h1>
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
                                    <input type="text" class="form-control" id="title" value="{{ $update->title }}" name="title" placeholder="@lang('app.title')">
                                    {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                                <label for="description" class="col-sm-4 control-label">@lang('app.description')</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="description">{{$update->description}}</textarea>
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

                </div>   <!-- /#page-wrapper -->


            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->

    </div>
@endsection