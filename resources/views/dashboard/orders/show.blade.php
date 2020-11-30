@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.products')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.products') </h3>
                    @if ($orders->count() > 0)


                    <table class="table table-hover" id="table">
                    <thead>
               <tr>
                 <th>#</th>


                   <th>@lang('site.name')</th>
                   <th>@lang('site.categories')</th>

                   <th>@lang('site.price')</th>
                   <th>@lang('site.total')</th>
                   <th>@lang('site.image')</th>

                   <th>@lang('site.created_at')</th>
                   <th>@lang('site.status')</th>


            </tr>
               </thead>

                        <tbody>
                        @foreach ($orders as $index=>$product)
                        <tr>
                            <th>#</th>
                            <td>{{ $product->name  ?? ''}}</td>
                            <td>{{ $product->category->name ?? '' }}</td>
                            <td>{{ $product->price ?? '' }}</td>
                            <td>{{ $product->pivot->quantity ?? '' }}</td>

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


                        </tr>
                        @endforeach


                        </tbody>
                    </table>

                        @endif


                </div><!-- end of box header -->
<hr>
                <div class="btn btn-primary form-control align-content-lg-center">


                </div>



                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('site.file')</th>

                        <th>@lang('site.created_at')</th>

                        <th>@lang('site.download')</th>


                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($orders as $index=>$products)
                        @foreach($products->images as $index=>$product)
                        <tr>
                        <th>#</th>


                        <td>
                            <a href="{{asset('uploads/' . $product->image)}}">
                       {{$product->image}}
                            </a>

                        </td>
                        <td> {{isset($product->created_at) ? $product->created_at->diffForHumans() :'' }}</td>

                        <td>
    <button>

     <a  href="{{ route('dashboard.download_file.downloadSingleFile', $product->id) }}" class="fa fa-download fa-1.9x"> @lang('site.download')

       </a> </button>

                        </td>

                        @endforeach
                            @endforeach

                    </tr>


                    </tbody>
                </table>





            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
