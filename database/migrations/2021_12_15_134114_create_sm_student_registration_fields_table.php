<?php

use App\SmSchool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\SmStudentRegistrationField;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;

class CreateSmStudentRegistrationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_student_registration_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('label_name')->nullable();  
            $table->tinyInteger('is_show')->nullable()->default(1);              
            $table->tinyInteger('active_status')->nullable()->default(1);
            $table->tinyInteger('is_required')->nullable()->default(0);
            $table->tinyInteger('student_edit')->nullable()->default(0);
            $table->tinyInteger('parent_edit')->nullable()->default(0);
            $table->tinyInteger('staff_edit')->nullable()->default(0);
            $table->tinyInteger('type')->nullable()->comment('1=student,2=staff');
            $table->tinyInteger('is_system_required')->nullable()->default('0');
            $table->tinyInteger('required_type')->nullable()->comment('1=switch on,2=off');
           
            $table->integer('position')->nullable();
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('set null');

            $table->timestamps();
        });
        try {
            $request_fields=[
              'session',
              'class' ,
              'section',
              'roll_number', 
              'admission_number', 
              'first_name',
              'last_name',
              'gender',
              'date_of_birth',
              'blood_group',
              'email_address',
              'caste',
              'phone_number',
              'religion',
              'admission_date',
              'student_category_id',
              'student_group_id',
              'height', 
              'weight',
              'photo',
              'fathers_name',  
              'fathers_occupation',               
              'fathers_phone',
              'fathers_photo',
              'mothers_name',  
              'mothers_occupation',               
              'mothers_phone',
              'mothers_photo',
              'guardians_name',                        
              'guardians_email',
              'guardians_photo',
              'guardians_phone',  
              'guardians_occupation',
              'guardians_address',
              'current_address',  
              'permanent_address',
              'route',
              'vehicle',
              'dormitory_name',
              'room_number',
              'national_id_number',
              'local_id_number',
              'bank_account_number',
              'bank_name',
              'previous_school_details',
              'additional_notes',
              'ifsc_code',
              'document_file_1',
              'document_file_2',
              'document_file_3',
              'document_file_4',
              'custom_field'
          ];
          
            $all_schools = SmSchool::get();
            foreach ($all_schools as $school) {
                foreach ($request_fields as $key=>$value) {
                    $exit = SmStudentRegistrationField::where('school_id', $school->id)->where('field_name', $value)->first();
                    if (!$exit) {
                        $field=new SmStudentRegistrationField;
                        $field->position=$key+1;
                        $field->field_name=$value;
                        $field->label_name=$value;
                        $field->type=1;
                        $field->school_id = $school->id;
                        $field->save();
                    }
                }
            
                $required_fields =  ['session','class', 'section' ,'first_name','last_name','gender' ,'date_of_birth' ,'relation','guardians_email','phone_number'];
                
                $student_edit =  ['roll_number','first_name','last_name','gender' ,'date_of_birth','phone_number','email_address'];

                $parent_edit =  [
                'first_name','last_name','gender' ,'date_of_birth','phone_number','email_address','fathers_name', 'fathers_occupation','fathers_phone','fathers_photo','mothers_name','mothers_occupation', 'mothers_phone', 'mothers_photo','guardians_name','guardians_email',
                'guardians_photo','guardians_phone','guardians_occupation','guardians_address',
                'current_address', 'permanent_address'];

                SmStudentRegistrationField::where('school_id', $school->id)->whereIn('field_name', $required_fields)->update(['is_required'=>1, 'is_system_required'=>1]);

                SmStudentRegistrationField::where('school_id', $school->id)->whereIn('field_name', $student_edit)->update(['student_edit'=>1]);

                SmStudentRegistrationField::where('school_id', $school->id)->whereIn('field_name', $parent_edit)->update(['parent_edit'=>1]);

                $currencies = [
                    [1, 'Leke', 'ALL', 'Lek'],
                    [2, 'Dollars', 'USD', '$'],
                    [3, 'Afghanis', 'AFN', '؋'],
                    [4, 'Pesos', 'ARS', '$'],
                    [5, 'Guilders', 'AWG', 'ƒ'],
                    [6, 'Dollars', 'AUD', '$'],
                    [7, 'New Manats', 'AZN', 'ман'],
                    [8, 'Dollars', 'BSD', '$'],
                    [9, 'Dollars', 'BBD', '$'],
                    [10, 'Rubles', 'BYR', 'p.'],
                    [11, 'Euro', 'EUR', '€'],
                    [12, 'Dollars', 'BZD', 'BZ$'],
                    [13, 'Dollars', 'BMD', '$'],
                    [14, 'Bolivianos', 'BOB', '$b'],
                    [15, 'Convertible Marka', 'BAM', 'KM'],
                    [16, 'Pula', 'BWP', 'P'],
                    [17, 'Leva', 'BGN', 'лв'],
                    [18, 'Reais', 'BRL', 'R$'],
                    [19, 'Pounds', 'GBP', '£'],
                    [20, 'Dollars', 'BND', '$'],
                    [21, 'Riels', 'KHR', '៛'],
                    [22, 'Dollars', 'CAD', '$'],
                    [23, 'Dollars', 'KYD', '$'],
                    [24, 'Pesos', 'CLP', '$'],
                    [25, 'Yuan Renminbi', 'CNY', '¥'],
                    [26, 'Pesos', 'COP', '$'],
                    [27, 'Colón', 'CRC', '₡'],
                    [28, 'Kuna', 'HRK', 'kn'],
                    [29, 'Pesos', 'CUP', '₱'],
                    [30, 'Koruny', 'CZK', 'Kč'],
                    [31, 'Kroner', 'DKK', 'kr'],
                    [32, 'Pesos', 'DOP ', 'RD$'],
                    [33, 'Dollars', 'XCD', '$'],
                    [34, 'Pounds', 'EGP', '£'],
                    [35, 'Colones', 'SVC', '$'],
                    [36, 'Pounds', 'FKP', '£'],
                    [37, 'Dollars', 'FJD', '$'],
                    [38, 'Cedis', 'GHC', '¢'],
                    [39, 'Pounds', 'GIP', '£'],
                    [40, 'Quetzales', 'GTQ', 'Q'],
                    [41, 'Pounds', 'GGP', '£'],
                    [42, 'Dollars', 'GYD', '$'],
                    [43, 'Lempiras', 'HNL', 'L'],
                    [44, 'Dollars', 'HKD', '$'],
                    [45, 'Forint', 'HUF', 'Ft'],
                    [46, 'Kronur', 'ISK', 'kr'],
                    [47, 'Rupees', 'INR', '₹'],
                    [48, 'Rupiahs', 'IDR', 'Rp'],
                    [49, 'Rials', 'IRR', '﷼'],
                    [50, 'Pounds', 'IMP', '£'],
                    [51, 'New Shekels', 'ILS', '₪'],
                    [52, 'Dollars', 'JMD', 'J$'],
                    [53, 'Yen', 'JPY', '¥'],
                    [54, 'Pounds', 'JEP', '£'],
                    [55, 'Tenge', 'KZT', 'лв'],
                    [56, 'Won', 'KPW', '₩'],
                    [57, 'Won', 'KRW', '₩'],
                    [58, 'Soms', 'KGS', 'лв'],
                    [59, 'Kips', 'LAK', '₭'],
                    [60, 'Lati', 'LVL', 'Ls'],
                    [61, 'Pounds', 'LBP', '£'],
                    [62, 'Dollars', 'LRD', '$'],
                    [63, 'Switzerland Francs', 'CHF', 'CHF'],
                    [64, 'Litai', 'LTL', 'Lt'],
                    [65, 'Denars', 'MKD', 'ден'],
                    [66, 'Ringgits', 'MYR', 'RM'],
                    [67, 'Rupees', 'MUR', '₨'],
                    [68, 'Pesos', 'MXN', '$'],
                    [69, 'Tugriks', 'MNT', '₮'],
                    [70, 'Meticais', 'MZN', 'MT'],
                    [71, 'Dollars', 'NAD', '$'],
                    [72, 'Rupees', 'NPR', '₨'],
                    [73, 'Guilders', 'ANG', 'ƒ'],
                    [74, 'Dollars', 'NZD', '$'],
                    [75, 'Cordobas', 'NIO', 'C$'],
                    [76, 'Nairas', 'NGN', '₦'],
                    [77, 'Krone', 'NOK', 'kr'],
                    [78, 'Rials', 'OMR', '﷼'],
                    [79, 'Rupees', 'PKR', '₨'],
                    [80, 'Balboa', 'PAB', 'B/.'],
                    [81, 'Guarani', 'PYG', 'Gs'],
                    [82, 'Nuevos Soles', 'PEN', 'S/.'],
                    [83, 'Pesos', 'PHP', 'Php'],
                    [84, 'Zlotych', 'PLN', 'zł'],
                    [85, 'Rials', 'QAR', '﷼'],
                    [86, 'New Lei', 'RON', 'lei'],
                    [87, 'Rubles', 'RUB', 'руб'],
                    [88, 'Pounds', 'SHP', '£'],
                    [89, 'Riyals', 'SAR', '﷼'],
                    [90, 'Dinars', 'RSD', 'Дин.'],
                    [91, 'Rupees', 'SCR', '₨'],
                    [92, 'Dollars', 'SGD', '$'],
                    [93, 'Dollars', 'SBD', '$'],
                    [94, 'Shillings', 'SOS', 'S'],
                    [95, 'Rand', 'ZAR', 'R'],
                    [96, 'Rupees', 'LKR', '₨'],
                    [97, 'Kronor', 'SEK', 'kr'],
                    [98, 'Dollars', 'SRD', '$'],
                    [99, 'Pounds', 'SYP', '£'],
                    [100, 'New Dollars', 'TWD', 'NT$'],
                    [101, 'Baht', 'THB', '฿'],
                    [102, 'Dollars', 'TTD', 'TT$'],
                    [103, 'Lira', 'TRY', 'TL'],
                    [104, 'Liras', 'TRL', '£'],
                    [105, 'Dollars', 'TVD', '$'],
                    [106, 'Hryvnia', 'UAH', '₴'],
                    [107, 'Pesos', 'UYU', '$U'],
                    [108, 'Sums', 'UZS', 'лв'],
                    [109, 'Bolivares Fuertes', 'VEF', 'Bs'],
                    [110, 'Dong', 'VND', '₫'],
                    [111, 'Rials', 'YER', '﷼'],
                    [112, 'Taka', 'BDT', '৳'],
                    [113, 'Zimbabwe Dollars', 'ZWD', 'Z$'],
                    [114, 'Kenya', 'KES', 'KSh'],
                    [115, 'Nigeria', 'naira', '₦'],
                    [116, 'Ghana', 'GHS', 'GH₵'],
                    [117, 'Ethiopian', 'ETB', 'Br'],
                    [118, 'Tanzania', 'TZS', 'TSh'],
                    [119, 'Uganda', 'UGX', 'USh'],
                    [120, 'Rwandan', 'FRW', 'FRw']
                ];

                foreach ($currencies as $currency) {
                    if(!\App\SmCurrency::where('name', $currency[1])->where('school_id', $school->id)->first()){
                        $store = new \App\SmCurrency();
                        $store->name = $currency[1];
                        $store->code = $currency[2];
                        $store->symbol = $currency[3];
                        $store->school_id = $school->id;
                        $store->save();
                    }

                }

                if(!\App\SmBackgroundSetting::where('title', 'Dashboard Background')->where('school_id', $school->id)->first()){
                    $b = new \App\SmBackgroundSetting();
                    $b->school_id = $school->id;
                    $b->title = 'Dashboard Background';
                    $b->type = 'image';
                    $b->color = '';
                    $b->image = 'public/backEnd/img/body-bg.jpg';
                    $b->is_default = 1;
                    $b->save();
                }
                if(!\App\SmBackgroundSetting::where('title', 'Login Background')->where('school_id', $school->id)->first()){
                    $b = new \App\SmBackgroundSetting();
                    $b->school_id = $school->id;
                    $b->title = 'Login Background';
                    $b->type = 'image';
                    $b->color = '';
                    $b->image = 'public/backEnd/img/login-bg.jpg';
                    $b->is_default = 1;
                    $b->save();
                }


            }

            $required_fields = ['session', 'class', 'section', 'first_name', 'last_name', 'gender', 'date_of_birth', 'relation', 'guardians_email'];

            $all_schools = SmSchool::get();
            foreach ($all_schools as $school) {
                SmStudentRegistrationField::where('school_id', $school->id)->whereIn('field_name', $required_fields)->update(['is_required' => 1, 'is_system_required' => 1]);
            }
        
        } catch (\Exception $e) {
            Log::info($e);
        }

            $new = new InfixModuleInfo();
            $new->id = 951;
            $new->module_id = 3;
            $new->parent_id = 61;
            $new->type = '2';
            $new->is_saas = 0;
            $new->route = "Student Settings";
            $new->lang_name = "student_settings";
            $new->icon_class = "student_settings";
            $new->active_status = 1;
            $new->created_by =1;
            $new->updated_by = 1;
            $new->school_id = 1;
            $new->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_registration_fields');
    }
}
