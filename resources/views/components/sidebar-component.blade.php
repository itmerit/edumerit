@php
    $school_config = schoolConfig();
    $isSchoolAdmin = Session::get('isSchoolAdmin');
@endphp
<!-- sidebar part here -->
<nav id="sidebar" class="sidebar">

    <div class="sidebar-header update_sidebar">
        @if (Auth::user()->role_id != 2 && Auth::user()->role_id != 3)
            @if (userPermission('dashboard'))
                @if (moduleStatusCheck('Saas') == true &&
                        Auth::user()->is_administrator == 'yes' &&
                        Session::get('isSchoolAdmin') == false &&
                        Auth::user()->role_id == 1)
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
            <img src="{{ asset('public/uploads/settings/logo.png') }}" alt="logo">
        @endif
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>

    </div>
    @if (Auth::user()->is_saas == 0)

        <ul class="sidebar_menu list-unstyled" id="sidebar_menu">
            @if (moduleStatusCheck('Saas') == true &&
                    Auth::user()->is_administrator == 'yes' &&
                    Session::get('isSchoolAdmin') == false &&
                    Auth::user()->role_id == 1)
                @include('saas::menu.Saas')
            @else
                @if (auth()->user()->role_id != 3)
                    @isset($sidebar_menus)
                        @foreach ($sidebar_menus as $sidebar_menu)
                            @if($sidebar_menu->subModule->count() > 0 && sidebarPermission($sidebar_menu->permissionInfo)==true)
                                @if ($sidebar_menu->permissionInfo->name)
                                    <span class="menu_seperator" id="seperator_{{ $sidebar_menu->permissionInfo->route }}" data-section="{{ $sidebar_menu->permissionInfo->route }}">{{ $sidebar_menu->permissionInfo->name }} </span>
                                @endif
                            
                                @foreach ($sidebar_menu->subModule as $item)

                                    @if(sidebarPermission($item->permissionInfo)==true)
                                        <li class="{{ spn_active_link(subModuleRoute($item), 'mm-active') }} {{ $sidebar_menu->permissionInfo->route }}">

                                            @if ($item->subModule->count() > 0 && $item->permissionInfo->route != 'dashboard')
                                                <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                                @else
                                                    <a href="{{ validRouteUrl($item->permissionInfo->route) }}">
                                            @endif
                                            <div class="nav_icon_small">
                                                <span class="{{ $item->permissionInfo->icon }}"></span>
                                            </div>
                                            <div class="nav_title">
                                                <span>{{ __($item->permissionInfo->lang_name) }} 
                                                </span>
                                                @if (config('app.app_sync') && $item->permissionInfo->module && in_array($item->permissionInfo->module, $paid_modules))
                                                    <span class="demo_addons">Addon</span>
                                                @endif
                                            </div>
                                            </a>
                                            <ul class="mm-collapse">
                                                @if (@$item->subModule)
                                                    @foreach (@$item->subModule as $key => $sub)
                                                        @if(sidebarPermission($sub->permissionInfo)==true)
                                                        <li>
                                                            @if (count($sub->subModule) > 0)
                                                                <a href="javascript:void(0)" class="has-arrow "
                                                                    aria-expanded="false">
                                                                @else
                                                                    <a href="{{ validRouteUrl($sub->permissionInfo->route) }}"
                                                                        class="{{ spn_active_link(subModuleRoute($sub), 'active') }}">
                                                            @endif
                                                            {{ __($sub->permissionInfo->lang_name) }} </a>
                                                            @if ($sub->subModule)
                                                                <ul class="list-unstyled" id="{{ $key }}">
                                                                    @foreach ($sub->subModule as $child)
                                                                        @if(sidebarPermission($child)==true)
                                                                            <li>
                                                                                <a
                                                                                    href="{{ validRouteUrl($child->permissionInfo->route) }}">
                                                                                    {{ __($child->permissionInfo->lang_name) }} 
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endisset
                @endif
                @if (auth()->user()->role_id == 3)
                    @isset($sidebar_menus)                                    
                        @foreach ($sidebar_menus as $sidebar_menu)
                            @if(sidebarPermission($sidebar_menu->permissionInfo)==true)
                                    @if($sidebar_menu->permissionInfo->lang_name)
                                    <span class="menu_seperator">{{ __($sidebar_menu->permissionInfo->lang_name) }}</span>
                                    @endif
                                    @foreach ($sidebar_menu->subModule as $item)
                                        @if(sidebarPermission($item->permissionInfo)==true)
                                            <li class="{{ spn_active_link(subModuleRoute($item), 'mm-active') }}">
                                                
                                                @if (
                                                    ($item->subModule->count() > 0 && $item->permissionInfo->route != 'dashboard') ||
                                                        $item->permissionInfo->relate_to_child == 1)
                                                    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
                                                    @else
                                                        <a href="{{ validRouteUrl($item->permissionInfo->route) }}">
                                                @endif
                                                <div class="nav_icon_small">
                                                    <span class="{{ $item->permissionInfo->icon }}"></span>
                                                </div>
                                                <div class="nav_title">
                                                        <span>{{ __($item->permissionInfo->lang_name) }}</span>
                                                        @if (config('app.app_sync') && $item->permissionInfo->module && in_array($item->permissionInfo->module, $paid_modules))
                                                        @if (config('app.app_sync'))
                                                            <span class="demo_addons">Addon</span>
                                                        @endif
                                                    @endif
                                                </div>
                                                </a>
                                                <ul class="mm-collapse">
                                                    @if (@$item->subModule)
                                                        @foreach (@$item->subModule as $key => $sub)
                                                            @if ($sub->permissionInfo->relate_to_child == 1 && $item->permissionInfo->is_parent == 1 && sidebarPermission($sub->permissionInfo))
                                                                @foreach ($childrens as $children)
                                                      
                                                                @if(! in_array($item->permissionInfo->module , ["fees_collection", "Fees"]) && (hasDueFees($children->id) )) @continue  @endif 
                                                                   
                                                                        <li>
                                                                            <a href="{{ validRouteUrl($sub->permissionInfo->route, $children->id) }}"
                                                                                class="{{ spn_active_link(subModuleRoute($sub), 'active') }}">

                                                                                {{ __($sub->permissionInfo->lang_name) }} - {{ $children->full_name }}
                                                                            </a>
                                                                        </li>
                                                                            
                                                                       
                                                                        
                                                                    
                                                                @endforeach
                                                            @else
                                                            @if(sidebarPermission($sub->permissionInfo))
                                                                <li>
                                                                    <a href="{{ validRouteUrl($sub->permissionInfo->route) }}"
                                                                        class="{{ spn_active_link(subModuleRoute($sub), 'active') }}">

                                                                        {{ __($sub->permissionInfo->lang_name) }} 
                                                                    </a>

                                                                </li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    @if (
                                                        $item->permissionInfo->relate_to_child == 1 &&
                                                            $item->permissionInfo->is_parent == 1 &&
                                                            count($item->subModule) == 0 && sidebarPermission($item->permissionInfo))
                                                        @foreach ($childrens as $children)
                                                       
                                                            @if(! in_array($item->permissionInfo->module , ["fees_collection", "Fees"]) && (hasDueFees($children->id) )) @continue  @endif 
                                                                
                                                                    <li>
                                                                        <a href="{{ validRouteUrl($item->permissionInfo->route, $children->id) }}"
                                                                            class="{{ spn_active_link(subModuleRoute($item), 'active') }}">

                                                                            {{ __($item->permissionInfo->lang_name) }} -
                                                                            {{ $children->full_name }}</a>

                                                                    </li>
                                                        
                                                               
                                                           
                                                             

                                                            
                                                        @endforeach
                                                    @endif

                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                            @endif
                        @endforeach
                    @endisset
                @endif
            @endif
        </ul>
    @endif
</nav>
<!-- sidebar part end -->
@push('script')
    <script>
        $(document).ready(function(){
            var sections=[];
            $('.menu_seperator').each(function() { sections.push($(this).data('section')); });
          
            jQuery.each(sections, function(index, section) {             
                if($('.'+section).length == 0) {
                    $('#seperator_'+section).addClass('d-none');
                }else{
                    $('#seperator_'+section).removeClass('d-none');
                }
            });           
        })

    </script>
@endpush