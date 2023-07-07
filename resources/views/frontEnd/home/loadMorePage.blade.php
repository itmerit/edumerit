@foreach($due_courses as $value)
    <div class="col-lg-4 col-md-6 all_courses">
        <div class="academic-item">
            <div class="academic-img" 
                style="background:  
                url({{$value->image != ""? asset($value->image) : '../img/client/common-banner1.jpg'}}) 
                            no-repeat top;">
                </div>
            <div class="academic-text">
                <h4>
                    <a href="{{url('course-Details/'.$value->id)}}">{{$value->title}}</a>
                </h4>
            </div>
        </div>
    </div>
@endforeach
<input type="hidden" value="{{$skip+count($due_courses)}}" class="hide-button">
<input type="hidden" value="{{$count}}" class="count-course">