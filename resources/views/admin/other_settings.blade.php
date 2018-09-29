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


                    <div class="row">
                        <div class="col-xs-12">

                            {!! Form::open(['class'=>'form-horizontal', 'files'=>'true']) !!}

                            <div class="form-group  {{ $errors->has('logo')? 'has-error':'' }}">
                                <label class="col-sm-4 control-label">@lang('app.site_logo')</label>
                                <div class="col-sm-8">

                                    @if(logo_url())
                                        <img src="{{ logo_url() }}" />
                                    @endif


                                    <input type="file" id="logo" name="logo" class="filestyle" >
                                    {!! $errors->has('logo')? '<p class="help-block">'.$errors->first('logo').'</p>':'' !!}
                                </div>
                            </div>


                            <hr />

                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <button type="submit" class="btn btn-primary">@lang('app.edit')</button>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>

                </div>   <!-- /#page-wrapper -->

            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->
    </div> <!-- /#dashboard wrap -->
@endsection

@section('page-js')


@endsection