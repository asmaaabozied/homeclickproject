@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.offers')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')
                    </a></li>
                <li class="active">@lang('site.offers')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.offers')
                        <small>{{ $offers->total() }}</small></h3>


                    <div class="row">


                        <div class="col-md-4">

                            @if (auth()->user()->hasPermission('create_consultations'))

                                <a href="{{ route('dashboard.offers.create') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> @lang('site.add')</a>
                            @else
                                <a href="#" class="btn btn-primary disabled"><i
                                        class="fa fa-plus"></i> @lang('site.add')</a>
                            @endif
                        </div>

                    </div>

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($offers->count() > 0)

                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.products')</th>
                                <th>@lang('site.categories')</th>
                                <th>@lang('site.sellers')</th>
                                <th>@lang('site.discount')</th>
                                <th>@lang('site.start_at')</th>

                                <th>@lang('site.end_at')</th>

                                @if (auth()->user()->hasPermission('update_consultations','delete_consultations'))

                                    <th>@lang('site.action')</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($offers as $index=>$value)

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{$value->product->name}}</td>
                                    <td>{{$value->catogery->name}}</td>
                                    <td>{{$value->store->name}}</td>
                                    <td>{{isset($value->discount) ?$value->discount :'' }}</td>
                                    <td>{{isset($value->start_at) ? $value->start_at:'' }}</td>
                                    <td>{{isset($value->end_at) ? $value->end_at:'' }}</td>


                                    <td>


                                        @if (auth()->user()->hasPermission('update_consultations'))
                                            <a href="{{ route('dashboard.offers.edit', $value->id) }}"
                                               class="btn btn-info btn-sm"><i
                                                    class="fa fa-edit "></i> @lang('site.edit')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_consultations'))
                                            <form action="{{ route('dashboard.offers.destroy', $value->id) }}"
                                                  method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                        class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i
                                                    class="fa fa-trash"></i> @lang('site.delete')</button> --}}
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $offers->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
