<div role="tabpanel" class="tab-pane fade {{$type == 'studentTimeline' ? 'show active':''}}" id="studentTimeline">
    <div class="white-box">
        <div class="text-right mb-20">
            <button type="button" data-toggle="modal" data-target="#add_timeline_madal" class="primary-btn tr-bg text-uppercase bord-rad">
                @lang('common.add')
                <span class="pl ti-plus"></span>
            </button>
        </div>
        @foreach($timelines as $timeline)
            <div class="student-activities">
                <div class="single-activity">
                    <h4 class="title text-uppercase">{{$timeline->date != ""? dateConvert($timeline->date):''}}</h4>
                    <div class="sub-activity-box d-flex">
                        <h6 class="time text-uppercase"></h6>
                        <div class="sub-activity">
                            <h5 class="subtitle text-uppercase"> {{$timeline->title}}</h5>
                            <p>
                                {{$timeline->description}}
                            </p>
                        </div>

                        <div class="close-activity">

                            <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                               data-target="#deleteTimelineModal{{$timeline->id}}" href="#">
                                <span class="ti-trash text-white"></span>
                            </a>

                            @if (file_exists($timeline->file))
                                <a href="{{url($timeline->file)}}"
                                   class="primary-btn tr-bg text-uppercase bord-rad" download>
                                    @lang('common.download')<span class="pl ti-download"></span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal fade admin-query" id="deleteTimelineModal{{$timeline->id}}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('common.delete')</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                </div>

                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                            data-dismiss="modal">@lang('common.cancel')
                                    </button>
                                    <a class="primary-btn fix-gr-bg"
                                       href="{{route('delete_timeline', [$timeline->id])}}">
                                        @lang('common.delete')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>