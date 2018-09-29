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
                            <div class="profile-avatar">
                                <img src="{{ $user->get_gravatar(100) }}" class="img-thumbnail img-circle" width="100" />
                            </div>

                            <table class="table table-bordered table-striped">

                                <tr>
                                    <th>@lang('app.name')</th>
                                    <td>{{ $user->name }}</td>
                                </tr>

                                <tr>
                                    <th>@lang('app.email')</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('app.gender')</th>
                                    <td>{{ ucfirst($user->gender) }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('app.phone')</th>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('app.address')</th>
                                    <td>{{ $user->address }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('app.country')</th>
                                    <td>
                                        @if($user->country)
                                            {{ $user->country->name }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>@lang('app.created_at')</th>
                                    <td>{{ $user->signed_up_datetime() }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('app.status')</th>
                                    <td>{{ $user->status_context() }}</td>
                                </tr>
                            </table>

                            <a href="{{ route('profile_edit') }}"><i class="fa fa-pencil-square-o"></i> @lang('app.edit') </a>

                        </div>
                    </div>


                </div>   <!-- /#page-wrapper -->




            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->
    </div> <!-- /#dashboard wrap -->
@endsection

@section('page-js')

@endsection