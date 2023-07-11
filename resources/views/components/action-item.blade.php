@props(['permission'=>null, 'action'])
@if(!$permission || userPermission($permission))
<a {{ $attributes->merge(['class'=>'dropdown-item']) }}>{{ $action }}</a>
@endif