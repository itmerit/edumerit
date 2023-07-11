<h4>{{ __('menumanage::menuManage.Available menu items') }}</h4>
<div class="">
    <div class="row">
        <div class="col-xl-12">
            <!-- menu_setup_wrap  -->
            <div class="dd available_list  menu_item_div menu-list" data-section="1">
                <div class="  available-items-container unused_menu" data-id="remove" data-section_id="remove"
                    id="available_list">
                    @php
                        $hasIds = [];
                    @endphp
                    @isset($unused_menus)
                        @if ($unused_menus->count())

                            @foreach ($unused_menus->where('type', 1) as $menu)
                                @php
                                    $hasIds[] = $menu->id;
                                @endphp
                                @if (!$menu->module || moduleStatusCheck($menu->module))
                                    @php
                                        
                                        $submenus = $unused_menus
                                            ->where('type', 2)
                                            ->where('parent_route', $menu->route)
                                            ->where('parent_route', '!=', 'dashboard');
                                    @endphp
                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="{{ $menu->id }}"
                                            data-section_id="{{ $menu->section_id }}"
                                            data-parent_route="{{ $menu->parent_route }}">
                                            <div class="card accordion_card" id="accordion_{{ $menu->id }}">
                                                <div class="card-header item_header" id="heading_{{ $menu->id }}">
                                                    <div class="dd-handle">
                                                        <div class="float-left">
                                                            {{ $menu->name }}
                                                        </div>
                                                    </div>
                                                    <div class="float-right btn_div">
                                                        <div class="edit_icon">
                                                            <span class="edit-btn">
                                                                <a class=" btn-modal" data-container="#commonModal"
                                                                    type="button"
                                                                    href="{{ route('sidebar-manager.menu-edit-form', $menu->id) }}">
                                                                    <i class="ti-pencil-alt p-2"></i>
                                                                </a>

                                                            </span>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <ol class="dd-list">
                                                @if ($menu->route != 'dashboard')
                                                    @foreach ($submenus->where('type', 2) as $submenu)
                                                        @php
                                                            $hasIds[] = $submenu->id;
                                                        @endphp
                                                        @if (!$submenu->module || moduleStatusCheck($submenu->module))
                                                            @php
                                                                $other_ids[] = $submenu->id;
                                                            @endphp
                                                            <li class="dd-item" data-id="{{ $submenu->id }}">
                                                                <div class="card accordion_card"
                                                                    id="accordion_{{ $submenu->id }}">
                                                                    <div class="card-header item_header"
                                                                        id="heading_{{ $submenu->id }}">
                                                                        <div class="dd-handle">
                                                                            <div class="float-left">
                                                                                {{ $submenu->name }}

                                                                            </div>
                                                                        </div>
                                                                        <div class="float-right btn_div">
                                                                            <div class="float-right btn_div">
                                                                                <div class="edit_icon">
                                                                                    <span class="edit-btn">
                                                                                        <a class=" btn-modal"
                                                                                            data-container="#commonModal"
                                                                                            type="button"
                                                                                            href="{{ route('sidebar-manager.menu-edit-form', $submenu->id) }}">
                                                                                            <i
                                                                                                class="ti-pencil-alt p-2"></i>
                                                                                        </a>

                                                                                    </span>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ol>
                                        </li>
                                    </ol>
                                @endif
                            @endforeach




                            @foreach ($unused_menus->whereNotIn('id', $hasIds) as $menu)
                                @if (!$menu->module || moduleStatusCheck($menu->module))
                                    @php
                                        
                                        $submenus = $unused_menus
                                            ->where('type', 2)
                                            ->where('parent_route', $menu->route)
                                            ->where('parent_route', '!=', 'dashboard');
                                    @endphp
                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="{{ $menu->id }}"
                                            data-section_id="{{ $menu->section_id }}"
                                            data-parent_route="{{ $menu->parent_route }}">
                                            <div class="card accordion_card" id="accordion_{{ $menu->id }}">
                                                <div class="card-header item_header" id="heading_{{ $menu->id }}">
                                                    <div class="dd-handle">
                                                        <div class="float-left">
                                                            {{ $menu->name }}
                                                        </div>
                                                    </div>
                                                    <div class="float-right btn_div">
                                                        <div class="edit_icon">
                                                            <span class="edit-btn">
                                                                <a class=" btn-modal" data-container="#commonModal"
                                                                    type="button"
                                                                    href="{{ route('sidebar-manager.menu-edit-form', $menu->id) }}">
                                                                    <i class="ti-pencil-alt p-2"></i>
                                                                </a>

                                                            </span>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </li>
                                    </ol>
                                @endif
                            @endforeach
                        @else
                            <ol class="dd-list">
                            </ol>

                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
