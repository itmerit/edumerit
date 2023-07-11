<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{@$course->title}}</h1>
            <img src="{{asset($course->image)}}" class="mt-3 mr-3" width="100%" style="float: left">
            <h3 class="mt-3">@lang('front_settings.overview'): </h3>
            <p>{{@$course->overview}}</p>
            <h3 class="mt-3">@lang('front_settings.outline'): </h3>
            <p>{{@$course->outline}}</p>
            <h3 class="mt-3">@lang('front_settings.prerequisites'): </h3>
            <p>{{@$course->prerequisites}}</p>
            <h3 class="mt-3">@lang('front_settings.resources'): </h3>
            <p>{{@$course->resources}}</p>
            <h3 class="mt-3">@lang('front_settings.stats'): </h3>
            <p>{{@$course->stats}}</p>
        </div>
    </div>

