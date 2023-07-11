@extends('backEnd.master')
@section('title') 
@lang('communicate.notice_board')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.notice_board')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.notice_board')</a>
            </div>
        </div>
    </div>
</section>

<section class="mb-40 sms-accordion">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-5">
                <div class="main-title">
                    <h3 class="mb-30">@lang('communicate.all_notices')</h3>
                </div>
            </div>
             @if(userPermission("add-notice"))
            <div class="offset-lg-5 col-lg-3 text-right col-md-6 col-7">
                <a href="{{route('add-notice')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('communicate.add_notice')
                </a>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="accordion">
                   @php $i = 0; @endphp
                   @if(isset($allNotices))
                   @foreach($allNotices as $value)
                   <div class="card">
                     <a class="card-link" data-toggle="collapse" href="#notice{{@$value->id}}">
                        <div class="card-header d-flex justify-content-between">
                            {{@$value->notice_title}}
                            <div>
                            @if(userPermission('edit-notice'))
                             <a href="{{route('edit-notice',$value->id)}}">
                                <button type="submit" class="primary-btn small tr-bg mr-10">@lang('common.edit') </button>
                             </a>
                             @endif
                              @if(userPermission('delete-notice-view'))
                                <a class="deleteUrl" data-modal-size="modal-md" title="Delete Notice" href="{{route('delete-notice-view',$value->id)}}"><button class="primary-btn small tr-bg">@lang('common.delete') </button></a>
                            @endif
                            </div>
                        </div>
                    </a>
                    @php $i++; @endphp
                    <div id="notice{{@$value->id}}" class="collapse {{$i ==  1 ? 'show' : ''}}" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    {!! $value->notice_message !!}
                                </div>
                                <div class="col-lg-4">
                                    <p class="mb-0">
                                        <span class="ti-calendar mr-10"></span>
                                        @lang('communicate.publish_date') :    
                                         {{@$value->publish_on != ""? dateConvert(@$value->publish_on):''}}
                                    </p>
                                    <p class="mb-0">
                                        <span class="ti-calendar mr-10"></span>
                                        @lang('communicate.notice_date') :                                         
                                        {{@$value->notice_date != ""? dateConvert(@$value->notice_date):''}}
                                    </p>
                                    <p>
                                        <span class="ti-user mr-10"></span>
                                        @lang('communicate.created_by') : {{ @$value->users !=""?@$value->users->full_name:""}}
                                    </p>

                                    <?php 
                                    $rolesData = explode(',', $value->inform_to);
                                    $RoleName = \Modules\RolePermission\Entities\InfixRole::select('name')->whereIn('id', $rolesData)->get();
                                    ?>
                                    @foreach ($RoleName as $name)
                                    <p class="mb-0">
                                    <span class="ti-user mr-10"></span>{{ @$name->name}}</p>
                                    @endforeach



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</section>
@endsection
