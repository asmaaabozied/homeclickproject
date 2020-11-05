<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset(auth()->user()->getImagePathAttribute()) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>@lang('site.title')</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>


        <ul class="sidebar-menu" data-widget="tree">

            <li><a href="{{ route('dashboard.welcome') }}"><i
                        class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>@lang('site.management') </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display:none">
                    @if (auth()->user()->hasPermission('read_roles'))
                        <li><a href="{{ route('dashboard.roles.index') }}"><i
                                    class="fa fa-sliders"></i><span>@lang('site.roles')</span></a></li>
                    @endif
                    @if (auth()->user()->hasPermission('read_admins'))
                        <li><a href="{{ route('dashboard.users.index') }}"><i
                                    class="fa fa-cogs"></i><span>@lang('site.admins')</span></a></li>
                    @endif
                    @if (auth()->user()->hasPermission('read_users'))
                        <li><a href="{{ route('dashboard.manage_users.index') }}"><i
                                    class="fa fa-users"></i><span>@lang('site.users')</span></a></li>
                    @endif

{{--                        @if (auth()->user()->hasPermission('read_sellers'))--}}
                        <li><a href="{{ route('dashboard.sellers.index') }}"><i
                                    class="fa fa-users"></i><span>@lang('site.sellers')</span></a></li>
{{--                    @endif--}}
                </ul>
                r
            </li>

            {{--            @if (auth()->user()->hasPermission('read_news'))--}}
            {{--                <li><a href="{{ route('dashboard.news_categories.index') }}"><i class="fa fa-newspaper-o"></i><span>@lang('site.news')</span></a></li>--}}
            {{--@endif--}}
            {{--                @if (auth()->user()->hasPermission('read_categories'))--}}
            {{--            <li class="treeview">--}}
            {{--                <a href="#">--}}
            {{--                    <i class="fa fa-pie-chart"></i>--}}
            {{--                    <span>@lang('site.newss')</span>--}}
            {{--                    <span class="pull-right-container">--}}
            {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
            {{--                    </span>--}}
            {{--                </a>--}}
            {{--                <ul class="treeview-menu" style="display:none">--}}


            {{--                    <li><a href="{{ route('dashboard.newss_subcategories.index') }}"><i class="fa fa-sliders"></i><span>@lang('site.newss')</span></a></li>--}}


            {{--                        <li><a href="{{ route('dashboard.article_subcategories.index') }}"><i class="fa fa-cogs"></i><span>@lang('site.articles')</span></a></li>--}}


            {{--                        <li><a href="{{ route('dashboard.media_subcategories.index') }}"><i class="fa fa-building-o"></i><span>@lang('site.media')</span></a></li>--}}

            {{--                </ul>--}}
            {{--            </li>--}}

            {{--            @endif--}}


            {{--        @if (auth()->user()->hasPermission('read_tags'))--}}
            {{--                <li><a href="{{ route('dashboard.tags.index') }}"><i class="fa fa-th"></i><span>@lang('site.tag')</span></a></li>--}}
            {{--            @endif--}}
            @if (auth()->user()->hasPermission('read_geographies'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-globe"></i>
                        <span>@lang('site.geography')</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu" style="display:none">
                        <li><a href="{{ route('dashboard.countries.index') }}"><i
                                    class="fa fa-flag-o"></i><span>@lang('site.countries')</span></a></li>
                        <li><a href="{{ route('dashboard.cities.index') }}"><i
                                    class="fa fa-building-o"></i><span>@lang('site.cities')</span></a></li>
                    </ul>
                </li>
            @endif

            <li><a href="{{ route('dashboard.pages.index') }}"><i
                        class="fa fa-newspaper-o"></i><span>@lang('site.pages')</span></a></li>
            <li><a href="{{ route('dashboard.offers.index') }}"><i
                        class="fa fa-newspaper-o"></i><span>@lang('site.offers')</span></a></li>
            <li><a href="{{ route('dashboard.subscriptions.index') }}"><i
                        class="fa fa-newspaper-o"></i><span>@lang('site.subscriptions')</span></a></li>
            @if (auth()->user()->hasPermission('read_categories'))

            <li><a href="{{ route('dashboard.capons.index') }}"><i
                        class="fa fa-newspaper-o"></i><span>@lang('site.capons')</span></a></li>

            @endif

            {{--<li class="treeview">--}}
            {{--<a href="#">--}}
            {{--<i class="fa fa-pie-chart"></i>--}}
            {{--<span>الخرائط</span>--}}
            {{--<span class="pull-right-container">--}}
            {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
            {{--</a>--}}
            {{--<ul class="treeview-menu">--}}
            {{--<li>--}}
            {{--<a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a>--}}
            {{--</li>--}}
            {{--<li>--}}
            {{--<a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</li>--}}


            {{--            @if (auth()->user()->hasPermission('read_catogery'))--}}
            {{--                <li class="treeview">--}}
            {{--                    <a href="#">--}}
            {{--                        <i class="fa fa-globe"></i>--}}
            {{--                        <span>@lang('site.geography')</span>--}}
            {{--                        <span class="pull-right-container">--}}
            {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
            {{--                    </span>--}}
            {{--                    </a>--}}
            {{--                                        <ul class="treeview-menu" style="display:none">--}}
            {{--                                            <li><a href="{{ route('dashboard.catogery.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.countries')</span></a></li>--}}
            {{--                                        </ul>--}}
            {{--                </li>--}}
            {{--            @endif--}}
            {{--      @if (auth()->user()->hasRole('super_admin'))

                                 <li><a href="{{ route('dashboard.contact') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.contact')

                         <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                            @php  $cout=\Modules\Contact\Entities\Contact::where('read_at',0)->count()

                          @endphp
                                             {{$cout}}

                     </span>

         </span></a></li>

                 @endif --}}


            {{--         @if (auth()->user()->hasRole('super_admin'))

                        <li><a href="{{ route('dashboard.setting') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.setting')

                            <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                        </span>

            </span></a></li>

                    @endif --}}


            @if (auth()->user()->hasPermission('read_categories'))

                <li><a href="{{ route('dashboard.catogeries.index') }}"><i class="fa fa-th fa fa-1.5x"></i><span>@lang('site.categories')

                    <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                </span>

    </span></a></li>

            @endif


            @if (auth()->user()->hasPermission('read_contactusmassages'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-medkit fa fa-1.5x"></i>
                        <span>@lang('site.contact')</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>


                    <ul class="treeview-menu" style="display:none">
                        <li><a href="{{ route('dashboard.catogeryjobs.index') }}"><i
                                    class=" fa fa-th fa fa-1.5x"></i><span>@lang('site.complain')</span></a></li>
                        <li><a href="{{ route('dashboard.contacts.index') }}"><i class="fa fa-envelope"></i><span>@lang('site.contact')


                                </span></a>
                    </ul>
                </li>


            @endif


            {{--            @if (auth()->user()->hasPermission('read_cases'))--}}
            {{--                <li class="treeview">--}}
            {{--                    <a href="#">--}}
            {{--                        <i class=" fa fa-medkit fa fa-1.5x"></i>--}}
            {{--                        <span>@lang('site.cases')</span>--}}
            {{--                        <span class="pull-right-container">--}}
            {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
            {{--                    </span>--}}
            {{--                    </a>--}}
            {{--                    <ul class="treeview-menu">--}}
            {{--                        <li><a href="{{ route('dashboard.cases.index') }}"><i class="fa fa-th"></i><span>@lang('site.cases')</span></a></li>--}}

            {{--                        @if (auth()->user()->hasPermission('read_typecases'))--}}

            {{--                        <li><a href="{{ route('dashboard.typecases.index') }}"><i class="fa fa-newspaper-o"></i><span>@lang('site.typecases')</span></a></li>--}}
            {{--                        @endif--}}

            {{--                        @if (auth()->user()->hasPermission('read_contactusmassages'))--}}

            {{--                            <li><a href="{{ route('dashboard.inquiress.index') }}"><i class=" fa fa-medkit fa fa-1.5x"></i><span>@lang('site.orders')--}}

            {{--                                <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">--}}

            {{--                            </span>--}}

            {{--                </span></a></li>--}}

            {{--                        @endif--}}

            {{--                    </ul>--}}
            {{--                </li>--}}
            {{--            @endif--}}
            {{--            @if (auth()->user()->hasPermission('read_categories'))--}}
            {{--                <li class="treeview">--}}
            {{--                    <a href="#">--}}
            {{--                        <i class="fa fa-globe"></i>--}}
            {{--                        <span>@lang('site.rulrcategories')</span>--}}
            {{--                        <span class="pull-right-container">--}}
            {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
            {{--                    </span>--}}
            {{--                    </a>--}}
            {{--                    <ul class="treeview-menu" style="display:none">--}}
            {{--                        <li><a href="{{ route('dashboard.catogerieslawer.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.categories')</span></a></li>--}}
            {{--                        <li><a href="{{ route('dashboard.subcatogerieslawer4.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.articles')</span></a></li>--}}

            {{--                    </ul>--}}
            {{--                </li>--}}
            {{--            @endif--}}
            {{--            @if (auth()->user()->hasPermission('read_categories'))--}}
            {{--                <li class="treeview">--}}
            {{--                    <a href="#">--}}
            {{--                        <i class="fa fa-globe"></i>--}}
            {{--                        <span>@lang('site.subcategory')</span>--}}
            {{--                        <span class="pull-right-container">--}}
            {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
            {{--                    </span>--}}
            {{--                    </a>--}}

            {{--                    <ul class="treeview-menu" style="display:none">--}}
            {{--                        <li><a href="{{ route('dashboard.subcatogerieslawer.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.categories')</span></a></li>--}}
            {{--                        <li><a href="{{ route('dashboard.subcatogerieslawer2.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.differentiation principles')</span></a></li>--}}
            {{--                        <li><a href="{{ route('dashboard.subcatogerieslawer3.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.commercial')</span></a></li>--}}
            {{--                        <li><a href="{{ route('dashboard.subcatogerieslawer4.index') }}"><i class="fa fa-flag-o"></i><span>@lang('site.generaltrade')</span></a></li>--}}

            {{--                    </ul>--}}

            {{--                </li>--}}
            {{--            @endif--}}


            <li class="treeview">--}}
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>@lang('site.products')</span>
                    <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                </a>

                <ul class="treeview-menu" style="display:none">


                    <li><a href="{{ route('dashboard.products.index') }}"><i class="fa fa-globe"></i><span>@lang('site.products')

                                <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                            </span>

                </span></a></li>

                    <li><a href="{{ route('dashboard.categories.index') }}"><i
                                class="fa fa-medkit fa fa-1.5x"></i><span>@lang('site.categories')

                            <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                        </span>

            </span></a></li>

                </ul>
            </li>



            @if (auth()->user()->hasPermission('read_categories'))

                <li><a href="{{ route('dashboard.orders.index') }}"><i class="fa fa-th fa fa-1.5x"></i><span>@lang('site.orders')

                    <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                </span>

    </span></a></li>

            @endif


            @if (auth()->user()->hasPermission('read_settings'))

                <li><a href="{{ route('dashboard.settings.index') }}"><i class="fas fa-cog"></i><span>@lang('site.settings')

                            <span class="kt-badge kt-badge--rounded kt-badge--brand btn-danger fa fa-2x">

                        </span>

            </span></a></li>

            @endif


            @if (auth()->user()->hasPermission('read_consultations'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-newspaper-o"></i>
                        <span>@lang('site.reports')</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu" style="display:none">

                        <li><a href="{{ route('dashboard.reportusers') }}"><i
                                    class="fa fa-building-o"></i><span>@lang('site.users')</span></a></li>
                        <li><a href="{{ route('dashboard.reportvisitor') }}"><i
                                    class="fa fa-building-o"></i><span>@lang('site.reportvisitor')</span></a></li>

                        <li><a href="{{ route('dashboard.reportseller') }}"><i
                                    class="fa fa-building-o"></i><span>@lang('site.sellers')</span></a></li>
                        <li><a href="{{ route('dashboard.reportorders') }}"><i
                                    class="fa fa-building-o"></i><span>@lang('site.orders')</span></a></li>


                    </ul>
                </li>

            @endif


        </ul>

    </section>

</aside>

