<?php

use App\Events\CreateClassGroupChat;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmAssignSubject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\GroupUser;

class ChatMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Group::truncate();
        GroupUser::truncate();
        $subjects = SmAssignSubject::withOutGlobalScope(StatusAcademicSchoolScope::class)->get();
        foreach ($subjects as $assignSubject){
            event(new CreateClassGroupChat($assignSubject));
        }
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
