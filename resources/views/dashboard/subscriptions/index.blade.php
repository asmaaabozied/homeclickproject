@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.subscriptions')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.subscriptions')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.subscriptions') <small>{{ $subs->total() }}</small></h3>

                    <form action="{{ route('dashboard.subscriptions.index') }}" method="get">

                        <div class="row">



                            <div class="col-md-4">
                                @if (auth()->user()->hasPermission('create_cases'))

                                    <a href="{{ route('dashboard.subscriptions.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                 @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($subs->count() > 0)

                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>

                                <th>@lang('site.price')</th>

                                <th>@lang('site.start_at')</th>

                                <th>@lang('site.end_at')</th>

                                <th>@lang('site.created_at')</th>

                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($subs as $index=>$sub)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{isset($sub->name)?$sub->name: '' }}</td>
                                    <td>{{ isset($sub->price) ?$sub->price : '' }}</td>
                                    <td>{{ isset($sub->start_at) ?$sub->start_at : '' }}</td>
                                    <td>{{ isset($sub->end_at) ?$sub->end_at : '' }}</td>




                                    <td>{{ isset($sub->created_at) ? $sub->created_at->diffForHumans() :''	 }}</td>



                                    <td>

                                        @if (auth()->user()->hasPermission('update_cases'))

                                            <a href="{{ route('dashboard.subscriptions.edit', $sub->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_cases'))


                                            <form action="{{ route('dashboard.subscriptions.destroy', $sub->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif

                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

{{--                        {{ $case->appends(request()->query())->links() }}--}}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
