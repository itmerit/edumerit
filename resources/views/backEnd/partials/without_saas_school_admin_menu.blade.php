@if (userPermission(399) && menuStatus(399))
    <li data-position="{{ menuPosition(399) }}">
        <a href="{{ route('manage-adons') }}">@lang('system_settings.module_manager')</a>
    </li>
@endif

{{-- @if (userPermission(401) && menuStatus(401))
    <li data-position="{{ menuPosition(401) }}">
        <a href="{{ route('manage-currency') }}">@lang('system_settings.manage_currency')</a>
    </li>
@endif --}}

{{-- @if (userPermission(428) && menuStatus(428))

    <li data-position="{{ menuPosition(428) }}">
        <a href="{{ route('base_setup') }}">@lang('system_settings.base_setup')</a>
    </li>
@endif --}}

@if (userPermission(549) && menuStatus(549))

    <li data-position="{{ menuPosition(549) }}">
        <a href="{{ route('language-list') }}">@lang('system_settings.language')</a>
    </li>
@endif

@if (userPermission(456) && menuStatus(465))

    <li data-position="{{ menuPosition(465) }}">
        <a href="{{ route('backup-settings') }}">@lang('system_settings.backup_settings')</a>
    </li>
@endif




@if (userPermission(478) && menuStatus(478))

    <li data-position="{{ menuPosition(478) }}">
        <a href="{{ route('update-system') }}"> @lang('system_settings.about_&_update')</a>
    </li>
@endif

@if (userPermission(4000) && menuStatus(4000))

    <li data-position="{{ menuPosition(4000) }}">
        <a href="{{ route('utility') }}">@lang('system_settings.utilities')</a>
    </li>
@endif

@if (userPermission(482) && menuStatus(482))
    <li data-position="{{ menuPosition(482) }}">
        <a href="{{ route('api/permission') }}">@lang('system_settings.api_permission') </a>
    </li>
@endif


