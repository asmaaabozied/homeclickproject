@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.capons')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.capons.index') }}"> @lang('site.capons')</a></li>
                <li class="active">@lang('site.add')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.capons.store') }}" enctype="multipart/form-data" method="post">

                        {{ csrf_field() }}
                        {{ method_field('post') }}



                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.code')</label>

                                <input type="text" name="code" class="form-control" required>

                            </div>




                            <div class="form-group col-md-6">

                                <label>@lang('site.discount')</label>

                            <input type="number" name="discount" class="form-control" required>

                            </div>

                        </div>

                        <div class="row">

                            <!-- Discount Start Date Field -->
                            <div class="form-group col-md-6">
                                <label>@lang('site.start_at')</label>

                                <input type="date" name="start_at" class="form-control date" required>


                            </div>

                            <!-- Discount End Date Field -->
                            <div class="form-group col-md-6">
                                <label>@lang('site.end_at')</label>

                                <input type="date" name="end_at" class="form-control date" required>

                            </div>

                        </div>






                        <div class="form-group">
                            <button type="button" class="btn btn-warning mr-1"
                                    onclick="history.back();">
                                <i class="fa fa-backward"></i> @lang('site.back')
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
<script src="{{ asset('public/dashboard_files/js/jquery.min.js') }}"></script>

