<div role="tabpanel" class="tab-pane fade {{ Session::get('studentDocuments') == 'active' ? 'show active' : '' }}"
    id="studentDocuments">
    <div class="white-box">
        <div class="text-right mb-20">
            <button type="button" data-toggle="modal" data-target="#add_document_madal"
                class="primary-btn tr-bg text-uppercase bord-rad">
                @lang('student.upload_document')
                <span class="pl ti-upload"></span>
            </button>
        </div>
        <table id="" class="table simple-table table-responsive school-table"
            cellspacing="0">
            <thead class="d-block">
                <tr class="d-flex">
                    <th class="col-2">@lang('student.title')</th>
                    <th class="col-6">@lang('student.name')</th>
                    <th class="col-4">@lang('student.action')</th>
                </tr>
            </thead>

            <tbody class="d-block">
                @if (is_show('document_file_1'))
                    @if ($student_detail->document_file_1 != '')
                        <tr class="d-flex">
                            <td class="col-2">{{ $student_detail->document_title_1 }}</td>
                            <td class="col-6">{{ showDocument(@$student_detail->document_file_1) }}</td>
                            <td class="col-4">
                                @if (file_exists($student_detail->document_file_1))
                                    <a class="primary-btn tr-bg text-uppercase bord-rad"
                                        href="{{ url($student_detail->document_file_1) }}" download>
                                        @lang('common.download')<span class="pl ti-download"></span>
                                    </a>
                                    <a class="primary-btn icon-only fix-gr-bg"
                                        onclick="deleteDoc({{ $student_detail->id }},1)" data-id="1" href="#">
                                        <span class="ti-trash"></span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
                @if (is_show('document_file_2'))
                    @if ($student_detail->document_file_2 != '')
                        <tr class="d-flex">
                            <td class="col-2">{{ $student_detail->document_title_2 }}</td>
                            <td class="col-6">{{ showDocument(@$student_detail->document_file_2) }}</td>
                            <td class="col-4">
                                @if (file_exists($student_detail->document_file_2))
                                    <a class="primary-btn tr-bg text-uppercase bord-rad"
                                        href="{{ url($student_detail->document_file_2) }}" download>
                                        @lang('common.download')<span class="pl ti-download"></span>
                                    </a>
                                    <a class="primary-btn icon-only fix-gr-bg"
                                        onclick="deleteDoc({{ $student_detail->id }},2)" data-id="2" href="#">
                                        <span class="ti-trash"></span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
                @if (is_show('document_file_3'))
                    @if ($student_detail->document_file_3 != '')
                        <tr class="d-flex">
                            <td class="col-2">{{ $student_detail->document_title_3 }}</td>
                            <td class="col-6">{{ showDocument(@$student_detail->document_file_3) }}</td>
                            <td class="col-4">
                                @if (file_exists($student_detail->document_file_3))
                                    <a class="primary-btn tr-bg text-uppercase bord-rad"
                                        href="{{ url($student_detail->document_file_3) }}" download>
                                        @lang('common.download')<span class="pl ti-download"></span>
                                    </a>
                                    <a class="primary-btn icon-only fix-gr-bg"
                                        onclick="deleteDoc({{ $student_detail->id }},3)" data-id="3" href="#">
                                        <span class="ti-trash"></span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
                @if (is_show('document_file_4'))
                    @if ($student_detail->document_file_4 != '')
                        <tr class="d-flex">
                            <td class="col-2">{{ $student_detail->document_title_4 }}</td>
                            <td class="col-6">{{ showDocument(@$student_detail->document_file_4) }}</td>
                            <td class="col-4">
                                @if (file_exists($student_detail->document_file_4))
                                    <a class="primary-btn tr-bg text-uppercase bord-rad"
                                        href="{{ url($student_detail->document_file_4) }}" download>
                                        @lang('common.download')<span class="pl ti-download"></span>
                                    </a>

                                    <a class="primary-btn icon-only fix-gr-bg"
                                        onclick="deleteDoc({{ $student_detail->id }},4)" data-id="4" href="#">
                                        <span class="ti-trash"></span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif

                <div class="modal fade admin-query" id="delete-doc">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('common.delete')</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div class="text-center">
                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                </div>

                                <div class="mt-40 d-flex justify-content-between">
                                    <form action="{{ route('student_document_delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="student_id">
                                        <input type="hidden" name="doc_id">
                                        <button type="button" class="primary-btn tr-bg"
                                            data-dismiss="modal">@lang('common.cancel')</button>
                                        <button type="submit" class="primary-btn fix-gr-bg">@lang('common.delete')</button>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                @foreach ($student_detail->studentDocument as $document)
                    <tr class="d-flex">
                        <td class="col-2">{{ $document->title }}</td>
                        <td class="col-6">{{ showDocument($document->file) }}</td>
                        <td class="col-4">
                            @if (file_exists($document->file))
                                <a class="primary-btn tr-bg text-uppercase bord-rad"
                                    href="{{ url($document->file) }}" download>
                                    @lang('common.download')<span class="pl ti-download"></span>
                                </a>
                            @endif
                            <a class="primary-btn icon-only fix-gr-bg" data-toggle="modal"
                                data-target="#deleteDocumentModal{{ $document->id }}" href="#">
                                <span class="ti-trash"></span>
                            </a>

                        </td>
                    </tr>
                    <div class="modal fade admin-query" id="deleteDocumentModal{{ $document->id }}">
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
                                            href="{{ route('delete-student-document', [$document->id]) }}">
                                            @lang('common.delete')</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade admin-query" id="add_document_madal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> @lang('student.upload_document')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        {{ Form::open([
                            'class' => 'form-horizontal',
                            'files' => true,
                            'route' => 'upload_document',
                            'method' => 'POST',
                            'enctype' => 'multipart/form-data',
                            'name' => 'document_upload',
                        ]) }}
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="student_id"
                                    value="{{ $student_detail->id }}">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label> @lang('common.title')<span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{" type="text"
                                                name="title" value="" id="title">
                                            


                                            <span class=" text-danger" role="alert"
                                                id="amount_error">

                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field" type="text"
                                                id="placeholderPhoto" placeholder="Document"
                                                disabled>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button style="position: relative; top: 8px; right: 12px;" class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="photo">
                                                @lang('common.browse')</label>
                                            <input type="file" class="d-none" name="photo"
                                                id="photo">
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <!-- <div class="col-lg-12 text-center mt-40">
                                <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                    <span class="ti-check"></span>
                                    save information
                                </button>
                            </div> -->
                            <div class="col-lg-12 text-center mt-40">
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('common.cancel')
                                    </button>

                                    <button class="primary-btn fix-gr-bg submit" type="submit">@lang('student.save')
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
