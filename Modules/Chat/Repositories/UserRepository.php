<?php
namespace Modules\Chat\Repositories;

use App\Models\User;
use Modules\Chat\Entities\BlockUser;


class UserRepository {

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function Search($keywords){
        $blocks = BlockUser::where('block_by', auth()->id())->pluck('block_to')->toArray();
        $users = User::with('roles')->where('id', '<>', auth()->id())
            ->where(function ($query) use ($keywords) {
                $query->orWhere('email', 'LIKE', '%'.$keywords.'%');
                $query->orWhere('full_name', 'LIKE', '%'.$keywords.'%');

            })
            ->whereNotIn('id', $blocks)
            ->paginate(10);

        // ==InfixEdu==

        if(app('general_settings')->get('chat_open') == 'no'){
            if (app('general_settings')->get('chat_can_teacher_chat_with_parents') == 'no'){
                foreach ($users as $index => $user){
                    if (auth()->user()->roles->id == 4){
                        $user->roles->id === 3 ? $users->forget($index) : '';
                    }
                }
            }
        }

        // ==End InfixEdu==


        return $users;
    }

    public function profileUpdate($data)
    {
        return User::find(auth()->id())->update($data);
    }

    public function blockAction($type, $user)
    {
        $block = BlockUser::where('block_by', auth()->id())->where('block_to', $user)->first();
        if ($type == 'block'){
            if (!$block) {
                BlockUser::create([
                    'block_by' => auth()->id(),
                    'block_to' => $user
                ]);
            }
        }else{
            $block->delete();
        }

        return true;
    }

    public function allBlockedUsers()
    {
        $blocks = BlockUser::where('block_by', auth()->id())->pluck('block_to')->toArray();
        return User::whereIn('id', $blocks)->get();
    }

}
