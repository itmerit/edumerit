<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h3>{{$news->news_title}}</h3>
            <h6 >@lang('student.category'): {{$news->category->category_name}}</h6>
            <p class="mt-3">{{$news->news_body}}</p>
        </div>
        <div class="col-md-3">
            <img src="{{asset($news->image)}}" width="185px" height="200px">
        </div>
    </div>
