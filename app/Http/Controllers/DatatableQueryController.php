<?php

namespace App\Http\Controllers;

use DateTime;
use App\SmBook;
use App\SmItem;
use DataTables;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmUserLog;
use App\SmHomework;
use App\SmAddIncome;
use App\SmBookIssue;
use App\SmComplaint;
use App\SmAddExpense;
use App\SmEmailSmsLog;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmLeaveDefine;
use App\SmAcademicYear;
use App\SmClassTeacher;
use App\SmLeaveRequest;
use App\SmNotification;
use App\SmAssignSubject;
use App\SmBankPaymentSlip;
use App\Models\FeesInvoice;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmAssignClassTeacher;
use Illuminate\Support\Carbon;
use App\SmTeacherUploadContent;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;

class DatatableQueryController extends Controller
{
    public function studentDetailsDatatable(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->withoutGlobalScope(StatusAcademicSchoolScope::class)->get();
            $sessions = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
            $academic_year = $request->academic_year;
            $class_id = $request->class_id;
            $name = $request->name;
            $roll_no = $request->roll_no;
            $section = $request->section_id;
            $data['un_session_id']= $request->un_session_id ;
            $data['un_academic_id']= $request->un_academic_id ;
            $data['un_faculty_id']= $request->un_faculty_id;
            $data['un_department_id']= $request->un_department_id;
            $data['un_semester_id']= $request->un_semester_id;
            $data['un_semester_label_id']= $request->un_semester_label_id;
            $data['un_section_id']= $request->un_section_id;
           
            return view('backEnd.studentInformation.student_details', compact('classes', 'class_id', 'name', 'roll_no', 'sessions', 'section', 'academic_year','data'));
        }
        if ($request->ajax()) {
            $records = StudentRecord::query();
            $records->where('is_promote', 0)->where('school_id',auth()->user()->school_id);
            $records->when(moduleStatusCheck('University') && $request->filled('un_academic_id'), function ($u_query) use ($request) {
                $u_query->where('un_academic_id', $request->un_academic_id);
                }, function ($query) use ($request) {
                    $query->when($request->academic_year, function ($query) use ($request) {
                    $query->where('academic_id', $request->academic_year);
                    });
            })
            ->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                $u_query->where('un_faculty_id', $request->un_faculty_id);
            }, function ($query) use ($request) {
                $query->when($request->class, function ($query) use ($request) {
                    $query->where('class_id', $request->class);
                });
            })
            
            ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                $u_query->where('un_department_id', $request->un_department_id);
            }, function ($query) use ($request) {
                $query->when($request->section, function ($query) use ($request) {
                    $query->where('section_id', $request->section);
                });
            })
            ->when(!$request->academic_year && moduleStatusCheck('University')==false, function ($query) use ($request) {
                $query->where('academic_id', getAcademicId());
            })
            
            ->when( moduleStatusCheck('University') && $request->filled('un_session_id'), function ($query) use ($request) {
                $query->where('un_session_id', $request->un_session_id);
            })
            
            ->when( moduleStatusCheck('University') && $request->filled('un_semester_label_id'), function ($query) use ($request) {
                $query->where('un_semester_label_id', $request->un_semester_label_id);
            });
            
           $student_records = $records->where('is_promote', 0)->whereHas('student')->get(['student_id'])->unique('student_id')->toArray();
           $all_students =  SmStudent::with('studentRecords','studentRecords.class','studentRecords.section')->whereIn('id',$student_records)
                                ->where('active_status', 1)
                                ->with(array('parents' => function ($query) {
                                    $query->select('id', 'fathers_name');
                                }))
                                ->with(array('gender' => function ($query) {
                                    $query->select('id', 'base_setup_name');
                                }))
                                ->with(array('category' => function ($query) {
                                    $query->select('id', 'category_name');
                                }))
                                ->when($request->name, function ($query) use ($request) {
                                    $query->where('full_name', 'like', '%' . $request->name . '%');
                                });

                             

            $students = SmStudent::with(['gender', 'studentRecords' => function ($q) use ($request) {
                return $q->when(moduleStatusCheck('University') && $request->filled('un_academic_id'), function ($u_query) use ($request) {
                        $u_query->where('un_academic_id', $request->un_academic_id);
                    }, function ($query) use ($request) {
                       $query->when($request->academic_year, function ($query) use ($request) {
                            $query->where('academic_id', $request->academic_year);
                        });
                    })
                    ->when(moduleStatusCheck('University') && $request->filled('un_faculty_id'), function ($u_query) use ($request) {
                        $u_query->where('un_faculty_id', $request->un_faculty_id);
                    }, function ($query) use ($request) {
                        $query->when($request->class, function ($query) use ($request) {
                            $query->where('class_id', $request->class);
                        });
                    })

                    ->when(moduleStatusCheck('University') && $request->filled('un_department_id'), function ($u_query) use ($request) {
                        $u_query->where('un_department_id', $request->un_department_id);
                    }, function ($query) use ($request) {
                        $query->when($request->section, function ($query) use ($request) {
                            $query->where('section_id', $request->section);
                        });
                    })
                    ->where('is_promote', 0)
                    ->when(!$request->academic_year && moduleStatusCheck('University')==false, function ($query) use ($request) {
                        $query->where('academic_id', getAcademicId());
                    });

            }])->select('sm_students.*');
            $students->where('sm_students.active_status', 1);

            // if ($request->name != "") {
            //     $students->where('sm_students.full_name', 'like', '%' . $request->name . '%');
            // }
            // if ($request->roll_no != "") {
            //     $students->where('sm_students.roll_no', 'like', '%' . $request->roll_no . '%');
            // }

            // return $request;
            $students = $students->with('studentRecords','studentRecords.class','studentRecords.section')->where('sm_students.school_id', Auth::user()->school_id)
                ->with(array('parents' => function ($query) {
                    $query->select('id', 'fathers_name');
                }))
                ->with(array('gender' => function ($query) {
                    $query->select('id', 'base_setup_name');
                })) 
                ->with(array('category' => function ($query) {
                    $query->select('id', 'category_name');
                }));

                $data = [];
            return Datatables::of($all_students)
                ->addIndexColumn()
                ->addColumn('dob', function ($row) {
                    $dob = dateConvert(@$row->date_of_birth);
                    return $dob;
                })
               

                ->addColumn('full_name', function ($row) {
                    $full_name_link = '<a target="_blank" href="'. route('student_view', [$row->id]) . '">' . $row->first_name .' '. $row->last_name . '</a>';
                    return $full_name_link;
                })
                
                ->addColumn('mobile', function ($row) {
                    $mobile = '<a href="tel:'.$row->mobile.'">' .$row->mobile. '</a>';
                    return $mobile;
                })


                ->addColumn('semester_label', function ($row) use ($request) {
                    $semester_label=[];
                    foreach ($row->studentRecords as $label) {
                        if (moduleStatusCheck('University')) {
                            $semester_label[] = $label->unSemesterLabel->name;
                        }
                    }
                    return $semester_label;
                })

                ->addColumn('class_sec', function ($row) use ($request) {
                    $class_sec=[];
                    foreach ($row->studentRecords as $classSec) {
                        if (moduleStatusCheck('University')) {
                            $class_sec[] = $classSec->unFaculty->name.'('. $classSec->unDepartment->name .')';
                        } else {
                            $class_sec[] = $classSec->class->class_name.'('. $classSec->section->section_name .')';
                        }
                    }

                    return implode(', ', $class_sec);
                })

                ->addColumn('action', function ($row) {
                    $langName = (moduleStatusCheck('University')) ? app('translator')->get('university::un.assign_faculty_department') : app('translator')->get('student.assign_class') ;
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('student.assign-class') === true ? '<a class="dropdown-item" target="_blank" href="' . route('student.assign-class', [$row->id]) . '">' . $langName . '</a>' :'')

                        .((userPermission('student.assign-class') === true && moduleStatusCheck('University')) ?
                        '<a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id,'assign_subject']) . '">' .  app('translator')->get('university::un.assign_subject') . '</a>' :'')

                        .'<a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                        (userPermission('student_edit') === true ? '<a class="dropdown-item" href="' . route('student_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                        (userPermission('disabled_student') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                            '<a onclick="deleteId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.disable') . '</a>') : '') .

                        '</div>
                                </div>';

                    return $btn;
                })
                ->rawColumns(['action','full_name', 'mobile', 'dob','class_sec','full_name', 'mobile', 'dob','class_sec'])
                ->make(true);
        }
        return view('backEnd.studentInformation.students');
    }

    public function searchStudentList(Request $request)
    {
        $student_ids = StudentRecord::when($request->academic_year, function ($query) use ($request) {
            $query->where('academic_id', $request->academic_year);
        })
        ->when($request->class, function ($query) use ($request) {
            $query->where('class_id', $request->class);
        })
        ->when($request->section, function ($query) use ($request) {
            $query->where('section_id', $request->section);
        })
        ->when(!$request->academic_year, function ($query) use ($request) {
            $query->where('academic_id', getAcademicId());
        })
        ->distinct('student_id')->pluck('student_id')->toArray();

        $students = SmStudent::query();
        $students->where('active_status', 1);

       
        if ($request->name != "") {
            $students->where('full_name', 'like', '%' . $request->name . '%');
        }
        if ($request->roll_no != "") {
            $students->where('roll_no', 'like', '%' . $request->roll_no . '%');
        }


        $students = $students->whereIn('id', $student_ids)->where('school_id', Auth::user()->school_id)
           
            ->with(array('parents' => function ($query) {
                $query->select('id', 'fathers_name');
            }))
            ->with(array('gender' => function ($query) {
                $query->select('id', 'base_setup_name');
            }))
            ->with(array('category' => function ($query) {
                $query->select('id', 'category_name');
            }))
            ->get();

        return Datatables::of($students)
            ->addIndexColumn()
            ->addColumn('dob', function ($row) {

                $dob = dateConvert(@$row->date_of_birth);

                return $dob;
            })
            ->rawColumns(['dob'])
            ->editColumn('full_name', function ($row) {
                $full_name_link = '<a target="_blank" href="'. route('student_view', [$row->id]) . '">' . $row->first_name .' '. $row->last_name . '</a>';
                return $full_name_link;
            })
            
            ->editColumn('mobile', function ($row) {
                $mobile = '<a href="tel:'.$row->mobile.'">' .$row->mobile. '</a>';
                return $mobile;
            })
            ->addColumn('class_sec', function ($row) use ($request) {
                $class_sec=[];
                foreach ($row->studentRecords as $classSec) {
                    $class_sec[]=$classSec->class->class_name.'('. $classSec->section->section_name .'), ' ;
                }
                if ($request->class) {
                    $sections = [];
                    $class =  $row->recordClass ? $row->recordClass->class->class_name : '';
                    if ($request->section) {
                        $sections [] = $row->recordSection != "" ? $row->recordSection->section->section_name:"";
                    } else {
                        foreach ($row->recordClasses as $section) {
                            $sections [] = $section->section->section_name;
                        }
                    }
                    return  $class .'('.$sections.'), ';
                }
                return $class_sec;
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    (userPermission('student_edit') === true ? '<a class="dropdown-item" href="' . route('student_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('disabled_student') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.disable') . '</a>') : '') .

                    '</div>
                            </div>';

                return $btn;
            })
            ->rawColumns(['action','full_name', 'mobile', 'dob','class_sec', 'mobile', 'dob','class_sec'])
            ->make(true);

        return view('backEnd.studentInformation.students');
    }

    public function AjaxStudentSearch($class, $section, $date)
    {

        try {
            // $date = $request->attendance_date;
            if (getClassActeacherAccesscess()) {
                $classes = SmClass::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            } else {
                $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                $classes = SmAssignSubject::where('teacher_id', $teacher_info->id)->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
                    ->where('sm_assign_subjects.academic_id', getAcademicId())
                    ->where('sm_assign_subjects.active_status', 1)
                    ->where('sm_assign_subjects.school_id', Auth::user()->school_id)
                    ->select('sm_classes.id', 'class_name')
                    ->distinct('sm_classes.id')
                    ->get();
            }
            $students = SmStudent::where('class_id', $class)->where('section_id', $section)->where('active_status', 1)
                ->where('school_id', Auth::user()->school_id)->get();

            if ($students->isEmpty()) {
                Toastr::error('No Result Found', 'Failed');
                return redirect('student-attendance');
            }

            $already_assigned_students = [];
            $new_students = [];
            $attendance_type = "";
            foreach ($students as $student) {
                $attendance = SmStudentAttendance::where('student_id', $student->id)
                    ->where('attendance_date', date('Y-m-d', $date))
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if ($attendance != "") {
                    $already_assigned_students[] = $attendance;
                    $attendance_type = $attendance->attendance_type;
                } else {
                    $new_students[] = $student;
                }
            }
            $class_id = $class;
            $section_id = $section;
            $class_info = SmClass::find($class);
            $section_info = SmSection::find($section);

            $search_info['class_name'] = $class_info->class_name;
            $search_info['section_name'] = $section_info->section_name;
            $search_info['date'] = $date;

            $all_students = [];
            foreach ($already_assigned_students as $key => $value) {
                $all_students[$value->student_id]['std_id'] = $value->student_id;
                $all_students[$value->student_id]['admission_no'] = $value->studentInfo->admission_no;
                $all_students[$value->student_id]['roll_no'] = $value->studentInfo->roll_no;
                $all_students[$value->student_id]['full_name'] = $value->studentInfo->full_name;
                $all_students[$value->student_id]['attendance_type'] = $value->attendance_type;
                $all_students[$value->student_id]['notes'] = $value->notes;
                $all_students[$value->student_id]['attendance_date'] = $value->attendance_date;
            }
            foreach ($new_students as $key => $value) {
                $all_students[$value->id]['std_id'] = $value->id;
                $all_students[$value->id]['admission_no'] = $value->admission_no;
                $all_students[$value->id]['roll_no'] = $value->roll_no;
                $all_students[$value->id]['full_name'] = $value->full_name;
                $all_students[$value->id]['attendance_type'] = '';
                $all_students[$value->id]['notes'] = '';
                $all_students[$value->id]['attendance_date'] = '';
            }
            // return $all_students;

            // if ($request->ajax()) {


            return Datatables::of($all_students)
                ->addIndexColumn()
                ->addColumn('teacher_note', function ($row) {
                    $note_input = '<input type="text" name="note>';

                    return $note_input;
                })
                // ->rawColumns(['teacher_note'])

                ->addColumn('action', function ($row) {

                    $btn = '<div class="d-flex radio-btn-flex">
                                    <div class="mr-20">
                                        <input type="radio" data-id="' . $row['std_id'] . '" name="attendance[' . $row['std_id'] . ']" id="attendanceP' . $row['std_id'] . '"' . ($row['attendance_type'] == 'P' ? 'checked' : '') . ' value="P" class="common-radio attendanceP attendance_type">
                                        <label for="attendanceP' . $row['std_id'] . '">' . app('translator')->get('common.present') . '</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" data-id="' . $row['std_id'] . '" name="attendance[' . $row['std_id'] . ']" id="attendanceL' . $row['std_id'] . '"' . ($row['attendance_type'] == 'L' ? 'checked' : '') . ' value="L" class="common-radio attendanceL attendance_type">
                                        <label for="attendanceL' . $row['std_id'] . '">' . app('translator')->get('common.late') . '</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" data-id="' . $row['std_id'] . '" name="attendance[' . $row['std_id'] . ']" id="attendanceA' . $row['std_id'] . '"' . ($row['attendance_type'] == 'A' ? 'checked' : '') . ' value="A" class="common-radio attendanceA attendance_type">
                                        <label for="attendanceA' . $row['std_id'] . '">' . app('translator')->get('common.absent') . '</label>
                                    </div>
                                    <div class="mr-20">
                                        <input type="radio" data-id="' . $row['std_id'] . '" name="attendance[' . $row['std_id'] . ']" id="attendanceF' . $row['std_id'] . '"' . ($row['attendance_type'] == 'F' ? 'checked' : '') . ' value="F" class="common-radio attendanceF attendance_type">
                                        <label for="attendanceF' . $row['std_id'] . '">' . app('translator')->get('common.half_day') . '</label>
                                    </div>
                                       
    
                                    </div>';

                    return $btn;
                })
                ->rawColumns(['action', 'teacher_note'])
                ->make(true);

            // }


            return view('backEnd.studentInformation.student_attendance', compact('classes', 'date', 'class_id', 'section_id', 'date', 'already_assigned_students', 'new_students', 'attendance_type', 'search_info'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getStaffList(Request $request)
    {  
        try {
            if (Auth::user()->role_id == 1) {
                $staffs = SmStaff::query();
                
                $staffs->withOutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', Auth::user()->school_id)
                    ->where('is_saas', 0)
                    ->with(array('roles' => function ($query) {
                        $query->select('id', 'name');
                    }))
                    ->with(array('departments' => function ($query) {
                        $query->select('id', 'name');
                    }))
                    ->with(array('designations' => function ($query) {
                        $query->select('id', 'title');
                    })) ;
                    if ($request->role_id != "") {
                        $staffs->where(function($q) use ($request) {
                            $q->where('role_id', $request->role_id)->orWhere('previous_role_id', $request->role_id);
                        });
        
                    }
                    if ($request->staff_no != "") {
                        $staffs->where('staff_no', $request->staff_no);
                    }
        
                    if ($request->staff_name != "") {
                        $staffs->where('full_name', 'like', '%' . $request->staff_name . '%');
                    }
        
                    if (Auth::user()->role_id != 1) {
                        $staffs->where('role_id', '!=', 1);
                    }
                    $staffs =  $staffs->get();
            } else {
                $staffs = SmStaff::where('is_saas', 0)->where('school_id', Auth::user()->school_id)
                    ->where('role_id', '!=', 1)
                    ->where('role_id', '!=', 5)
                    ->with(array('roles' => function ($query) {
                        $query->select('id', 'name');
                    }))
                    ->with(array('departments' => function ($query) {
                        $query->select('id', 'name');
                    }))
                    ->with(array('designations' => function ($query) {
                        $query->select('id', 'title');
                    }))
                    ->get();
            }

            return Datatables::of($staffs)
                ->addIndexColumn()
                ->addColumn('switch', function ($row) {
                    if (Auth::user()->id != $row->user_id || Auth::user()->role_id != 1) {
                        $btn = '<label class="switch_toggle">
                            <input type="checkbox" id="' . $row->id . '" value="' . $row->id . '" class="switch-input-staff hr_'.$row->id.'" ' . ($row->active_status == 0 ? '' : 'checked') . '>
                            <span class="slider round"></span>
                          </label>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" target="_blank" href="' . route('viewStaff', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                        (userPermission('editStaff') === true ? '<a class="dropdown-item" href="' . route('editStaff', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                        (userPermission('deleteStaff') === true ? ($row->user_id == Auth::id() ? '' :
                            '<a onclick="deleteStaff(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .

                        '</div>
                                </div>';

                    return $btn;
                })
                ->rawColumns(['action', 'switch'])
                ->make(true);

           
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function incomeList(Request $request)
    {
        $add_incomes = SmAddIncome::with('incomeHeads', 'paymentMethod')->where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
        return Datatables::of($add_incomes)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {

                $date = dateConvert(@$row->created_at);

                return $date;
            })
            ->rawColumns(['date'])
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    (userPermission('student_edit') === true ? '<a class="dropdown-item" href="' . route('student_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('disabled_student') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.disable') . '</a>') : '') .

                    '</div>
                                </div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

    }


    public function emailSmsLogAjax()
    {
        $emailSmsLogs = SmEmailSmsLog::where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->latest()->get();
        return Datatables::of($emailSmsLogs)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                $date = dateConvert(@$row->created_at);
                return $date;
            })
            ->addColumn('send_via', function ($row) {
                if ($row->send_through == "E") {
                    $type = "Email";
                } else {
                    $type = "Sms";
                }
                return $type;
            })
            ->rawColumns(['date'])
            ->make(true);
    }

    public function userLogAjax(Request $request)
    {

        $user_logs = SmUserLog::where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('id', 'desc')
            ->with(array('role' => function ($query) {
                $query->select('id', 'name');
            }))
            ->with(array('user' => function ($query) {
                $query->select('id', 'full_name');
            }))
            ->get();

        return Datatables::of($user_logs)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                $date = dateConvert(@$row->created_at);
                return $date;
            })
            ->rawColumns(['date'])
            ->addColumn('login_time', function ($row) {
                $login_time = $row->created_at->toDayDateTimeString();
                return $login_time;
            })
            ->rawColumns(['login_time'])
            ->make(true);
    }

    public function bankPaymentSlipAjax(Request $request)
    {
        $bank_slips = SmBankPaymentSlip::query();
        if(moduleStatusCheck('University')){
            $bank_slips->where('un_academic_id', getAcademicId());
            if ($request->un_semester_label_id != "") {
                $bank_slips->where('un_semester_label_id', $request->un_semester_label_id);
            }
        }else{
            $bank_slips->where('academic_id', getAcademicId());
            if ($request->class != "") {
                $bank_slips->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $bank_slips->where('section_id', $request->section);
            }
            if ($request->payment_date != "") {
                $date = strtotime($request->payment_date);
                $new_format = date('Y-m-d', $date);
                $bank_slips->where('date', $new_format);
            }
        }
       
        if ($request->approve_status != "") {
            $bank_slips->where('approve_status', $request->approve_status);
        }
        $bank_slips = $bank_slips->with('studentInfo','installmentAssign.installment','feesType')
        ->where('school_id',Auth::user()->school_id)
        ->where('approve_status',0)
        ->orderBy('id', 'desc')
        ->get();

        return Datatables::of($bank_slips)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                $date = dateConvert(@$row->created_at);
                return $date;
            })
            ->rawColumns(['date'])
            ->addColumn('status', function ($row) {
                if ($row->approve_status == 0) {
                    $btn = '<button class="primary-btn small bg-warning text-white border-0">' . app('translator')->get('common.pending') . '</button>';
                } elseif ($row->approve_status == 1) {
                    $btn = '<button class="primary-btn small bg-success text-white border-0  tr-bg">' . app('translator')->get('common.approved') . '</button>';
                } elseif ($row->approve_status == 2) {
                    $btn = '<button class="primary-btn small bg-danger text-white border-0  tr-bg">' . app('translator')->get('common.rejected') . '</button>';
                }
                return $btn;
            })
            ->addColumn('p_amount' , function ($row){
                return generalSetting()->currency_symbol.' '.$row->amount ;
            })
            ->addColumn('slip', function ($row) {
                if ((!empty($row->slip))) {
                    $btn = '<a class="text-color" data-toggle="modal" data-target="#showCertificateModal(' . $row->id . ');" href="#">' . app('translator')->get('common.approve') . '</a>';
                } else {
                    $btn = "";
                }
                return $btn;
            })
            ->addColumn('action', function ($row) {
                if ($row->approve_status == 0) {
                    $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="enableId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#enableStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.approve') . '</a>' .
                        '<a onclick="rejectPayment(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-id="' . $row->id . '"  >' . app('translator')->get('common.reject') . '</a>' .
                        '</div>
                                </div>';
                } elseif ($row->approve_status == 1) {
                    $btn = '<div class="dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">' . app('translator')->get('common.approved') . '</a>' .
                        '</div>
                                </div>';
                } elseif ($row->approve_status == 2) {
                    $btn = '<div class="dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                <div class="dropdown-menu dropdown-menu-right">
                                        <a onclick="viewReason(' . $row->id . ');" class="dropdown-item ' . "reason" . $row->id . '" href="#" data-reason="' . $row->reason . '"  >' . app('translator')->get('common.view') . '</a>' .
                        '</div>
                                </div>';
                }

                return $btn;
            })
            ->rawColumns(['status', 'action', 'slip'])
            ->make(true);

    }


    public function assignmentList()
    {

        $user = Auth()->user();

        if (teacherAccess()) {
            SmNotification::where('user_id', $user->id)->where('role_id', 1)->update(['is_read' => 1]);
        }

        if (!teacherAccess()) {
            $uploadContents = SmTeacherUploadContent::where('content_type', 'as')
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', Auth::user()->school_id)
                            ->whereNullLms()
                            ->get();
        } else {
            $uploadContents = SmTeacherUploadContent::where(function ($q) {
                $q->where('created_by', Auth::user()->id)->orWhere('available_for_admin', 1);
            })->where('content_type', 'as')->whereNullLms()->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();
        }
     
        return Datatables::of($uploadContents)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {

                $date = dateConvert(@$row->created_at);

                return $date;
            })
            ->addColumn('type', function ($row) {
                if ($row->content_type == 'as') {
                    $type = "assignment";
                }
                return __('study.' . $type);

            })
            ->addColumn('avaiable', function ($row) {
                $avaiable = '';
                if ($row->available_for_admin == 1) {
                    $avaiable .= app('translator')->get('study.all_admins') .', ';
                }
                if ($row->available_for_all_classes == 1) {
                    $avaiable .= app('translator')->get('study.all_classes_student').', ';
                }
                if ($row->classes != "" && $row->sections != "") {
                    $avaiable .= (app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name).', ';
                }

                if ($row->classes != "" && $row->section == null) {
                    $avaiable .= (app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections')) .', ';
                }

                if(moduleStatusCheck('University')){
                    $avaiable .= app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name .'-' . @$row->undepartment->name . ')';
                }

                return $avaiable;

            })
            ->addColumn('class_sections', function ($row) {
                if(moduleStatusCheck('University')){
                    $semLabel =  $row->semesterLabel->name ;
                    $academ = $row->unAcademic->name;
                    return $semLabel . '(' .$academ. ')';
                }else{
                    if (($row->class != "") && ($row->section != "")) {
                        $classes = $row->classes->class_name;
                        $sections = $row->sections->section_name;
                        return $classes . '(' . $sections . ')';
                    } elseif (($row->class != "") && ($row->section == null)) {
                        $classes = $row->classes->class_name;
                        $nullsections = app('translator')->get('common.all_sections');
                        return $classes . '(' . $nullsections . ')';
                    } elseif ($row->section != "") {
                        return $sections = $row->sections->section_name;
                    } elseif ($row->class != "") {
                        return $classes = $row->classes->class_name;
                    }

                }
            })
            ->rawColumns(['date'])
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a data-modal-size="modal-lg" title="' . __('study.view_content_details') . '" class="dropdown-item modalLink" href="' . route('upload-content-view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    (userPermission('assignment-list-edit') === true ? '<a class="dropdown-item" href="' . route('upload-content-edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('alesson-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteAssignMent(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteApplyLeaveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .


                    '</div>
                                </div>';

                return $btn;
            })
            ->rawColumns(['action', 'date'])
            ->make(true);
    }


    public function leaveDefineList()
    {

        $leave_defines = SmLeaveDefine::with('role', 'user','leaveType')->where('active_status', 1)
            ->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId())->get();

        return $data = Datatables::of($leave_defines)
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                        <div class="dropdown-menu dropdown-menu-right">'
                    . (userPermission('leave-define-edit') === true ? '<a class="dropdown-item" href="' . route('leave-define-edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('leave-define-edit') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="addLeaveDay(' . $row->id . ');"  class="dropdown-item ' . "reason" . $row->id . '" href="#" data-toggle="modal" data-target="#addLeaveDayModal" data-total_days="' . $row->days . '"  >' . app('translator')->get('common.add_days') . '</a>') : '') .

                    (userPermission('leave-define-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteLeaveDefine(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteLeaveDefineModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .


                    '</div>
                                    </div>';

                return $btn;
            })
            ->rawColumns(['action', 'userName'])
            ->make(true);
           
    }


    public function syllabusList()
    {
        try {
            if (!teacherAccess()) {
                $uploadContents = SmTeacherUploadContent::where('content_type', 'sy')
                    ->whereNullLms()
                    ->where('academic_id', getAcademicId())
                    ->where('school_id', Auth::user()->school_id)->get();
            } else {
                $uploadContents = SmTeacherUploadContent::where(function ($q) {
                    $q->where('created_by', Auth::user()->id)->orWhere('available_for_admin', 1);
                })->where('content_type', 'sy')
                ->whereNullLms()
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();
            }
            // return  $uploadContents;
            return Datatables::of($uploadContents)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {

                    $date = dateConvert(@$row->created_at);

                    return $date;
                })
                ->addColumn('type', function ($row) {
                    if ($row->content_type == 'as') {
                        $type = "assignment";
                    } elseif ($row->content_type == 'st') {
                        $type = "study_material";
                    } elseif ($row->content_type == 'sy') {
                        $type = "syllabus";
                    } else {
                        $type = "others";
                    }

                    return __('study.' . $type);

                })
                ->addColumn('avaiable', function ($row) {
                    $avaiable = '';
                    if ($row->available_for_admin == 1) {
                        $avaiable .= app('translator')->get('study.all_admins') .', ';
                    }
                    if ($row->available_for_all_classes == 1) {
                        $avaiable .= app('translator')->get('study.all_classes_student').', ';
                    }
                    if ($row->classes != "" && $row->sections != "") {
                        $avaiable .= (app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name).', ';
                    }

                    if ($row->classes != "" && $row->section == null) {
                        $avaiable .= (app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections')) .', ';
                    }

                    if(moduleStatusCheck('University')){
                        $avaiable .= app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name .'-' . @$row->undepartment->name . ')';
                    }

                    return $avaiable;


                })
                ->addColumn('class_sections', function ($row) {

                    if(moduleStatusCheck('University')){
                        $semLabel =  $row->semesterLabel->name ;
                        $academ = $row->unAcademic->name;
                        return $semLabel . '(' .$academ. ')';
                    }else{
                        if (($row->class != "") && ($row->section != "")) {
                            $classes = $row->classes->class_name;
                            $sections = $row->sections->section_name;
                            return $classes . '(' . $sections . ')';
                        } elseif (($row->class != "") && ($row->section == null)) {
                            $classes = $row->classes->class_name;
                            $nullsections = app('translator')->get('study.all_sections');
                            return $classes . '(' . $nullsections . ')';
                        } elseif ($row->section != "") {
                            return $sections = $row->sections->section_name;
                        } elseif ($row->class != "") {
                            return $classes = $row->classes->class_name;;
                        }
                }
                })
                ->rawColumns(['date'])
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a data-modal-size="modal-lg" title="' . __('study.view_content_details') . '" class="dropdown-item modalLink" href="' . route('upload-content-view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                        (userPermission('syllabus-list-edit') === true ? '<a class="dropdown-item" href="' . route('upload-content-edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                        (userPermission('syllabus-list-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                            '<a onclick="deleteAssignMent(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteApplyLeaveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .


                        '</div>
                                </div>';

                    return $btn;
                })
                ->rawColumns(['action', 'date'])
                ->make(true);

        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function complaintDetailsDatatable(Request $request){
        if ($request->ajax()) {
            $complaints = SmComplaint::with('complaintType','complaintSource')->get();
            return Datatables::of($complaints)
            ->addIndexColumn()
            ->addColumn('c_date', function ($row) {
                return dateConvert(@$row->date);
            })
            ->addColumn('complaint_type', function ($row) {
               return  @$row->complaintType->name ;
            })
            ->addColumn('complaint_source', function ($row) {
                return  @$row->complaintSource->name ;
             })
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                <div class="dropdown-menu dropdown-menu-right">'
                                    .(userPermission('complaint_show') === true ? '<a class="dropdown-item modalLink" data-modal-size="large-modal" title="'. app('translator')->get('admin.complaint_details') .'" href="' . route('complaint_show', [$row->id]) . '">' . app('translator')->get('admin.complaint_details') . '</a>' : '') .
                                    (userPermission('complaint_edit') === true ? '<a class="dropdown-item " href="' . route('complaint_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .
                                    (userPermission('complaint_delete') === true ? '<a class="dropdown-item" data-toggle="modal" onclick="deleteComplaint(' . $row->id . ');"  href="#">' . app('translator')->get('common.delete') . '</a>' : '') .

                                '</div>
                        </div>';

                return $btn;
            })
            ->rawColumns(['action', 'complaint_type','complaint_source','c_date'])
            ->make(true);
            return view('backEnd.admin.complaint');
        }
    }


    public function unAssignStudentList(Request $request){
        if ($request->ajax()) {
            $all_students = SmStudent::with('parents','gender','category')->wheredoesnthave('studentRecords')->where('school_id', Auth::user()->school_id)->where('academic_id',getAcademicId())->get();
            return Datatables::of($all_students)
                ->addIndexColumn()
                ->addColumn('dob', function ($row) {
                    $dob = dateConvert(@$row->date_of_birth);
                    return $dob;
                })
                ->addColumn('full_name', function ($row) {
                    $full_name_link = '<a target="_blank" href="'. route('student_view', [$row->id]) . '">' . $row->first_name .' '. $row->last_name . '</a>';
                    return $full_name_link;
                })
                ->addColumn('mobile', function ($row) {
                    $mobile = '<a href="tel:'.$row->mobile.'">' .$row->mobile. '</a>';
                    return $mobile;
                })
                ->addColumn('action', function ($row) {
                    $langName = (moduleStatusCheck('University')) ? app('translator')->get('university::un.assign_faculty_department') : app('translator')->get('student.assign_class') ;
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('student.assign-class') === true ? '<a class="dropdown-item" target="_blank" href="' . route('student.assign-class', [$row->id]) . '">' . $langName . '</a>' :'')

                        .((userPermission('student.assign-class') === true && moduleStatusCheck('University')) ?
                        '<a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id,'assign_subject']) . '">' .  app('translator')->get('university::un.assign_subject') . '</a>' :'')

                        .'<a class="dropdown-item" target="_blank" href="' . route('student_view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                        (userPermission('student_edit') === true ? '<a class="dropdown-item" href="' . route('student_edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                        (userPermission('disabled_student') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                            '<a onclick="deleteId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.disable') . '</a>') : '') .

                        '</div>
                                </div>';

                    return $btn;
                })
                ->rawColumns(['action','full_name', 'mobile', 'dob','full_name', 'mobile', 'dob'])
                ->make(true);
        }
    }

    public function disableStudentList(Request $request){

        if ($request->ajax()) {

            $student_ids = StudentRecord::when($request->class_id, function ($query) use ($request) {
                                $query->where('class_id', $request->class_id);
                            })
                            ->when($request->section_id, function ($query) use ($request) {
                                $query->where('section_id', $request->section_id);
                            })
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', Auth::user()->school_id)
                            ->pluck('student_id')->unique();

          $students =  SmStudent::query();
          $students->whereIn('id',$student_ids);
          $students->when($request->name, function ($query) use ($request) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
          });
          $students->when($request->admission_no, function ($query) use ($request) {
            $query->where('admission_no', 'like', '%' . $request->admission_no . '%');
          });
          $all_students = $students->with('studentRecords.class','parents','gender','category')->where('active_status', 0)->where('school_id', Auth::user()->school_id)->get();


          return Datatables::of($all_students)
          ->addIndexColumn()
          ->addColumn('dob', function ($row) {
              $dob = dateConvert(@$row->date_of_birth);
              return $dob;
          })
         

          ->addColumn('full_name', function ($row) {
              $full_name_link = '<a target="_blank" href="'. route('student_view', [$row->id]) . '">' . $row->first_name .' '. $row->last_name . '</a>';
              return $full_name_link;
          })
          
          ->addColumn('mobile', function ($row) {
              $mobile = '<a href="tel:'.$row->mobile.'">' .$row->mobile. '</a>';
              return $mobile;
          })


          ->addColumn('semester_label', function ($row) use ($request) {
              $semester_label=[];
              foreach ($row->studentRecords as $label) {
                  if (moduleStatusCheck('University')) {
                      $semester_label[] = $label->unSemesterLabel->name;
                  }
              }
              return $semester_label;
          })

          ->addColumn('class_sec', function ($row) use ($request) {
              $class_sec=[];
              foreach ($row->studentRecords as $classSec) {
                  if (moduleStatusCheck('University')) {
                      $class_sec[] = $classSec->unFaculty->name.'('. $classSec->unDepartment->name .')';
                  } else {
                      $class_sec[] = $classSec->class->class_name.'('. $classSec->section->section_name .')';
                  }
              }

              return implode(', ', $class_sec);
          })

          ->addColumn('action', function ($row) {
              $langName = (moduleStatusCheck('University')) ? app('translator')->get('university::un.assign_faculty_department') : app('translator')->get('student.assign_class') ;
              $btn = '<div class="dropdown CRM_dropdown">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                              <div class="dropdown-menu dropdown-menu-right">'

                  .(userPermission('disable_student_delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.delete') . '</a></span>' :
                      '<a onclick="deleteId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .

                      (userPermission('disable_student_delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.delete') . '</a></span>' :
                      '<a onclick="enableId(' . $row->id . ');" class="dropdown-item" href="#" data-toggle="modal" data-target="#enableStudentModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.enable') . '</a>') : '') .

                  '</div>
                          </div>';

              return $btn;
          })
          ->rawColumns(['action','full_name', 'mobile', 'dob','class_sec','full_name', 'mobile', 'dob','class_sec'])
          ->make(true);
        }
    }

    public function uploadContentListDatatable(Request $request){

        if( $request->ajax()){
            $user = Auth()->user();
            if (teacherAccess()) {
                SmNotification::where('user_id', $user->id)->where('role_id', 1)->update(['is_read' => 1]);
            }
            if (!teacherAccess()) {
                $uploadContents = SmTeacherUploadContent::where('academic_id', getAcademicId())
                                ->where('school_id', Auth::user()->school_id)
                                ->whereNullLms()
                                ->latest()->get();
            } else {
                $uploadContents = SmTeacherUploadContent::where(function ($q) {
                    $q->where('created_by', Auth::user()->id);
                })->whereNullLms()->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->latest()->get();
            }

           
         
            return Datatables::of($uploadContents)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return dateConvert(@$row->created_at);
            })
            ->addColumn('type', function ($row) {
                if ($row->content_type == 'as') {
                    $type = "assignment";
                } elseif ($row->content_type == 'st') {
                    $type = "study_material";
                } elseif ($row->content_type == 'sy') {
                    $type = "syllabus";
                } else {
                    $type = "other";
                }

                return __('study.' . $type);

            })
            ->addColumn('avaiable', function ($row) {
                if ($row->available_for_admin == 1) {
                    $avaiable = app('translator')->get('study.all_admins');
                } elseif ($row->available_for_all_classes == 1) {
                    $avaiable = app('translator')->get('study.all_classes_student');
                } elseif ($row->classes != "" && $row->sections != "") {
                    $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name;
                } elseif ($row->classes != "" && $row->section == null) {
                    $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections');
                }else{
                    return app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name .'-' . @$row->undepartment->name . ')';
                }

                return $avaiable;

            })
            ->addColumn('class_sections', function ($row) {
                if(moduleStatusCheck('University')){
                    $semLabel =  $row->semesterLabel->name ;
                    $academ = $row->unAcademic->name;
                    return $semLabel . '(' .$academ. ')';
                }else{
                    if (($row->class != "") && ($row->section != "")) {
                        $classes = $row->classes->class_name;
                        $sections = $row->sections->section_name;
                        return $classes . '(' . $sections . ')';
                    } elseif (($row->class != "") && ($row->section == null)) {
                        $classes = $row->classes->class_name;
                        $nullsections = app('translator')->get('common.all_sections');
                        return $classes . '(' . $nullsections . ')';
                    } elseif ($row->section != "") {
                        return $sections = $row->sections->section_name;
                    } elseif ($row->class != "") {
                        return $classes = $row->classes->class_name;
                    }

                }
            })
            ->rawColumns(['date'])
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a data-modal-size="modal-lg" title="' . __('study.view_content_details') . '" class="dropdown-item modalLink" href="' . route('upload-content-view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    (userPermission('upload-content-edit') === true ? '<a class="dropdown-item" href="' . route('upload-content-edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('delete-upload-content') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteUpContent(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteApplyLeaveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .

                    (userPermission('download-content-document') === true && $row->upload_file != "" ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a class="dropdown-item" href="'.url($row->upload_file).'" download >' . app('translator')->get('common.download') .  ' <span class="pl ti-download"></span>'. '</a>') : '') .
                        


                    '</div>
                                </div>';

                return $btn;
            })
            ->rawColumns(['action', 'date'])
            ->make(true);
        }
    }

    public function otherDownloadList()
    {

        $user = Auth()->user();

        if (teacherAccess()) {
            SmNotification::where('user_id', $user->id)->where('role_id', 1)->update(['is_read' => 1]);
        }

        if (!teacherAccess()) {
            $uploadContents = SmTeacherUploadContent::where('content_type', 'ot')
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', Auth::user()->school_id)
                            ->whereNullLms()
                            ->get();
        } else {
            $uploadContents = SmTeacherUploadContent::where(function ($q) {
                $q->where('created_by', Auth::user()->id)->orWhere('available_for_admin', 1);
            })->where('content_type', 'ot')->whereNullLms()->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();
        }
     
        return Datatables::of($uploadContents)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return dateConvert(@$row->created_at);
            })
            ->addColumn('type', function ($row) {
                if ($row->content_type == 'ot') {
                    $type = "other";
                }
                return __('study.' . $type);

            })
            ->addColumn('avaiable', function ($row) {
                if ($row->available_for_admin == 1) {
                    $avaiable = app('translator')->get('study.all_admins');
                } elseif ($row->available_for_all_classes == 1) {
                    $avaiable = app('translator')->get('study.all_classes_student');
                } elseif ($row->classes != "" && $row->sections != "") {
                    $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name;
                } elseif ($row->classes != "" && $row->section == null) {
                    $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections');
                }else{
                    return app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name .'-' . @$row->undepartment->name . ')';
                }

                return $avaiable;

            })
            ->addColumn('class_sections', function ($row) {
                if(moduleStatusCheck('University')){
                    $semLabel =  $row->semesterLabel->name ;
                    $academ = $row->unAcademic->name;
                    return $semLabel . '(' .$academ. ')';
                }else{
                    if (($row->class != "") && ($row->section != "")) {
                        $classes = $row->classes->class_name;
                        $sections = $row->sections->section_name;
                        return $classes . '(' . $sections . ')';
                    } elseif (($row->class != "") && ($row->section == null)) {
                        $classes = $row->classes->class_name;
                        $nullsections = app('translator')->get('common.all_sections');
                        return $classes . '(' . $nullsections . ')';
                    } elseif ($row->section != "") {
                        return $sections = $row->sections->section_name;
                    } elseif ($row->class != "") {
                        return $classes = $row->classes->class_name;
                    }

                }
            })
            ->rawColumns(['date'])
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a data-modal-size="modal-lg" title="' . __('study.view_content_details') . '" class="dropdown-item modalLink" href="' . route('upload-content-view', [$row->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    (userPermission('other-download-list-edit') === true ? '<a class="dropdown-item" href="' . route('upload-content-edit', [$row->id]) . '">' . app('translator')->get('common.edit') . '</a>' : '') .

                    (userPermission('other-download-list-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteOtherDownload(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .


                    '</div>
                                </div>';

                return $btn;
            })
            ->rawColumns(['action', 'date'])
            ->make(true);
    }

    public function ajaxFeesPayment(Request $request){
        if($request->ajax()){
            $date_from = date('Y-m-d', strtotime($request->date_from));
            $date_to = date('Y-m-d', strtotime($request->date_to));
            $fees_payments = SmFeesPayment::query();
            $fees_payments = $fees_payments->when(directFees(), function($q){
                $q->whereNotNull('installment_payment_id');
            });

            if(moduleStatusCheck('University')){
                $fees_payments->when($request->un_semester_label_id, function ($q) use ($request) {
                        $q->whereHas('studentInfo', function ($q) use($request){
                            return $q->where(function($q) use($request) {
                            return $q->where('un_semester_label_id', $request->un_semester_label_id);
                        });
                    });
                });

            }else{
                if($request->class){
                    $fees_payments->whereHas('recordDetail', function ($q) use($request){
                        return $q->where('class_id', $request->class);
                        });
                }
                if($request->section){
                    $fees_payments->whereHas('recordDetail', function ($q) use($request){
                        return $q->where('section_id', $request->section);
                        });
                }
            }
            
            $fees_payments->when($request->keyword, function ($q) use ($request) {
                        $q->whereHas('studentInfo', function ($q) use($request){
                            return $q->where(function($q) use($request) {
                            return $q->where('full_name', 'like', '%' . @$request->keyword . '%')
                            ->orWhere('admission_no', 'like', '%' . @$request->keyword . '%')
                            ->orWhere('roll_no','like',  '%' . @$request->keyword . '%');
                        });
                });
            });
            $fees_payments->when($request->date_from && $request->date_to == null, function ($query) use ($date_from) {
                $query->whereDate('payment_date', '=', $date_from);
            });
            $fees_payments->when($request->date_to && $request->date_from == null, function ($query) use ($date_from, $date_to) {
                $query->whereDate('payment_date', '=', $date_to);
            });
            $fees_payments->when($request->date_from && $request->date_to, function ($query) use ($date_from, $date_to) {
                $query->whereDate('payment_date', '>=', $date_from)->whereDate('payment_date', '<=', $date_to);
            })->where('active_status', 1)->orderby('id', 'DESC')->where('school_id', Auth::user()->school_id);
            if (auth()->user()->role_id != 1 && auth()->user()->role_id != 5) {
                $fees_payments = $fees_payments->where('created_by', auth()->user()->id);
            }
            $fees_payments = $fees_payments->whereHas('recordDetail')->with('feesType','feesInstallment.installment','feesInstallment','recordDetail','installmentPayment','recordDetail.studentDetail','recordDetail.class','recordDetail.section','studentInfo')->get();

            return Datatables::of($fees_payments)
            ->addIndexColumn()
            ->addColumn('invoice', function($row){
                if(moduleStatusCheck('University')){
                    universityFeesInvoice(@$fees_payment->installmentPayment->invoice_no);
                }elseif(directFees()){
                    $invoice_setting = FeesInvoice::where('school_id', auth()->user()->school_id)->first(['prefix','start_form']);
                    return sm_fees_invoice($row->installmentPayment->invoice_no ,$invoice_setting);
                }else{
                  return  $row->id.'/'.$row->fees_type_id ;
                }

            })
            ->addColumn('date', function ($row) {
                return dateConvert(@$row->payment_date);
            })
            ->addColumn('fees_amount', function ($row) {
                return generalSetting()->currency_symbol. ' '.$row->amount;
            })
            ->addColumn('class_sec', function ($row) {
                if(moduleStatusCheck('University')){
                    return $row->recordDetail->semesterLabel->name.'('.$row->recordDetail->unDepartment->name.')';
                }else{
                    return $row->recordDetail->class->class_name.'('.$row->recordDetail->section->section_name.')';
                }
            })
            
            ->addColumn('action', function ($row) {
                $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                            <a data-modal-size="modal-lg" target="_blank" class="dropdown-item" href="' . route('fees_collect_student_wise', [$row->recordDetail->id]) . '">' . app('translator')->get('common.view') . '</a>' .
                    ((userPermission('edit-fees-payment') === true  && $row->assign_id !=null)? '<a class="dropdown-item modalLink" data-modal-size="modal-lg" title="' . __('fees.edit_fees_payment') . '" href="' . route('edit-fees-payment', [$row->id]) . '">' . app('translator')->get('fees.edit_fees') . '</a>' : '') .

                    (! moduleStatusCheck('University') && directFees() == false  ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                        '<a onclick="deleteFeesPayment(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .


                    '</div>
                                </div>';

                return $btn;
            }) 
            ->rawColumns(['action', 'date','invoice','fees_amount'])
            ->make(true);
        }
    }

     
    

    public function ajaxIncomeList(Request $request){

        if($request->ajax()){
            $all_incomes = SmAddIncome::with('paymentMethod','ACHead')->where('academic_id',getAcademicId())->get();
            return Datatables::of($all_incomes)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return   dateConvert(@$row->date);
            })
            ->addColumn('amount', function ($row) {
                return generalSetting()->currency_symbol. ' '.$row->amount;
            })
            ->addColumn('action', function ($row) {
                
                $btn = '<div class="dropdown CRM_dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                <div class="dropdown-menu dropdown-menu-right">'
                    .(userPermission('add_income_edit') === true ? '<a class="dropdown-item" href="' . route('add_income_edit', [$row->id]) . '">' . __('common.edit') . '</a>' :'')

                    .((userPermission('add_income_delete') === true) ?
                    '<a onclick="deleteIncome(' . $row->id . ');"  class="dropdown-item"  href="#">' .  app('translator')->get('common.delete') . '</a>' :'') . 

                    (( $row->file != "") ?
                    '<a   class="dropdown-item"  download href="'.url($row->file).'">' .  app('translator')->get('common.download') . ' <span class="pl ti-download"></span> </a>' :'') .
                    '</div>
                            </div>';

                return $btn;
            })
            ->rawColumns(['action','date'])
            ->make(true);
        }

    }

    public function ajaxExpenseList(Request $request){
            if( $request->ajax()){
                $all_incomes = SmAddExpense::with('expenseHead','ACHead','paymentMethod','account')->get();
                return Datatables::of($all_incomes)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return   dateConvert(@$row->date);
                })
                ->addColumn('amount', function ($row) {
                    return generalSetting()->currency_symbol. ' '.$row->amount;
                })
                ->addColumn('status', function ($row) {
                    
                    
                })
                ->addColumn('action', function ($row) {
                
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('add-expense-edit') === true ? '<a class="dropdown-item" href="' . route('add-expense-edit', [$row->id]) . '">' . __('common.edit') . '</a>' :'')
    
                        .((userPermission('add-expense-delete') === true) ?
                        '<a onclick="deleteExpense(' . $row->id . ');"  class="dropdown-item"  href="#">' .  app('translator')->get('common.delete') . '</a>' :'') . 
    
                        (( $row->file != "") ?
                        '<a   class="dropdown-item"  download href="'.url($row->file).'">' .  app('translator')->get('common.download') . ' <span class="pl ti-download"></span> </a>' :'') .
                        '</div>
                                </div>';
    
                    return $btn;
                })
                ->rawColumns(['status','date','amount','action'])
                ->make(true);
            }
        }

        public function ajaxPendingLeave(Request $request){
            if($request->ajax()){
                if (checkAdmin()) {
                    $apply_leaves = SmLeaveRequest::with('leaveType','leaveDefine','staffs','student')->where([['active_status', 1], ['approve_status', '!=', 'A']])
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('academic_id',getAcademicId())
                                    ->get();
                }elseif(Auth::user()->role_id == 4){
                    $staff = Auth::user()->staff;
                    $class_teacher = SmClassTeacher::where('teacher_id', $staff->id)
                                        ->where('school_id', Auth::user()->school_id)
                                        ->where('academic_id',getAcademicId())
                                        ->first();
                                      
                    if($class_teacher){
                        $leaves = SmLeaveRequest::where([
                            ['active_status', 1], 
                            ['approve_status', '!=', 'A'],
                            ['role_id', '=', 2]
                            ])
                            ->where('school_id', Auth::user()->school_id)
                            ->where('academic_id',getAcademicId())
                            ->first();
                            $smAssignClassTeacher = SmAssignClassTeacher::find($class_teacher->assign_class_teacher_id);  
                            if($leaves){
                                $apply_leaves = SmLeaveRequest::with('leaveType')->with(array('student' => function($query)use($smAssignClassTeacher) {
                                    $query->where('class_id', $smAssignClassTeacher->class_id)->where('section_id',  $smAssignClassTeacher->section_id);
                                }))->where([
                                    ['active_status', 1], 
                                    ['approve_status', '!=', 'A'],
                                    ['role_id', '=', 2]
                                    ])->where('school_id', Auth::user()->school_id)
                                ->where('academic_id',getAcademicId())
                                ->get();
                            }
                    }else{
                        $apply_leaves = SmLeaveRequest::with('leaveDefine')->where([
                            ['active_status', 1], 
                            ['approve_status', '!=', 'A'],
                            ['staff_id', '=', auth()->user()->staff->id],
                            ['role_id', '!=', 2]
                            ])
                            ->where('school_id', Auth::user()->school_id)
                            ->where('academic_id',getAcademicId())
                            ->get();
                    }
                }elseif(auth()->user()->role_id==1){
                    $apply_leaves = SmLeaveRequest::with('leaveDefine','leaveType','staffs','student','leaveType')->where([['active_status', 1], ['approve_status', '!=', 'A']])
                    ->where('school_id', Auth::user()->school_id)
                    ->where('academic_id',getAcademicId())
                    ->get();
                }else{
                    $apply_leaves = SmLeaveRequest::with('leaveType','leaveDefine','staffs','student')->where([['active_status', 1], ['approve_status', '!=', 'A']])
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where([
                                        ['active_status', 1], 
                                        ['approve_status', '!=', 'A'],
                                        ['staff_id', '=', auth()->user()->staff->id],
                                        ['role_id', '!=', 2]
                                        ])
                                    ->where('academic_id',getAcademicId())
                                    ->get();
                }

                return $data = Datatables::of($apply_leaves)
                ->addIndexColumn()
                ->addColumn('f_date', function ($row) {
                    return   dateConvert(@$row->leave_from);
                })
                ->addColumn('t_date', function ($row) {
                    return   dateConvert(@$row->leave_to);
                })
                ->addColumn('a_date', function ($row) {
                    return   dateConvert(@$row->apply_date);
                })
                
                ->addColumn('full_name', function ($row) {
                    if($row->role_id == 2){
                        return @$row->student->full_name;
                    }else{
                        return  @$row->staffs->full_name;
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->approve_status == 'P') {
                    $btn = '<button class="primary-btn bg-warning text-white border-0 small tr-bg">' . app('translator')->get('common.pending') . '</button>';
                        } elseif ($row->approve_status == 'A') {
                            $btn = '<button class="primary-btn bg-success text-white border-0 small tr-bg">' . app('translator')->get('common.approved') . '</button>';
                        } elseif ($row->approve_status == 'C') {
                            $btn = '<button class="primary-btn small bg-danger text-white border-0">' . app('translator')->get('common.cancelled') . '</button>';
                        }
                    return $btn;
                    
                })
                ->addColumn('action', function ($row) {
                
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('approve-leave-edit') === true ? '<a class="dropdown-item modalLink" data-modal-size="modal-lg" title="View/Edit Leave Details" href="' . route('view-leave-details-approve', [@$row->id]) . '">' . __('common.view') . '</a>' :'')
    
                        .((userPermission('approve-leave-delete') === true) ?
                        '<a onclick="deleteApplyLeave(' . $row->id . ');"  class="dropdown-item"  href="#">' .  app('translator')->get('common.delete') . '</a>' :'') . 

                        '</div>
                                </div>';
    
                    return $btn;
                })
                ->rawColumns(['status','action','f_date','t_date','a_date','full_name'])
                ->make(true);
                
            }
        
        }
        public function ajaxApproveLeave(Request $request){
            if($request->ajax()){
                if (Auth::user()->role_id == 1) {
                    $apply_leaves = SmLeaveRequest::with('leaveType','leaveDefine','staffs','student')->where([['active_status', 1], ['approve_status', '!=', 'P']])->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->get();
                } elseif(auth()->user()->staff) {
                    $apply_leaves = SmLeaveRequest::with('leaveType','leaveDefine','staffs','student')->where([['active_status', 1], ['approve_status', '!=', 'P'], ['staff_id', '=', auth()->user()->staff->id]])->where('academic_id', getAcademicId())->get();
                }

                return Datatables::of($apply_leaves)
                ->addIndexColumn()
                ->addColumn('f_date', function ($row) {
                    return   dateConvert(@$row->leave_from);
                })
                ->addColumn('t_date', function ($row) {
                    return   dateConvert(@$row->leave_to);
                })
                ->addColumn('a_date', function ($row) {
                    return   dateConvert(@$row->apply_date);
                })
                
                ->addColumn('full_name', function ($row) {
                    if($row->role_id == 2){
                        return @$row->student->full_name;
                    }else{
                        @$row->staffs->full_name;
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->approve_status == 'P') {
                    $btn = '<button class="primary-btn bg-warning text-white border-0 small tr-bg">' . app('translator')->get('common.pending') . '</button>';
                        } elseif ($row->approve_status == 'A') {
                            $btn = '<button class="primary-btn bg-success text-white border-0 small tr-bg">' . app('translator')->get('common.approved') . '</button>';
                        } elseif ($row->approve_status == 'C') {
                            $btn = '<button class="primary-btn small bg-danger text-white border-0">' . app('translator')->get('common.cancelled') . '</button>';
                        }
                    return $btn;
                    
                })
                ->addColumn('action', function ($row) {
                
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('approve-leave-edit') === true ? '<a class="dropdown-item modalLink" data-modal-size="modal-lg" title="View/Edit Leave Details" href="' . route('view-leave-details-approve', [@$row->id]) . '">' . __('common.view') . '</a>' :'')
    
                        .((userPermission('approve-leave-delete') === true) ?
                        '<a onclick="deleteApplyLeave(' . $row->id . ');"  class="dropdown-item"  href="#">' .  app('translator')->get('common.delete') . '</a>' :'') . 

                        '</div>
                                </div>';
    
                    return $btn;
                })
                ->rawColumns(['status','action','f_date','t_date','a_date','full_name'])
                ->make(true);
            }
        
        }


        public function homeworkListAjax(Request $request){

            if( $request->ajax()){
                    $all_homeworks = SmHomework::query();
                    $all_homeworks->with('classes','sections','subjects','users');
                    $all_homeworks->when($request->class, function ($query) use ($request) {
                        $query->where('class_id', $request->class);
                    });
                    $all_homeworks->when($request->subject, function ($query) use ($request) {
                        $query->where('subject_id', $request->subject);
                    });
                    $all_homeworks->when($request->section, function ($query) use ($request) {
                        $query->where('section_id', $request->section);
                    });

                    if(moduleStatusCheck('University')){
                        $all_homeworks->with('semesterLabel','unSession','unSemester');
                    }
                    $all_homeworks->where('school_id',Auth::user()->school_id)->orderby('id','DESC')
                        ->where('academic_id', getAcademicId());
                    if (teacherAccess()) {
                        $homeworkLists = $all_homeworks->where('created_by',Auth::user()->id)->get();
                    } else {
                        $homeworkLists = $all_homeworks->get();
                    }

                    return Datatables::of($homeworkLists)
                    ->addIndexColumn()
                    ->addColumn('homework_date', function ($row) {
                        return dateConvert(@$row->homework_date);
                    })
                    ->addColumn('submission_date', function ($row) {
                        return dateConvert(@$row->submission_date);
                    })
                    ->addColumn('evaluation_date', function ($row) {
                        return dateConvert(@$row->evaluation_date);
                    })
                    
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown CRM_dropdown">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                            <div class="dropdown-menu dropdown-menu-right">'

                                            .((userPermission('university.unevaluation-homework') === true && moduleStatusCheck('University')) ? '<a class="dropdown-item"  href="' . route('university.unevaluation-homework',[$row->un_semester_label_id,$row->id]) . '">' .  app('translator')->get('homework.evaluation') . '</a>' :'').
                                            ((userPermission('homework_edit') === true ) ? '<a class="dropdown-item"  href="' . route('homework_edit', [$row->id]) . '">' . __('common.edit') . '</a>' :'') .

                                            ((userPermission('evaluation-homework') === true  && moduleStatusCheck('University') == false) ? '<a class="dropdown-item"  href="' . route('evaluation-homework',[@$row->class_id,@$row->section_id,@$row->id]) . '">' . __('homework.evaluation') . '</a>' :'').
                        
                                            (userPermission('alesson-delete') === true ? (Config::get('app.app_sync') ? '<span  data-toggle="tooltip" title="Disabled For Demo "><a  class="dropdown-item" href="#"  >' . app('translator')->get('common.disable') . '</a></span>' :
                                            '<a onclick="deleteHomeWork(' . $row->id . ');"  class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteApplyLeaveModal" data-id="' . $row->id . '"  >' . app('translator')->get('common.delete') . '</a>') : '') .
                                            '</div>
                                </div>';
        
                        return $btn;
                    })
                    ->rawColumns(['action', 'homework_date','submission_date','evaluation_date'])
                    ->make(true);
            }
        }


        public function bookListAjax(Request $request){
            if($request->ajax()){
                $books = SmBook::leftjoin('sm_subjects', 'sm_books.book_subject_id', '=', 'sm_subjects.id')
                    ->leftjoin('sm_book_categories', 'sm_books.book_category_id', '=', 'sm_book_categories.id')
                    ->select('sm_books.*', 'sm_subjects.subject_name', 'sm_book_categories.category_name')
                    ->orderby('sm_books.id', 'DESC')
                    ->get();

                return Datatables::of($books)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('edit-book') === true ? '<a class="dropdown-item" href="' . route('edit-book', [@$row->id]) . '">' . __('common.edit') . '</a>' :'')
    
                        .((userPermission('delete-book-view') === true) ?
                        '<a  class="dropdown-item deleteUrl" data-modal-size="modal-md" title="'. __('library.delete_book') .'"  href="'.route('delete-book-view', [@$row->id]).'">' .  app('translator')->get('common.delete') . '</a>' :'') . 

                        '</div>
                                </div>';
    
                    return $btn;
                })
                ->rawColumns(['status','action','f_date','t_date','a_date','full_name'])
                ->make(true);
            }
        }


        public function allIssuedBookAjax(Request $request){

            if($request->ajax()){

                $all_issue_books = SmBookIssue::query();

                $all_issue_books->when($request->book_id, function ($query) use ($request) {
                    $query->where('book_id', $request->book_id);
                });

                if ($request->book_number) {
                    $issueBooks = $all_issue_books->whereHas('books', function ($query) use ($request) {
                        $query->where('id', $request->book_id)->where('book_number', $request->book_number);
                    });
                }

                if ($request->subject_id) {
                    $all_issue_books->whereHas('books', function ($query) use ($request) {
                        $query->where('id', $request->book_id)->where('book_subject_id', $request->subject_id);
                    });
                }
                $all_issue_books->join('sm_books', 'sm_book_issues.book_id', '=', 'sm_books.id')
                ->join('sm_library_members', 'sm_book_issues.member_id', '=', 'sm_library_members.student_staff_id')
                ->join('sm_subjects', 'sm_subjects.id', '=', 'sm_books.book_subject_id')
                ->join('users', 'users.id', '=', 'sm_library_members.student_staff_id')
                ->where('sm_books.school_id', Auth::user()->school_id);

                $issueBooks= $all_issue_books->get();

                return Datatables::of($issueBooks)
                ->addIndexColumn()
                ->addColumn('issue_status',  function($row){
                    $now=new DateTime($row->given_date);
                    $end=new DateTime($row->due_date);
                    if($row->issue_status == 'I'){
                        if($end<$now){
                            $btn = '<button class="primary-btn small bg-danger text-white border-0">' .__('library.expired'). '</button>' ;
                          }else{
                            $btn = '<button class="primary-btn small bg-success text-white border-0">' .__('library.issued'). '</button>' ;
                          }
                    }else{
                        $btn = '<button class="primary-btn small bg-success text-white border-0">'. __('library.returned').'</button>' ;
                    }
                    return $btn;
                    
                })
                ->addColumn('given_date', function($row){
                    return dateConvert($row->given_date );
                })
                ->addColumn('due_date', function($row){
                    return dateConvert($row->due_date );
                })
               
                ->rawColumns(['issue_status','given_date','due_date'])
                ->make(true);
            }

            }



        public function itemsListAjax(Request $request){

            if($request->ajax()){
                $items = SmItem::with('category')->where('school_id',Auth::user()->school_id)->get();
                return Datatables::of($items)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
            
                    $btn = '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">'
                        .(userPermission('item-list-edit') === true ? '<a class="dropdown-item" href="' . route('item-list-edit',$row->id) . '">' . __('common.edit') . '</a>' :'')
        
                        .((userPermission('delete-item-view') === true) ?
                        '<a  class="dropdown-item deleteUrl" data-modal-size="modal-md" title="'. __('inventory.delete_item') .'"  href="'.route('delete-item-view',@$row->id).'">' .  app('translator')->get('common.delete') . '</a>' :'') . 
        
                        '</div>
                                </div>';
        
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        }


        public function itemReceiveListAjax(Request $request){
            if($request->ajax()){
                $allItemReceiveLists = SmItemReceive::with('suppliers','paymentMethodName','bankName','itemPayments')
                ->get();

                return Datatables::of($allItemReceiveLists)
                ->addIndexColumn()
                ->addColumn('receive_date', function ($row) {
                        return dateConvert($row->receive_date);
                })
                
                ->addColumn('grand_total', function ($row) {
                    return number_format( (float) $row->grand_total, 2, '.', '');
                })
                ->addColumn('total_paid', function ($row) {
                    return number_format( (float) $row->total_paid, 2, '.', '');
                })
                ->addColumn('total_due', function ($row) {
                    return number_format( (float) $row->total_due, 2, '.', '');
                })
                ->addColumn('status', function ($row) {
                    if($row->paid_status == 'P'){
                       $btn = '<button class="primary-btn small bg-success text-white border-0">'.__('inventory.paid').'</button>' ;
                    }                  
                    elseif($row->paid_status == 'PP'){
                       $btn = '<button class="primary-btn small bg-warning text-white border-0">'.__('inventory.partial').'</button>' ;
                    }
                    elseif($row->paid_status == 'U'){
                       $btn = '<button class="primary-btn small bg-danger text-white border-0">'.__('inventory.unpaid').'</button>' ;
                    }
                    else{
                       $btn = '<button class="primary-btn small bg-info text-white border-0">'.__('inventory.refund').'</button>' ;
                    }
                    
                    return $btn;
                                       
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn .= '<div class="dropdown CRM_dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' . app('translator')->get('common.select') . '</button>
                                    <div class="dropdown-menu dropdown-menu-right">';
                    if($row->paid_status != 'R' ){
                        if(! $row->item_payments){
                            $btn.= (userPermission('edit-item-receive') === true ? '<a class="dropdown-item" href="' . route('edit-item-receive',$row->id) . '">' . __('common.edit') . '</a>' :'') ; 
                        }
                        if($row->total_due > 0){
                            $btn.= '<a class="dropdown-item modalLink" title="Add Payment" data-modal-size="modal-md" href="'.route('add-payment',$row->id).'">'.__('common.add_payment').'</a>' ;
                        }
                    }
                    if($row->paid_status != 'P'){
                        $btn.= (userPermission('view-receive-payments') === true ? '<a class="dropdown-item modalLink" data-modal-size="modal-lg" title="View Payments" href="' . route('view-receive-payments',$row->id) . '">' . __('common.view_payment') . '</a>' :'') ; 
                    }
                    if($row->paid_status != 'R'){
                        if($row->total_paid == 0){
                            $btn.= userPermission("delete-item-receive-view") ?  '<a class="dropdown-item deleteUrl" data-modal-size="modal-md" title="'.__('inventory.delete_item_receive').'" href="'.route('delete-item-receive-view', $row->id).'">'.__('common.delete').'</a>' : '' ;
                         }
                         if($row->total_paid>0){
                            $btn.= '<a class="dropdown-item deleteUrl" data-modal-size="modal-md" title="Cancel Item Receive" href="'.route('cancel-item-receive-view', $row->id).'">'.__('common.cancel').'</a>' ;
                         }
                    }
                         
                    $btn.= '</div>  </div>';
                    return $btn;
                })
                ->rawColumns(['action','receive_date','grand_total','total_paid','total_due','status'])
                ->make(true);

            }
        }


        public function studentTransportReportAjax(Request $request){
            if($request->ajax()){
                $students = SmStudent::with('studentRecord.class','studentRecord.section','parents','route','vehicle','drivers')->whereHas('vehicle')->get();
                return Datatables::of($students)
                ->addIndexColumn()
                ->addColumn('class_section', function ($row) {
                    return @$row->student_record->class->class_name .'('.@$row->student_record->section->section_name.')';
                })
                ->rawColumns(['class_section'])
                ->make(true);
            }
        }
    }

        
       




    



            
        
        

