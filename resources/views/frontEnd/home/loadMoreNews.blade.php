@foreach($due_news as $value)
<div class="col-lg-3 col-md-6 news_count">
    <div class="news-item">
        <div class="news-img">
            <img class="img-fluid w-100 news-image" src="{{asset($value->image)}}" alt="{{$value->news_title}}">
        </div>
        <div class="news-text">
            <p class="date">                                                                            
                {{$value->publish_date != ""? dateConvert($value->publish_date):''}}
            </p>
            <h4>
                <a href="{{url('news-details/'.$value->id)}}">
                    {{$value->news_title}}
                </a>
            </h4>
        </div>
    </div>
</div>
@endforeach
<input type="hidden" value="{{$skip+count($due_news)}}" class="hide-button">
<input type="hidden" value="{{$count}}" class="count-news">