<div class="container-fluid">
     <div class="row">
         <div class="col-lg-12">
             {{ Form::open(['class' => 'form-horizontal','files' => true,'route' => 'studyMaterialAssigned','method' => 'POST']) }}
                 <table id="" class="display school-table school-table-style-parent-fees" cellspacing="0" width="100%">
                     <thead>
                         <tr>
                             <th>#</th>
                         <th> @lang('study.content_title')</th>
                         <th> @lang('common.type')</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($uploadContents as $value)
                         <tr>
                             <td>
                                 <input type="checkbox" id="study_mat.{{$value->id}}" class="common-checkbox study_material" name="study_material[]" value="{{$value->id}}">
                                 <label for="study_mat.{{$value->id}}"></label>
                             </td>
                             <td>{{@$value->content_title}}</td>
                             <td>
                                 @if(@$value->content_type == 'as')
                                     @lang('study.assignment')
                                 @elseif(@$value->content_type == 'st')
                                     @lang('study.study_material')
                                 @elseif(@$value->content_type == 'sy')
                                     @lang('study.syllabus')
                                 @else
                                     @lang('study.other_download')
                                 @endif
                             </td>
                         </tr>
                         @endforeach
                     </tbody>
                     <tfoot>
                         <button class="primary-btn fix-gr-bg submit" type="submit">Submit </button>
                     </tfoot>
                 </table>
             {{ Form::close() }}
         </div>
     </div>
 </div>
 