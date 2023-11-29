@if (isset($custom_field_values))
    <h4 class="stu-sub-head mt-40">@lang('student.custom_field')</h4>
    @foreach ($custom_field_values as $key=>$custom_field_info)
        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="">
                        {{$key}}
                    </div>
                </div>
                <div class="col-lg-7 col-md-6">
                    @if(is_array($custom_field_info))
                        {{implode(', ', $custom_field_info)}}
                    @else
                        @php
                            $file = pathinfo($custom_field_info, PATHINFO_EXTENSION);
                        @endphp
                        @if($file && file_exists($custom_field_info))
                        <div class="d-flex align-items-center">
                            <span>
                                @php
                                    $name = explode('/', $custom_field_info);
                                    $number = array_key_last($name);
                                    echo explode('.',$name[$number])[0];
                                @endphp
                            </span>
                            <a href="{{url($custom_field_info)}}" download>
                                <span class="ti-download ml-3 d-inline-block"></span>
                            </a>
                        </div>
                        @else
                            {{$custom_field_info}}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif