@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.sellers')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.sellers.index') }}"> @lang('site.sellers')</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.sellers.update', $store->id) }}" method="post"  enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}
<div class="row">
                        <div class="form-group col-md-6">

                            <label>@lang('site.name')</label>

                            <input type="text" name="name" class="form-control" value="{{$store->name}}" >
                        </div>


                        <div class="form-group col-md-6">

                            <label>@lang('site.phone')</label>

                            <input type="text" name="phone" class="form-control" value="{{$store->phone}}" >
                        </div>
</div>
  <div class="row">

                        <div class="form-group col-md-6">

                            <label>@lang('site.email')</label>

                            <input type="text" name="email" class="form-control" value="{{$store->email}}" >
                        </div>

                        <div class="form-group col-md-6">

                            <label>@lang('site.subscriptions')</label>

                            <select class="form-control select2" name="subscribe_id" id="parent" required>
                                <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                @foreach($subscribes as $id => $item)
                                    <option value="{{$id}}" @if($store->subscribe_id ==$id) selected @endif >{{$item}}</option>
                                @endforeach
                            </select>
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
                            <label>@lang('site.images')</label>
                            <input type="file" id="file" multiple="multiple" class="file-input form-control" accept="image/*"
                                   name="images[]">
                        </div>



        <div class="form-group col-md-6">
            <label>@lang('site.image')</label>
            <input type="file" id="file"  class="file-input form-control"
                   name="image">
        </div>



</div>
                        <div class="row">
                            <div class="form-group col-md-6">

                                <label>@lang('site.categories')</label>

                                <select class="form-control select2" name="catogery_id" id="parent" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                    @foreach($catogeries as $id => $item)
                                        <option value="{{$id}}" @if($store->catogery_id ==$id) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>


                            <div class="form-group col-md-6">

                                <label>@lang('site.geography')</label>

                                <select class="form-control select2" name="geography_id" id="parent" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                    @foreach($cities as $id => $item)
                                        <option value="{{$id}}"   @if($store->geography_id ==$id) selected @endif >{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>


                        </div>


                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.lat')</label>

                                <input type="text" name="lat" class="form-control"  >
                            </div>

                            <div class="form-group col-md-6">

                                <label>@lang('site.lon')</label>

                                <input type="text" name="lon" class="form-control"  >
                            </div>


                        </div>



                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.value')</label>

                                <input type="text" name="value" class="form-control" value="{{$store->value}}" >
                            </div>

                            <div class="form-group col-md-6">

                                <label>@lang('site.hours')</label>

                                <input type="text" name="hours" class="form-control" value="{{$store->hours}}" >
                            </div>


                        </div>



         <div class="row">

                        <div class="form-group col-md-6">

                            <label>@lang('site.description')</label>
                            <textarea  name="description" class="form-control" id="summary-ckeditor" rows="11" cols="80" value="" >{{$store->description}}</textarea>
                        </div>


             <div class="form-group col-md-6">

                 <label>@lang('site.icon')</label>

                 <input type="file" class="form-control"  name='icon'>


             </div>
                </div>


                        <div class="row">
                            <div class="form-group col-md-6">


                                <input  type="checkbox" name="common"   @if(@$store->common) checked @endif value = 1>
                                <label>common</label>
                            </div>
                        </div>



                        <div class="form-group">
                            <button type="button" class="btn btn-warning mr-1"
                                    onclick="history.back();">
                                <i class="fa fa-backward"></i> @lang('site.back')
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
