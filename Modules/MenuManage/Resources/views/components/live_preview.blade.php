<h4>{{ __('menumanage::menuManage.Live Preview') }}</h4>
<div class="mt_30">

    <nav class="preview_menu_wrapper">
        <ul id="previewMenu">

            @if (isset($sidebar_menus))
                @foreach($sidebar_menus as $preview_section)
                    @if($preview_section->subModule->count() > 0)
                        <li class="preview_section">
                            {{__(@$preview_section->permissionInfo->lang_name)}}
                        </li>
                        @foreach (@$preview_section->subModule as $key => $item)
                            @if(sidebarPermission($item->permissionInfo)==true)
                                <li class="">
                                    <a href="#" class="@if ($item->subModule->count()) has-arrow @endif">
                                        <div class="nav_icon_small">
                                            <span class="{{ $item->permissionInfo->icon ?? 'fas fa-th' }}"></span>
                                        </div>
                                        <div class="nav_title">
                                            <span>{{$item->permissionInfo ? __($item->permissionInfo->lang_name) : 'no' }}</span>
                                        </div>
                                    </a>
                                    @if ($item->subModule->count())
                                        <ul>
                                            @foreach ($item->subModule as $submenu)
                                                @if(sidebarPermission($submenu->permissionInfo)==true)
                                                    <li>
                                                        <a href="#">
                                                            {{ $submenu->permissionInfo ? __($submenu->permissionInfo->lang_name) :'no sub' }}
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
                @endforeach
            @endif
        </ul>
    </nav>

</div>
