<div role="tabpanel"
     class="tab-pane fade {{ $type == '' && Session::get('studentDocuments') == '' ? 'show active' : '' }}"
     id="studentProfile">
    <div class="white-box">
        {{--        <h4 class="stu-sub-head">@lang('student.personal_info')</h4>--}}
        @if (is_show('admission_date'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.admission_date')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ !empty($student_detail->admission_date) ? dateConvert($student_detail->admission_date) : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (is_show('date_of_birth'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.date_of_birth')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ !empty($student_detail->date_of_birth) ? dateConvert($student_detail->date_of_birth) : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        @lang('student.age')
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        {{ \Carbon\Carbon::parse($student_detail->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years') }}
                    </div>
                </div>
            </div>
        </div>
        @if (is_show('student_category_id'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.category')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ $student_detail->category != '' ? $student_detail->category->category_name : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        @lang('student.group')
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        {{ $student_detail->group ? $student_detail->group->group : '' }}
                    </div>
                </div>
            </div>
        </div>
        @if (is_show('religion'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.religion')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ $student_detail->religion != '' ? $student_detail->religion->base_setup_name : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('phone_number'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.phone_number')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            @if ($student_detail->mobile)
                                <a href="tel:{{ @$student_detail->mobile }}"> {{ @$student_detail->mobile }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('email_address'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('common.email_address')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            @if ($student_detail->email)
                                <a href="mailto:{{ @$student_detail->email }}"> {{ @$student_detail->email }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- changes for lead module --abunayem --}}
        @if (moduleStatusCheck('Lead') == true)
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('lead::lead.city')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ @$student_detail->leadCity->city_name }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('lead::lead.source')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ @$student_detail->source->source_name }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- end --}}
        @if (is_show('current_address'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.present_address')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ @$student_detail->current_address }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('permanent_address'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="">
                            @lang('student.permanent_address')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-7">
                        <div class="">
                            {{ @$student_detail->permanent_address }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Start Parent Part -->
        <h4 class="stu-sub-head mt-40">@lang('student.Parent_Guardian_Details')</h4>
        <div class="d-flex">
            @if (is_show('fathers_photo'))
                <div class="mr-20 mt-20">
                    <img class="student-meta-img img-100"
                         src="{{ file_exists(@$student_detail->parents->fathers_photo) ? asset($student_detail->parents->fathers_photo) : asset('public/uploads/staff/demo/father.png') }}"
                         alt="">

                </div>
            @endif
            <div class="w-100">
                @if (is_show('fathers_name'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.father_name')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ @$student_detail->parents->fathers_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (is_show('fathers_occupation'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.occupation')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ @$student_detail->parents != '' ? @$student_detail->parents->fathers_occupation : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (is_show('fathers_phone'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.phone_number')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ @$student_detail->parents != '' ? @$student_detail->parents->fathers_mobile : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex">
            @if (is_show('mothers_photo'))
                <div class="mr-20 mt-20">
                    <img class="student-meta-img img-100"
                         src="{{ file_exists(@$student_detail->parents->mothers_photo) ? asset($student_detail->parents->mothers_photo) : asset('public/uploads/staff/demo/mother.jpg') }}"
                         alt="">
                </div>
            @endif
            <div class="w-100">
                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="">
                                @lang('student.mother_name')
                            </div>
                        </div>
                        @if (is_show('mothers_name'))
                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->mothers_name : '' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="">
                                @lang('student.occupation')
                            </div>
                        </div>
                        @if (is_show('mothers_occupation'))
                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->mothers_occupation : '' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if (is_show('mothers_phone'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.phone_number')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->mothers_mobile : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex">
            @if (is_show('guardians_photo'))
                <div class="mr-20 mt-20">
                    <img class="student-meta-img img-100"
                         src="{{ file_exists(@$student_detail->parents->guardians_photo) ? asset($student_detail->parents->guardians_photo) : asset('public/uploads/staff/demo/guardian.jpg') }}"
                         alt="">

                </div>
            @endif
            <div class="w-100">
                @if (is_show('guardians_name'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.guardian_name')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_name : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (is_show('guardians_email'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.email_address')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_email : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (is_show('guardians_phone'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.phone_number')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_mobile : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="">
                                @lang('student.relation_with_guardian')
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-7">
                            <div class="">
                                {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_relation : '' }}
                            </div>
                        </div>
                    </div>
                </div>
                @if (is_show('guardians_occupation'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.occupation')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_occupation : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (is_show('guardians_address'))
                    <div class="single-info">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="">
                                    @lang('student.guardian_address')
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                                <div class="">
                                    {{ $student_detail->parents != '' ? @$student_detail->parents->guardians_address : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- End Parent Part -->

        <!-- Start Transport Part -->
        @if(isMenuAllowToShow('transport') || isMenuAllowToShow('dormitory'))

            <h4 class="stu-sub-head mt-40">@lang('student.'.(isMenuAllowToShow('transport')? 'transport' : ''). (isMenuAllowToShow('transport') && isMenuAllowToShow('dormitory')? '_and_' : '').(isMenuAllowToShow('dormitory')? 'dormitory' : '').'_info')</h4>
            @if(isMenuAllowToShow('transport'))
                @if (is_show('route'))
                    @if (!empty($student_detail->route_list_id))
                        <div class="single-info">
                            <div class="row">
                                <div class="col-lg-5 col-md-5">
                                    <div class="">
                                        @lang('student.route')
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-6">
                                    <div class="">
                                        {{ isset($student_detail->route_list_id) ? @$student_detail->route->title : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @if (is_show('vehicle'))
                    @if (isset($student_detail->vehicle))
                        @if (!empty($vehicle_no))
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('student.vehicle_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{ $student_detail->vehicle != '' ? @$student_detail->vehicle->vehicle_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endif
                @endif


                @if (isset($driver_info))
                    @if (!empty($driver_info->full_name))
                        <div class="single-info">
                            <div class="row">
                                <div class="col-lg-5 col-md-5">
                                    <div class="">
                                        @lang('student.driver_name')
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-6">
                                    <div class="">
                                        {{ $student_detail->vechile_id != '' ? @$driver_info->full_name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endif

                @if (isset($driver_info))
                    @if (!empty($driver_info->mobile))
                        <div class="single-info">
                            <div class="row">
                                <div class="col-lg-5 col-md-5">
                                    <div class="">
                                        @lang('student.driver_phone_number')
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-6">
                                    <div class="">
                                        {{ $student_detail->vechile_id != '' ? @$driver_info->mobile : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

            @if (is_show('dormitory_name') && isMenuAllowToShow('dormitory'))
                @if (isset($student_detail->dormitory))
                    @if (!empty($student_detail->dormitory->dormitory_name))
                        <div class="single-info">
                            <div class="row">
                                <div class="col-lg-5 col-md-5">
                                    <div class="">
                                        @lang('student.dormitory_name')
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-6">
                                    <div class="">
                                        {{ isset($student_detail->dormitory_id) ? @$student_detail->dormitory->dormitory_name : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

        @endif

        <!-- End Transport Part -->

        <!-- Start Other Information Part -->
        <h4 class="stu-sub-head mt-40">@lang('student.other_information')</h4>
        @if (is_show('blood_group'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('common.blood_group')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->bloodgroup_id) ? @$student_detail->bloodGroup->base_setup_name : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('student_group_id'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.student_group')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->student_group_id) ? @$student_detail->group->group : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('height'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.height')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->height) ? @$student_detail->height : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('weight'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.weight')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->weight) ? @$student_detail->weight : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('previous_school_details'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.previous_school_details')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->previous_school_details) ? @$student_detail->previous_school_details : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('national_id_number'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.national_id_number')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->national_id_no) ? @$student_detail->national_id_no : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('local_id_number'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.local_id_number')
                        </div>
                    </div>


                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->local_id_no) ? @$student_detail->local_id_no : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('bank_account_number'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('accounts.bank_account_number')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->bank_account_no) ? @$student_detail->bank_account_no : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('bank_name'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.bank_name')
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->bank_name) ? @$student_detail->bank_name : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (is_show('ifsc_code'))
            <div class="single-info">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="">
                            @lang('student.ifsc_code')
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="">
                            {{ isset($student_detail->ifsc_code) ? @$student_detail->ifsc_code : '' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- End Other Information Part -->
        @if (is_show('custom_field'))
            {{-- Custom field start --}}
            @include('backEnd.customField._coutom_field_show')
            {{-- Custom field end --}}
        @endif
    </div>
</div>
