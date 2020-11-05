@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.categories')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.catogeries.index') }}"> @lang('site.categories')</a></li>
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

                    <form action="{{ route('dashboard.catogeries.store') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}
<div class="row">
                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group col-sm-6">
                            @if(count(config('translatable.locales'))>1)
                                <label>@lang('site.' . $locale . '.name')</label>
                        @else
                        <label>@lang('site.name')</label>
                        @endif
                                <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ old($locale . '.name') }}">
                            </div>
                        @endforeach
</div>


                        <div class="row">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group col-md-6">
                                    @if(count(config('translatable.locales'))>1)
                                        <label>@lang('site.' . $locale . '.description')</label>
                                    @else
                                        <label>@lang('site.description')</label>
                                    @endif
                                    <textarea class="textarea" name="{{ $locale }}[description]" row="5"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old($locale . '.description') }}</textarea>
                                </div>
                            @endforeach
                        </div>





                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label>@lang('site.image')</label>
                            <input type="file" name="icons" class="form-control image"  required>

                        </div>

                        <div class="form-group col-sm-6">
                            <img src="{{ asset('public/uploads/icons') }}" style="width: 100px"
                                 class="img-thumbnail image-preview" alt="">
                        </div>
                        </div>


<div class="row">

                        <div class="col-md-3">
                            <div class="form-group mt-1">
                                <input type="radio"
                                       name="type"
                                       value="1"
                                       checked
                                       class="switchery"
                                       data-color="success"
                                />

                                <label
                                    class="card-title ml-1">
                                    قسم رئيسي
                                </label>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mt-1">
                                <input type="radio"
                                       name="type"
                                       value="2" id="clss"
                                       class="switchery" data-color="success"
                                />

                                <label
                                    class="card-title ml-1">
                                    قسم فرعي
                                </label>

                            </div>
                        </div>



</div>

                        <div class="row hidden" id="cats_list" >
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="projectinput1"> اختر القسم الرئيسي
                                    </label>
                                    <select name="parent_id" class="select2 form-control">
                                        <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>

                                            @if($categories && $categories -> count() > 0)
                                                @foreach($categories as $category=>$value)

                                                    <option
                                                        value="{{$category}}">{{$value}}</option>
                                                @endforeach
                                            @endif

                                    </select>
                                    @error('parent_id')
                                    <span class="text-danger"> {{$message}}</span>
                                    @enderror

                                </div>
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
