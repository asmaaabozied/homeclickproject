@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.sendemail')  ({{$user->name}}) </h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>

            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">

                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.SendEmail', $user->id) }}" enctype="multipart/form-data" method="post">

                        {{ csrf_field() }}
                        {{ method_field('put') }}






                        <div class="row">



                            <div class="form-group col-md-6">

                                <label>@lang('site.email')</label>

                            <input type="text" name="email" class="form-control"  value="{{$user->email}}">

                            </div>

                        </div>


                        <div class="row">



                            <div class="form-group col-md-6">

                                <label>@lang('site.password')</label>

                                <input type="text" name="password" class="form-control" >

                            </div>

                        </div>

                        <div class="row">



                            <div class="form-group col-md-6">

                                <label>@lang('site.password_confirmation')</label>

                                <input type="text" name="password_confirmation" class="form-control" >

                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> @lang('site.sendemail')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
<script src="{{ asset('public/dashboard_files/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.textarea').summernote({
            // set editor height
            minHeight: 300,             // set minimum height of editor
            // maxHeight: 300,
        });
        $('.textarea')
    });
</script>
