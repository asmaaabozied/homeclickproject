@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.products')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.products')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">





{{--                               @if (auth()->user()->hasPermission('create_sponsers'))--}}


                                <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products')</h3>


                                {{--
 @else--}}
{{--                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>--}}
{{--                               @endif--}}


                        </div>


                <div class="box-body">

                    @if ($products->count() > 0)

                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.categories')</th>

                                <th>@lang('site.price')</th>

                                <th>@lang('site.image')</th>




                                <th>@lang('site.created_at')</th>
                                <th>@lang('site.status')</th>

                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($products as $index=>$product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td><img src="{{asset('uploads/'.$product->image)}}" style="width:100px; height:100px"></td>

                                    <td>{{isset($product->created_at) ? $product->created_at->diffForHumans() :'' }}</td>
                                    <td>

                                        <form action="{{ route('dashboard.products.status', $product->id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}

                                            @if( $product->status==1)
                                                <button type="submit" class="btn btn-success update btn-sm">
                                                    <i class="fa fa-check"></i> @lang('site.online')
                                                </button>
                                            @elseif( $product->status==0)
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-close"></i> @lang('site.offline')
                                                </button>
                                            @endif

                                        </form>

                                    </td>



                                    <td>
{{--                                        @if (auth()->user()->hasPermission('edit_sponsers'))--}}

{{--                                        @else--}}
{{--                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>--}}
{{--                                        @endif--}}
{{--                                            @if (auth()->user()->hasPermission('delete_sponsers'))--}}


                                            <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
{{--                                        @else--}}
{{--                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>--}}
{{--                                            @endif--}}
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->





                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif
                </div><!-- end of box header -->
                </div><!-- end of box body -->
        </section>

            <!-- end of box -->



    </div><!-- end of content wrapper -->


@endsection
