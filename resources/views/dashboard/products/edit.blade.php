@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.products.index') }}"> @lang('site.products')</a></li>
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

                    <form action="{{ route('dashboard.products.update', $product->id) }}" method="post"  enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="row">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group col-md-6 ">
                                    @if(count(config('translatable.locales'))>1)
                                        <label>@lang('site.' . $locale . '.name')</label>
                                    @else
                                        <label>@lang('site.name')</label>
                                    @endif
                                    <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ $product->translate($locale)->name }}">
                                </div>
                            @endforeach


                        </div>


                        <div class="row">
                            <div class="form-group col-md-6">

                                <label>@lang('site.price')</label>
                                <input type="text" name="price" class="form-control" value="{{$product->price}}">


                            </div>



                            <div class="form-group col-md-6">

                                <label>@lang('site.categories')</label>

                                <select class="form-control select2" name="catogery_id" id="parent" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                    @foreach($catogeries as $id => $item)
                                        <option value="{{$id}}"    @if($product->catogery_id ==$id) selected @endif >{{$item}}</option>
                                    @endforeach
                                </select>


                            </div>

                        </div>

                        <div class="row">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group col-md-6 ">
                                    @if(count(config('translatable.locales'))>1)
                                        <label>@lang('site.' . $locale . '.description')</label>
                                    @else
                                        <label>@lang('site.description')</label>
                                    @endif

                                    <textarea class="textarea" name="{{ $locale }}[description]" row="5"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $product->translate($locale)->description }}</textarea>                            </div>
                            @endforeach
                        </div>


                        <div class="row">

                            <div class="form-group col-sm-6 ">
                                {{--                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=" {{ trans('category.fields.image_help') }}"></i> &nbsp;--}}
                                <label>@lang('site.image')</label>


                                <input type="file" onchange="readURL(this, 'ImagePreview', 'ImagePreview');" name="image" class="form-control image" >
                            </div>

                            <div class="form-group col-sm-6">
                                <img src="{{ asset('public/uploads/image') }}" style="width: 100px"
                                     class="img-thumbnail image-preview" alt="">
                            </div>

                        </div>


                        <div class="row">

                            <div class="form-group col-md-6">
                                <label>@lang('site.images')</label>
                                <input type="file" class="form-control"  name='images[]'>
                            </div>
                            <div class="form-group col-sm-6">
                                <img src="{{ asset('public/uploads/images') }}" style="width: 100px"
                                     class="img-thumbnail image-preview" alt="">
                            </div>
                        </div>



                        <div class="row">
                            <div class="form-group col-md-6">


                                <input  type="checkbox" name="common"   @if(@$product->common) checked @endif value = 1>
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
