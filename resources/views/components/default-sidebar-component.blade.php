@php
    $school_config = schoolConfig();
    $isSchoolAdmin = Session::get('isSchoolAdmin');
@endphp
        <!-- sidebar part here -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-header update_sidebar">
        @if (Auth::user()->role_id != 2 && Auth::user()->role_id != 3)
            @if (userPermission('dashboard'))
                @if (moduleStatusCheck('Saas') == true && Auth::user()->is_administrator == 'yes' && Session::get('isSchoolAdmin') == false && Auth::user()->role_id == 1)
                    <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                        @else
                            <a href="{{ route('admin-dashboard') }}" id="admin-dashboard">
                                @endif
                                @else
                                    <a href="{{ url('/') }}" id="admin-dashboard">
                                        @endif
                                        @else
                                            <a href="{{ url('/') }}" id="admin-dashboard">
                                                @endif
                                                @if (!is_null($school_config->logo))
                                                    <img src="{{ asset($school_config->logo) }}" alt="logo">
                                                @else
                                                    <img src="{{ asset('public/uploads/settings/logo.png') }}"
                                                         alt="logo">
                                                @endif
                                            </a>
                                            <a id="close_sidebar" class="d-lg-none">
                                                <i class="ti-close"></i>
                                            </a>

    </div>
    @if (Auth::user()->is_saas == 0)

        <ul class="sidebar_menu list-unstyled" id="sidebar_menu">
            <input type="hidden" name="" id="default_position" value="{{ menuPosition('is_submit') }}">
            @if (Auth::user()->role_id != 2 && Auth::user()->role_id != 3)
                @if (userPermission('dashboard'))
                    <li>
                        @if (moduleStatusCheck('Saas') == true && Auth::user()->is_administrator == 'yes' && Session::get('isSchoolAdmin') == false && Auth::user()->role_id == 1)
                            <a href="{{ route('superadmin-dashboard') }}" id="superadmin-dashboard">
                                @else
                                    <a href="{{ route('admin-dashboard') }}" id="admin-dashboard">
                                        @endif
                                        
                                        <div class="nav_icon_small">
                                            <span class="fas fa-th"></span>
                                        </div>
                                        <div class="nav_title">
                                            <span>@lang('common.dashboard')</span>
                                        </div>

                                    </a>
                    </li>
                @endif
            @endif
            @foreach ($sidebars as $item)
                <li  class="sortable_li">
                    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                        <div class="nav_icon_small">
                            <span class="flaticon-reading"></span>
                        </div>
                        <div class="nav_title">
                            <span>{{ @$item->permissionInfo->name }} - {{ @$item->permission_id }}</span>
                            @if (config('app.app_sync'))
                                <span class="demo_addons">Addon</span>
                            @endif
                        </div>
                    </a>
                    <ul class="list-unstyled">
                        @if(@$item->permissionInfo)
                            @foreach (@$item->permissionInfo->subModule->where('parent_route', '!=', 'dashboard') as $sub)
                                
                        
                        
                                <li data-position="">
                                    <a href="">{{@$sub->permissionInfo->name }} - {{ $sub->permission_id }}</a>
                                </li>
                        
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endforeach
        </ul>
    @endif
</nav>
<!-- sidebar part end -->