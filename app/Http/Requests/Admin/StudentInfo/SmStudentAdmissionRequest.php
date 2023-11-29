<?php

namespace App\Http\Requests\Admin\StudentInfo;

use App\User;
use App\SmStudent;
use Illuminate\Validation\Rule;
use App\Models\SmStudentRegistrationField;
use App\Models\StudentRecord;
use Illuminate\Foundation\Http\FormRequest;

class SmStudentAdmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxFileSize =generalSetting()->file_size*1024;
        $student = null;
        $class_ids = [$this->class];
        $section_ids = [$this->section];
   
        if ($this->id) {
            $student = SmStudent::with('parents', 'studentRecords')->findOrFail($this->id);
            $class_ids = $student->studentRecords->pluck('class_id')->toArray();
            $section_ids = $student->studentRecords->pluck('section_id')->toArray();
        }


        $school_id = auth()->user()->school_id;
        $academic_id = getAcademicId();


        $field = SmStudentRegistrationField::where('school_id', $school_id)
            ->when(auth()->user()->role_id == 2, function ($query) {
                $query->where('student_edit', 1)->where('is_required', 1);
            })
            ->when(auth()->user()->role_id == 3, function ($query) {
                $query->where('parent_edit', 1)->where('is_required', 1);
            })
            ->when(!in_array(auth()->user()->role_id, [2,3]), function ($query) {
                $query->where('is_required', 1);
            })
            ->pluck('field_name')
            ->toArray();
        $user_role_id = null;
        if ($this->filled('phone_number') || $this->filled('email_address')) {
            $user = User::when($this->filled('phone_number') && !$this->email_address, function ($q) {
                $q->where('phone_number', $this->phone_number)->orWhere('username', $this->phone_number);
            })
            ->when($this->filled('email_address') && !$this->phone_number, function ($q) {
                $q->where('email', $this->email_address)->orWhere('username', $this->email_address);
            })
            ->when($this->filled('email_address') && $this->filled('phone_number'), function ($q) {
                $q->where('phone_number', $this->phone_number);
            })
            ->first();
            if ($user) {
                $user_role_id = $user->role_id==2 ? $user->role_id:null;
            } 
        }

        $rules= [

            'admission_number' => ['integer', Rule::unique('sm_students', 'admission_no')->ignore(optional($student)->id)->where('school_id', $school_id), Rule::requiredIf(function () use ($field) {
                return in_array('admission_number', $field);
            })],
            
            'first_name' => ['max:100', Rule::requiredIf(function () use ($field) {
                return in_array('first_name', $field);
            })],
            'last_name' => [Rule::requiredIf(function () use ($field) {
                return in_array('last_name', $field);
            }),'max:100'],

            'gender' => [Rule::requiredIf(function () use ($field) {
                return in_array('gender', $field);
            })],
            'date_of_birth' => ['before:'. date('Y-m-d'),'after:1900-01-01', Rule::requiredIf(function () use ($field) {
                return in_array('date_of_birth', $field);
            })],
            'blood_group'=>[Rule::requiredIf(function () use ($field) {
                return in_array('blood_group', $field);
            }),'nullable','integer'],
            'religion'=>[Rule::requiredIf(function () use ($field) {
                return in_array('religion', $field);
            }),'nullable','integer'],
            'caste'=>[Rule::requiredIf(function () use ($field) {
                return in_array('caste', $field);
            })],

            'admission_date'=>['before:tomorrow', Rule::requiredIf(function () use ($field) {
                return in_array('admission_date', $field);
            }),'date'],
            'student_category_id'=>[Rule::requiredIf(function () use ($field) {
                return in_array('student_category_id', $field);
            }),'nullable','integer'],
            'student_group_id' => [Rule::requiredIf(function () use ($field) {
                return in_array('student_group_id', $field);
            }),'nullable','integer'],
            'height'=>[Rule::requiredIf(function () use ($field) {
                return in_array('height', $field);
            })],
            'weight'=>[Rule::requiredIf(function () use ($field) {
                return in_array('weight', $field);
            })],
            'photo' => [Rule::requiredIf(function () use ($field) {
                return in_array('photo', $field);
            }),'sometimes','nullable','file','mimes:jpg,jpeg,png', 'max:'.$maxFileSize],
            'fathers_name'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('fathers_name', $field);
            }),'max:100'],
            'fathers_occupation'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('fathers_occupation', $field);
            }),'max:100'],
            'fathers_phone'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('fathers_phone', $field);
            }),'max:100'],
            'fathers_photo'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('fathers_photo', $field);
            }),'sometimes','nullable','file','mimes:jpg,jpeg,png','max:'.$maxFileSize],
            'mothers_name'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('mothers_name', $field);
            }),'max:100'],
            'mothers_occupation'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('mothers_occupation', $field);
            }),'max:100'],
            'mothers_phone'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('mothers_phone', $field);
            }),'max:100'],
            'mothers_photo'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('mothers_photo', $field);
            }),'nullable','mimes:jpg,jpeg,png'],
            'guardians_name' =>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('guardians_name', $field);
            }),'max:100'],
            'relation'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('relation', $field);
            })],

            'guardians_photo' =>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('guardians_photo', $field);
            }), 'sometimes','nullable','file','mimes:jpg,jpeg,png','max:'.$maxFileSize],

            'guardians_occupation'=>[Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('guardians_occupation', $field);
            }), 'max:100'],
            'guardians_address' => [Rule::requiredIf(function () use ($field) {
                return !$this->parent_id && !$this->staff_parent && in_array('guardians_address', $field);
            }),'max:200'],
            'current_address' => [Rule::requiredIf(function () use ($field) {
                return in_array('current_address', $field);
            }),'max:200'],
            'permanent_address' => [Rule::requiredIf(function () use ($field) {
                return in_array('permanent_address', $field);
            }),'max:200'],
            'route'=>[Rule::requiredIf(function () use ($field) {
                return in_array('route', $field);
            }),'nullable','integer'],
            'vehicle' =>[Rule::requiredIf(function () use ($field) {
                return in_array('vehicle', $field);
            }),'nullable','integer'],
            'dormitory_name'=>[Rule::requiredIf(function () use ($field) {
                return in_array('dormitory_name', $field);
            }),'nullable','integer'],
            'room_number' =>[Rule::requiredIf(function () use ($field) {
                return in_array('room_number', $field);
            }),'nullable','integer'],
            'national_id_number'=>[Rule::requiredIf(function () use ($field) {
                return in_array('national_id_number', $field);
            })],
            'local_id_number'=>[Rule::requiredIf(function () use ($field) {
                return in_array('local_id_number', $field);
            })],
            'bank_account_number'=>[Rule::requiredIf(function () use ($field) {
                return in_array('bank_account_number', $field);
            })],
            'bank_name'=>[Rule::requiredIf(function () use ($field) {
                return in_array('bank_name', $field);
            })],
            'previous_school_details'=>[Rule::requiredIf(function () use ($field) {
                return in_array('previous_school_details', $field);
            })],
            'additional_notes'=>[Rule::requiredIf(function () use ($field) {
                return in_array('additional_notes', $field);
            })],
            'ifsc_code'=>[Rule::requiredIf(function () use ($field) {
                return in_array('ifsc_code', $field);
            })],
            'document_file_1' => [Rule::requiredIf(function () use ($field) {
                return in_array('document_file_1', $field);
            }),'nullable','mimes:pdf,doc,docx,jpg,jpeg,png,txt','max:'.$maxFileSize],
            'document_file_2' =>[Rule::requiredIf(function () use ($field) {
                return in_array('document_file_2', $field);
            }),'nullable','mimes:pdf,doc,docx,jpg,jpeg,png,txt','max:'.$maxFileSize],
            'document_file_3' =>[Rule::requiredIf(function () use ($field) {
                return in_array('document_file_3', $field);
            }),'nullable','mimes:pdf,doc,docx,jpg,jpeg,png,txt','max:'.$maxFileSize],
            'document_file_4' =>[Rule::requiredIf(function () use ($field) {
                return in_array('document_file_4', $field);
            }),'nullable','mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:'.$maxFileSize],
        ];

        if (moduleStatusCheck('Lead')==true) {
            $rules['lead_city'] = [Rule::requiredIf(function () use ($field) {
                return in_array('lead_city', $field);
            })];
            $rules['source_id'] =[Rule::requiredIf(function () use ($field) {
                return in_array('source_id', $field);
            })];
        }
        if (moduleStatusCheck('University') && !$this->id) {
            $rules += [
                'un_session_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('un_session_id', $field);
                })],
                'un_faculty_id' => [ 'sometimes', 'nullable', Rule::requiredIf(function () use ($field) {
                    return $this->filled('un_faculty_id') && in_array('un_faculty_id', $field);
                })],
                'un_department_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('un_department_id', $field);
                })],
                'un_academic_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('un_academic_id', $field);
                })],
                'un_semester_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('un_semester_id', $field);
                })],
                'un_semester_label_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('un_semester_label_id', $field);
                })],
                'un_section_id' => [Rule::requiredIf(function () use ($field) {
                    return  in_array('section', $field);
                })],
            ];
        } else {
            $rules += [
                'session' =>  [Rule::requiredIf(function () use ($field) {
                    return $this->filled('session') && in_array('session', $field);
                })],
                'class' => [Rule::requiredIf(function () use ($field) {
                    return $this->filled('class') &&  in_array('class', $field);
                })],
                'section' => [Rule::requiredIf(function () use ($field) {
                    return $this->filled('section') && in_array('section', $field);
                })],
            ];
        }

        if ($user_role_id !=2) {
            $rules +=[
                'email_address' => ['bail',Rule::requiredIf(function () use ($field) {
                    return in_array('email_address', $field);
                }), 'sometimes','nullable','email', Rule::unique('users', 'email')->ignore(optional($student)->user_id)],
                'phone_number'=>['bail',Rule::requiredIf(function () use ($field) {
                    return in_array('phone_number', $field);
                }),Rule::unique('users', 'phone_number')->where(function ($query) use ($student) {
                    return  $query->whereNotNull('phone_number')->where('id', '!=', (optional($student)->user_id));
                })],
                'guardians_email' =>['bail', Rule::requiredIf(function () use ($field) {
                    return !$this->parent_id && !$this->staff_parent && in_array('guardians_email', $field);
                }), 'sometimes', 'nullable'],
                'guardians_phone'=>['bail', 'nullable', Rule::requiredIf(function () use ($field) {
                    return !$this->parent_id && !$this->staff_parent && in_array('guardians_phone', $field);
                }),'max:100', 'different:phone_number'],
                'roll_number' => ['sometimes', 'nullable', Rule::requiredIf(function () use ($field) {
                    return $this->filled('session') && in_array('roll_number', $field);
            }), Rule::unique('sm_students', 'roll_no')->ignore(optional($student)->id)->where('school_id', $school_id)->where('academic_id', $academic_id)->whereIn('class_id', $class_ids)->whereIn('section_id', $section_ids)],
            ];
        }
        
       
        //added by abu nayem lead id number check replace of roll number


        return $rules;
    }
    public function attributes()
    {

        $attributes =  [
            'session' => 'Academic',
        ];
        if (moduleStatusCheck('Lead')==true) {
            $attributes['roll_number'] = 'ID Number';
            $attributes['source_id'] = 'Source';
            $attributes['lead_city'] = 'City';
        }

        if (moduleStatusCheck('University')) {
            $attributes['un_session_id'] = 'Session';
            $attributes['un_faculty_id'] = 'Faculty';
            $attributes['un_department_id'] = 'Department';
            $attributes['un_academic_id'] = 'Academic';
            $attributes['un_semester_id'] = 'Semester';
            $attributes['un_semester_label_id'] = 'Semester Label';
            $attributes['un_section_id'] = 'Section';
        }
        return $attributes;
    }
}
