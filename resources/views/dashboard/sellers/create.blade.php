@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.sellers')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.sellers.index') }}"> @lang('site.sellers')</a></li>
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

                    <form action="{{ route('dashboard.sellers.store') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                        <div class="row">
                        <div class="form-group col-md-6">

                        <label>@lang('site.name')</label>

                                <input type="text" name="name" class="form-control"  >
                            </div>


                        <div class="form-group col-md-6">

                            <label>@lang('site.phone')</label>

                            <input type="text" name="phone" class="form-control"  >
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6">

                            <label>@lang('site.email')</label>

                            <input type="text" name="email" class="form-control"  >
                        </div>

{{--                        <div class="form-group col-md-6">--}}

{{--                            <label>@lang('site.jobs')</label>--}}

{{--                            <input type="text" name="job" class="form-control"  >--}}
{{--                        </div>--}}
{{--                            --}}

                            <div class="form-group col-md-6">
                                <label>@lang('site.images')</label>
                                <input type="file" class="form-control"  name='images[]'>
                            </div>
{{--                            <div class="form-group col-sm-3">--}}
{{--                                <img src="{{ asset('public/uploads/images') }}" style="width: 100px"--}}
{{--                                     class="img-thumbnail image-preview" alt="">--}}
{{--                            </div>--}}
                        </div>

<div class="row">
                        <div class="form-group col-md-6">

                            <label>@lang('site.subscriptions')</label>

                            <select class="form-control select2" name="subscribe_id" id="parent" required>
                                <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                @foreach($subscribes as $id => $item)
                                    <option value="{{$id}}" >{{$item}}</option>
                                @endforeach
                            </select>

                        </div>
             <div class="form-group col-md-6">

                 <label>@lang('site.image')</label>

                 <input type="file" class="form-control"  name='image'>


             </div>
</div>

                        <div class="row">
                            <div class="form-group col-md-6">
                            <label>@lang('site.password')</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                            <div class="form-group col-md-6">
                            <label>@lang('site.password_confirmation')</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-md-6">

                                <label>@lang('site.categories')</label>

                                <select class="form-control select2" name="catogery_id" id="parent" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                    @foreach($catogeries as $id => $item)
                                        <option value="{{$id}}" >{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group col-md-6">

                                <label>@lang('site.geography')</label>

                                <select class="form-control select2" name="geography_id" id="parent" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                    @foreach($cities as $id => $item)
                                        <option value="{{$id}}" >{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>


                        </div>

                        <div class="row">

                        <div class="form-group col-md-6">

                            <label>@lang('site.description')</label>
                            <textarea  name="description" class="form-control" id="summary-ckeditor" rows="11" cols="80" ></textarea>
                        </div>




                        </div>

<div class="row">
    <div class="form-group col-md-6">


        <input  type="checkbox" name="common"  value="1" >
        <label>common</label>
    </div>
</div>
                    <br>
<div class="row">

                         <div class="form-group col-md-6">
                             <button type="button" class="btn btn-warning mr-1"
                                     onclick="history.back();">
                                 <i class="fa fa-backward"></i> @lang('site.back')
                             </button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>
</div>
                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
