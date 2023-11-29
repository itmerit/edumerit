<?php


use App\SmBaseSetup;
use App\SmSchool;
use App\SmStaff;
use App\SmStudent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BaseSetupUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $schools = SmSchool::where('id','!=', 1)->get();
        $base_setups = SmBaseSetup::where('school_id', 1)->get();

        foreach($schools as $school){
            foreach($base_setups as $setup){
                $exit = SmBaseSetup::where('base_setup_name',$setup->base_setup_name)->where('base_group_id',$setup->base_group_id)->where('school_id', $school->id)->first();
                if(!$exit){
                    $new_setup = $setup->replicate();
                    $new_setup->school_id = $school->id;
                    $new_setup->save();
    
                    $this->update($new_setup, $setup);
                }
            }

        }
    }

    public function update($new_setup, $old_setup){
        if($new_setup->base_group_id == 1){
            $column = 'gender_id';
            SmStaff::where('gender_id', $old_setup->id)->where('school_id', $new_setup->school_id)->update([$column => $new_setup->id]);
        } else if($new_setup->base_group_id == 2){
            $column = 'religion_id';
        } else{
            $column = 'bloodgroup_id';
        }
        
        SmStudent::where($column, $old_setup->id)->where('school_id', $new_setup->school_id)->update([$column => $new_setup->id]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
