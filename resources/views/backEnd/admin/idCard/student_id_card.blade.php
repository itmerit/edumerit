@extends('backEnd.master')
@section('title')
    @if (isset($id_card))
        @lang('admin.edit_id_card')
    @else
        @lang('admin.create_id_card')
    @endif

@endsection
@section('mainContent')
    @push('css')
        <style>
            .user_id_card_header {
                padding: 10px;
                background: #c738d8;

            }

            .user_id_card_header h4 {
                font-size: 18px;
                font-weight: 500;
                text-align: center;
                margin-bottom: 0;
                color: #fff;
            }

            .cust-margin {
                margin-left: -140px !important;
            }

            .sticky_card {
                position: sticky;
                top: 0;
            }
            .input-right-icon button.primary-btn-small-input {
                top: 8px !important;
                right: 0px !important;
            }
        </style>
    @endpush
    <input type="hidden" value="{{ moduleStatusCheck('University') ? 'University' :'' }}" id="module">
    <input type="hidden" value="{{ isset($id_card) ? 'edit' :'' }}" id="editIDCard">
    <section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>
                    @if (isset($id_card))
                        @lang('admin.edit_id_card')
                    @else
                        @lang('admin.create_id_card')
                    @endif
                </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('admin.admin_section')</a>
                    <a href="#">@lang('admin.id_card')</a>
                    <a href="#">
                        @if (isset($id_card))
                            @lang('admin.edit_id_card')
                        @else
                            @lang('admin.create_id_card')
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($id_card))
                @if (userPermission('create-id-card'))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{ route('create-id-card') }}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($id_card))
                                        @lang('admin.edit_id_card')
                                    @else
                                        @lang('admin.add_id_card')
                                    @endif
                                  
                                </h3>
                            </div>
                            @if (isset($id_card))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['student-id-card-update', @$id_card->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{ $id_card->id }}">
                            @else
                                @if (userPermission('create-id-card'))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'store-id-card', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                @endif
                            @endif

                            @include('backEnd.admin.idCard.form_id_card')

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('admin.preview_id_card') </h3>
                            </div>
                        </div>
                    </div>
                    <div class="sticky_card">
                        <div class="user_id_card_header mt-30">
                            <h4 id="titleV">
                                @if (isset($id_card))
                                    {{ $id_card->title }}
                                @else
                                    @lang('admin.user_id_card')
                                @endif
                            </h4>
                        </div>
                        <div class="mt-10">
                            @if (isset($id_card))
                                @php
                                    $roleId = json_decode($id_card->role_id);
                                @endphp

                                    @include('backEnd.admin.idCard.edit_view_id_card')
                                    @include('backEnd.admin.idCard.guardian_edit_view')

                            @else
                                @include('backEnd.admin.idCard.add_view_id_card')
                                @include('backEnd.admin.idCard.guardian_add_view_id_card')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')

    <script>
        window.pageLayout = $('#pageLayoutStyle').val();
        window.userType = $('#applicableUser').val();

        //Update Generate Id card
        $(document).ready(function() {
               var moduleStatus = $('#module').val();
               var editIDCard = $('#editIDCard').val();
               if(editIDCard =='' && moduleStatus !=''){
                    $("#hFaculty").hide();             
                    $("#hAcademic").hide();
                    $("#hSemester").hide();
                
                    if (moduleStatus !='') {
                        $("#hFatherName").hide();
                        $("#hMotherName").hide();
                    }
                }
            if (userType == "0") {
                $('.text').html('@lang('admin.id')');
                $('.hStaffId').html('ID : 001');
                $('.vStaffId').html('ID : 001');
                $("#hClass").hide();

                $("#hSession").hide();
                $("#hFaculty").hide();
                $("#hDepartment").hide();
                $("#hAcademic").hide();
                $("#hSemester").hide();

                $("#vClass").hide();

                $("#vSession").hide();
                $("#vFaculty").hide();
                $("#vDepartment").hide();
                $("#vAcademic").hide();
                $("#vSemester").hide();

                $(".classHide").hide();
                $(".staffInfo").addClass('d-block');
                $('#gHorizontal').addClass('d-none');
                $('#gVertical').addClass('d-none');
                $(".admissionNo").removeClass('d-none');
                $(".classHide").removeClass('d-none');
                $(".fatherName").removeClass('d-none');
                $(".motherName").removeClass('d-none');
                $(".mobile").addClass('d-none');
                $(".dateOfBirth").removeClass('d-none');
                $(".bloodGroup").addClass('d-none');
                $("#hBloodGroup").addClass('d-none');
                $("#vBloodGroup").addClass('d-none');
            } else if (userType == "3") {
                $(".classHide").hide();
                $(".admissionNo").addClass('d-none');
                $(".fatherName").addClass('d-none');
                $(".motherName").addClass('d-none');
                $(".dateOfBirth").addClass('d-none');
                $(".bloodGroup").addClass('d-none');
                $("#hBloodGroup").addClass('d-none');
                $("#vBloodGroup").addClass('d-none');
            }


            $("body").on("change", "#applicableUser", function(e) {
                e.preventDefault();
                window.userType = $(this).val();
                let name_text = $(this).find('option:selected').text()+ ' Name';
                $('.role_name_text').text(name_text)
                if (userType == "0") {
                    $('.text').html('@lang('admin.id')');
                    $('.hStaffId').html('ID : 001');
                    $('.vStaffId').html('ID : 001');
                    $("#hClass").hide();
                    $("#vClass").hide();

                    $("#hSession").hide();
                    $("#hFaculty").hide();
                    $("#hDepartment").hide();
                    $("#hAcademic").hide();
                    $("#hSemester").hide();

                    $("#vSession").hide();
                    $("#vFaculty").hide();
                    $("#vDepartment").hide();
                    $("#vAcademic").hide();
                    $("#vSemester").hide();

                    $(".classHide").hide();
                    $(".staffInfo").addClass('d-block');
                    $('#gHorizontal').addClass('d-none');
                    $('#gVertical').addClass('d-none');
                    $(".admissionNo").removeClass('d-none');
                    $(".classHide").removeClass('d-none');
                    $(".fatherName").removeClass('d-none');
                    $(".motherName").removeClass('d-none');
                    $(".mobile").addClass('d-none');
                    $(".dateOfBirth").removeClass('d-none');
                    $(".bloodGroup").addClass('d-none');
                    $("#hBloodGroup").addClass('d-none');
                    $("#vBloodGroup").addClass('d-none');
                    if (pageLayout == "horizontal") {
                        $('#horizontal').removeClass('d-none');
                        // $('#vertical').addClass('d-none');
                        $('#vertical').removeAttr("class");
                        $('#vertical').addClass('d-none');

                        $('#gHorizontal').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 57 mm)');
                        $('#pHeight').html('(@lang('admin.default') 89 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    } else {
                        $('#horizontal').addClass('d-none');
                        $('#vertical').removeClass('d-none');
                        $('#gVertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 89 mm)');
                        $('#pHeight').html('(@lang('admin.default') 57 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 13 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 13 mm)');
                    }
                } else if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        $('#gHorizontal').removeClass('d-none');
                        $('#horizontal').addClass('d-none');
                        $('#vertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 60 mm)');
                        $('#pHeight').html('(@lang('admin.default') 106 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    } else {
                        $('#horizontal').addClass('d-none');
                        $('#vertical').addClass('d-none');
                        $('#gVertical').removeClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 106 mm)');
                        $('#pHeight').html('(@lang('admin.default') 55 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    }
                    $(".staffInfo").removeClass('d-block');
                    $(".admissionNo").addClass('d-none');
                    $(".classHide").addClass('d-none');
                    $(".fatherName").addClass('d-none');
                    $(".motherName").addClass('d-none');
                    $(".mobile").removeClass('d-none');
                    $(".dateOfBirth").addClass('d-none');
                    $(".bloodGroup").addClass('d-none');
                } else {
                    $('.text').html('@lang('admin.admission_no')');
                    $('.hStaffId').html('Admission No : 001');
                    $('.vStaffId').html('Admission No : 001');

                    $("#hClass").show();
                    $("#vClass").show();

                    $("#hSession").show();
                    $("#hFaculty").show();
                    $("#hDepartment").show();
                    $("#hAcademic").show();
                    $("#hSemester").show();
                                        
                    $("#vSession").show();
                    $("#vFaculty").show();
                    $("#vDepartment").show();
                    $("#vAcademic").show();
                    $("#vSemester").show();

                    $(".classHide").show();
                    $(".staffInfo").removeClass('d-block');
                    $('#gHorizontal').addClass('d-none');
                    $('#gVertical').addClass('d-none');
                    $(".admissionNo").removeClass('d-none');
                    $(".classHide").removeClass('d-none');
                    $(".fatherName").removeClass('d-none');
                    $(".motherName").removeClass('d-none');
                    $(".mobile").addClass('d-none');
                    $(".dateOfBirth").removeClass('d-none');
                    $(".bloodGroup").removeClass('d-none');
                    if (pageLayout == "horizontal") {
                        $('#horizontal').removeClass('d-none');
                        $('#gHorizontal').addClass('d-none');
                        $('#vertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 57 mm)');
                        $('#pHeight').html('(@lang('admin.default') 89 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    } else {
                        $('#horizontal').addClass('d-none');
                        $('#vertical').removeClass('d-none');
                        $('#gVertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 89 mm)');
                        $('#pHeight').html('(@lang('admin.default') 57 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 13 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 13 mm)');
                    }
                }
            });
        });


        $(document).ready(function() {
            $(document).on("change", "#pageLayoutStyle", function(event) {
                window.pageLayout = $(this).val();
                if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        $('#gHorizontal').removeClass('d-none');
                        $('#gVertical').removeAttr("class");
                        $('#gVertical').addClass('d-none');
                        $('#horizontal').addClass('d-none');
                        $('#vertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 55 mm)');
                        $('#pHeight').html('(@lang('admin.default') 106 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    } else {
                        $('#gVertical').removeClass('d-none');
                        $('#gHorizontal').removeAttr("class");
                        $('#gHorizontal').addClass('d-none');
                        $('#horizontal').addClass('d-none');
                        $('#vertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 106 mm)');
                        $('#pHeight').html('(@lang('admin.default') 55 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    }
                } else {
                    if (pageLayout == "horizontal") {
                        $('#horizontal').removeClass('d-none');
                        $('#vertical').removeAttr("class");
                        $('#vertical').addClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 57 mm)');
                        $('#pHeight').html('(@lang('admin.default') 89 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 21 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 21 mm)');
                    } else {
                        $('#horizontal').removeAttr("class");
                        $('#horizontal').addClass('d-none');
                        $('#vertical').removeClass('d-none');
                        $('#pWidth').html('(@lang('admin.default') 89 mm)');
                        $('#pHeight').html('(@lang('admin.default') 57 mm)');
                        $('#profileWidth').html('(@lang('admin.default') 13 mm)');
                        $('#profileHeight').html('(@lang('admin.default') 13 mm)');
                    }
                }
            });

            $(document).on("keyup", "#title", function(event) {
                let titleValue = $(this).val();
                $("#titleV").html(titleValue);
            });

            $(document).on("keyup", "#signDesignation", function(event) {
                let disSignValue = $(this).val();
                $("#disSign").html(disSignValue);
            });

            $(document).on("keyup", "#plWidth", function(event) {
                let plWidthValue = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal__card").css({
                        "width": plWidthValue + "mm"
                    });
                } else {
                    $(".vertical__card").css({
                        "width": plWidthValue + "mm"
                    });
                }
            });

            $(document).on("keyup", "#plHeight", function(event) {
                let plHeightValue = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal__card").css({
                        "height": plHeightValue + "mm"
                    });
                } else {
                    $(".vertical__card").css({
                        "height": plHeightValue + "mm"
                    });
                }
            });

            $(document).on("change", "#userPhotoStyle", function(event) {
                let userPhotoStyle = $(this).val();
                if (pageLayout == "horizontal") {
                    if (userPhotoStyle == "round") {
                        $(".hRoundImg").css({
                            'border-radius': '50%'
                        });
                    } else {
                        $(".hRoundImg").css({
                            'border-radius': '0'
                        });
                    }
                } else {
                    if (userPhotoStyle == "round") {
                        $(".vRoundImg").css({
                            'border-radius': '50%'
                        });
                    } else {
                        $(".vRoundImg").css({
                            'border-radius': '0'
                        });
                    }
                }
            });

            $(document).on("keyup", "#userPhotoWidth", function(event) {
                let userPhotoWidth = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".hSize").css({
                        "width": userPhotoWidth + "mm"
                    });
                } else {
                    $(".vSize").css({
                        "width": userPhotoWidth + "mm"
                    });
                    $(".vSize").css({
                        "flex-basis": userPhotoWidth + "mm"
                    });
                }
            });

            $(document).on("keyup", "#userPhotoheight", function(event) {
                let userPhotoHeight = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".hSize").css({
                        "height": userPhotoHeight + "mm"
                    });
                } else {
                    $(".vSize").css({
                        "height": userPhotoHeight + "mm"
                    });
                }
            });

            $(document).on("keyup", "#tSpace", function(event) {
                let tSpace = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal_card_body").css({
                        "padding-top": tSpace + "mm"
                    });
                } else {
                    $(".vertical_card_body").css({
                        "padding-top": tSpace + "mm"
                    });
                }
            });

            $(document).on("keyup", "#bSpace", function(event) {
                let bSpace = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal_card_body").css({
                        "padding-bottom": bSpace + "mm"
                    });
                } else {
                    $(".vertical_card_body").css({
                        "padding-bottom": bSpace + "mm"
                    });
                }
            });

            $(document).on("keyup", "#lSpace", function(event) {
                let lSpace = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal_card_body").css({
                        "padding-left": lSpace + "mm"
                    });
                } else {
                    $(".vertical_card_body").css({
                        "padding-left": lSpace + "mm"
                    });
                }
            });

            $(document).on("keyup", "#rSpace", function(event) {
                let rSpace = $(this).val();
                if (pageLayout == "horizontal") {
                    $(".horizontal_card_body").css({
                        "padding-right": rSpace + "mm"
                    });
                } else {
                    $(".vertical_card_body").css({
                        "padding-right": rSpace + "mm"
                    });
                }
            });

            // Radio Button
            studentName = (status) => {
                if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#gHName").show();
                        } else {
                            $("#gHName").hide();
                        }
                    } else {
                        if (status == "1") {
                            $("#gVName").show();
                        } else {
                            $("#gVName").hide();
                        }
                    }
                } else {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#hName").show();
                        } else {
                            $("#hName").hide();
                        }
                    } else {
                        if (status == "1") {
                            $("#vName").show();
                        } else {
                            $("#vName").hide();
                        }
                    }
                }
            }

            idRoll = (status) => {
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                        $("#hAdmissionNumber").show();
                    } else {
                        $("#hAdmissionNumber").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vAdmissionNumber").show();
                    } else {
                        $("#vAdmissionNumber").hide();
                    }
                }
            }

            IDclass = (status) => {
                var module = $('#module').val();
                if (pageLayout == "horizontal") {
                    if(module =='') {
                        if (status == "1") {
                            $("#hClass").show();
                        } else {
                            $("#hClass").hide();
                        }
                    }
                  
                } else {
                    if(module =='') {
                        if (status == "1") {
                            $("#vClass").show();
                        } else {
                            $("#vClass").hide();
                        }
                    }
                }
            }

            IDSession = (status) => {                        
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                            $("#hSession").show();
                    } else {
                            $("#hSession").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vSession").show();
                    } else {
                        $("#vSession").hide();
                    }
                }
            }

            IDFaculty = (status) => {                        
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                            $("#hFaculty").show();
                    } else {
                            $("#hFaculty").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vFaculty").show();
                    } else {
                        $("#vFaculty").hide();
                    }
                }
            }

            

            IDDepartment = (status) => {                        
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                            $("#hDepartment").show();
                    } else {
                            $("#hDepartment").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vDepartment").show();
                    } else {
                        $("#vDepartment").hide();
                    }
                }
            }
            IDAcademic = (status) => {                        
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                            $("#hAcademic").show();
                    } else {
                            $("#hAcademic").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vAcademic").show();
                    } else {
                        $("#vAcademic").hide();
                    }
                }
            }
            IDSemester = (status) => {                        
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                            $("#hSemester").show();
                    } else {
                            $("#hSemester").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vSemester").show();
                    } else {
                        $("#vSemester").hide();
                    }
                }
            }
            fatherName = (status) => {
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                        $("#hFatherName").show();
                    } else {
                        $("#hFatherName").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vFatherName").show();
                    } else {
                        $("#vFatherName").hide();
                    }
                }
            }

            motherName = (status) => {
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                        $("#hMotherName").show();
                    } else {
                        $("#hMotherName").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vMotherName").show();
                    } else {
                        $("#vMotherName").hide();
                    }
                }
            }

            dOB = (status) => {
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                        $("#hDob").show();
                    } else {
                        $("#hDob").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vDob").show();
                    } else {
                        $("#vDob").hide();
                    }
                }
            }

            bloodGroup = (status) => {
                if (pageLayout == "horizontal") {
                    if (status == "1") {
                        $("#hBloodGroup").show();
                    } else {
                        $("#hBloodGroup").hide();
                    }
                } else {
                    if (status == "1") {
                        $("#vBloodGroup").show();
                    } else {
                        $("#vBloodGroup").hide();
                    }
                }
            }

            phoneNumber = (status) => {
                if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#hPhoneNumber").show();
                        } else {
                            $("#hPhoneNumber").hide();
                        }
                    } else {
                        if (status == "1") {
                            $("#gVAddress").show();
                        } else {
                            $("#gVAddress").hide();
                        }
                    }
                }
            }

            children = (status) => {
                if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#hPhoneNumber").show();
                        } else {
                            $("#hPhoneNumber").hide();
                        }
                    } else {
                        if (status == "1") {
                            $("#gVAddress").show();
                        } else {
                            $("#gVAddress").hide();
                        }
                    }
                }
            }

            addRess = (status) => {
                var module = $('#module').val();
                if (userType == "3") {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#gHAddress").show();
                        } else {
                            $("#gHAddress").hide();
                        }

                       
                    } else {
                        if (status == "1") {
                            $(".gVAddress").show();
                        } else {
                            $(".gVAddress").hide();
                        }
                    }
                } else {
                    if (pageLayout == "horizontal") {
                        if (status == "1") {
                            $("#hAddress").show();
                        } else {
                            $("#hAddress").hide();
                        }
                    } else {
                        if (status == "1") {
                            $(".vAddress").show();
                        } else {
                            $(".vAddress").hide();
                        }
                    }
                }
            }
        });

        // Image Show
        function imageChangeWithBackFile(input, srcBack) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (pageLayout == "horizontal") {
                        $('.horizontal__card').css('background-image', 'url(' + e.target.result +')');
                    } else {
                        $('.vertical__card').css('background-image', 'url(' + e.target.result +')');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function imageChangeWithFile(input, srcId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (pageLayout == "horizontal") {
                        $('.hImg').css('background-image', 'url(' + e.target.result +')');
                    } else {
                        $('.vImg').css('background-image', 'url(' + e.target.result +')');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function logoImageChangeWithFile(input, srcIdLogo) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (pageLayout == "horizontal") {
                        $('.hLogo').css('background-image', 'url(' + e.target.result +')');
                    } else {
                        $('.vLogo').css('background-image', 'url(' + e.target.result +')');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function signatureImageChangeWithFile(input, srcIdDis) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (pageLayout == "horizontal") {
                        $('.hSign').css('background-image', 'url(' + e.target.result +')');
                    } else {
                        $('.vSign').css('background-image', 'url(' + e.target.result +')');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Delete
        $(document).on("click", "#deleteBackImg", function(event) {
            $('#placeholderFileFiveName').removeAttr('placeholder');
            $('#placeholderFileFiveName').attr("placeholder", "@lang('admin.background_image')");
            $('#document_file_5').val('');
            if (pageLayout == "horizontal") {
                $('.horizontal__card').css('background-image',
                    'url({{ asset('public/backEnd/id_card/img/vertical_bg.png') }})');
            } else {
                $('.vertical__card').css('background-image',
                    'url({{ asset('public/backEnd/id_card/img/horizontal_bg.png') }})');
            }
        });

        $(document).on("click", "#deleteProImg", function(event) {
            $('#placeholderFileSixName').removeAttr('placeholder');
            $('#placeholderFileSixName').attr("placeholder", "@lang('admin.profile_image')");
            $('#document_file_6').val('');
            if (pageLayout == "horizontal") {
                $('.hImg').css('background-image', 'url({{ asset('public/backEnd/id_card/img/thumb.png') }})');
            } else {
                $('.vImg').css('background-image', 'url({{ asset('public/backEnd/id_card/img/thumb.png') }})');
            }
        });

        $(document).on("click", "#deleteLogoImg", function(event) {
            $('#placeholderFileThreeName').removeAttr('placeholder');
            $('#placeholderFileThreeName').attr("placeholder", "@lang('common.logo')");
            $('#document_file_3').val('');
            if (pageLayout == "horizontal") {
                $('.hLogo').css('background-image', 'url({{ asset('public/backEnd/img/logo.png') }})');
            } else {
                $('.vLogo').css('background-image', 'url({{ asset('public/backEnd/img/logo.png') }})');
            }
        });

        $(document).on("click", "#deleteSignImg", function(event) {
            $('#placeholderFileFourName').removeAttr('placeholder');
            $('#placeholderFileFourName').attr("placeholder", "@lang('admin.signature')");
            $('#document_file_4').val('');
            if (pageLayout == "horizontal") {
                $('.hSign').css('background-image',
                'url({{ asset('public/backEnd/id_card/img/Signature.png') }})');
            } else {
                $('.vSign').css('background-image',
                'url({{ asset('public/backEnd/id_card/img/Signature.png') }})');
            }
        });
    </script>
@endpush
