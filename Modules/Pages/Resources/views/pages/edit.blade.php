@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.pages')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.pages.index') }}"> @lang('site.pages')</a></li>
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

                    <form action="{{ route('dashboard.pages.update', $page->id) }}" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="page_id" class="form-control"  value="{{ $page->id }}">

                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                @if(count(config('translatable.locales'))>1)
                                    <label>@lang('site.' . $locale . '.name')</label>
                                @else
                                    <label>@lang('site.name')</label>
                                @endif
                                <input type="text" name="{{ $locale }}[name]" class="form-control"  value="{{ $page->translate($locale)->name }}">
                            </div>
                        @endforeach
                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                @if(count(config('translatable.locales'))>1)
                                    <label>@lang('site.' . $locale . '.title')</label>
                                @else
                                    <label>@lang('site.title')</label>
                                @endif
                                <input type="text" name="{{ $locale }}[title]" class="form-control"  value="{{ $page->translate($locale)->title }}">
                            </div>
                        @endforeach
                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                @if(count(config('translatable.locales'))>1)
                                    <label>@lang('site.' . $locale . '.content')</label>
                                @else
                                    <label>@lang('site.content')</label>
                                @endif
                                <textarea class="textarea" name="{{ $locale }}[content]" row="5"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{strip_tags( $page->translate($locale)->content) }}</textarea>
                            </div>

                        @endforeach
                        <div class="form-group">
                            <label>@lang('site.slug')</label>
                            <input type="text" name="slug" class="form-control" value="{{ $page->slug }}"  readonly>
                        </div>


                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
<script>
    $(document).ready(function() {
        $('.textarea').summernote({
            // set editor height
            minHeight: 300,             // set minimum height of editor
            // maxHeight: 300,
        });
    });
</script>
