@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.contact')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.contact')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.contact') <small>{{ $contacts->total() }}</small></h3>

                    <form action="{{ route('dashboard.contacts.index') }}" method="get">

{{--                        <div class="row">--}}

{{--                            <div class="col-md-4">--}}
{{--                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>--}}

{{--                            </div>--}}

{{--                        </div>--}}
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($contacts->count() > 0)

                        <table class="table table-hover"  id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>complain</th>

                                <th>@lang('site.email')</th>

                                <th>@lang('site.contact_message')</th>
                                <th>@lang('site.created_at')</th>
                                <th>@lang('site.status')</th>

                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($contacts as $index=>$contact)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->Catogery->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->message }}</td>

                                    <td>{{ isset($contacvt->created_at) ? $contact->created_at->diffForHumans() :''	 }}</td>

                                    <td>

                                        <form action="{{ route('dashboard.contacts.status', $contact->id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}

                                            @if( $contact->status==1)
                                                <button type="submit" class="btn btn-success update btn-sm">
                                                    <i class="fa fa-check"></i> @lang('site.open')
                                                </button>
                                            @elseif( $contact->status==0)
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-close"></i> @lang('site.close')
                                                </button>
                                            @endif

                                        </form>



                                    </td>

{{--                                            @if (auth()->user()->hasPermission('delete_contacts'))--}}

                                      <td>
                                            <form action="{{ route('dashboard.contacts.destroy', $contact->id) }}" method="post" style="display: inline-block">
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

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
