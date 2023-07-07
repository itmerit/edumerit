<?php

namespace Modules\Chat\Services;

use Modules\Chat\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository  $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search($keywords){
        return $this->userRepository->search($keywords);
    }

    public function profileUpdate($data)
    {
        return $this->userRepository->profileUpdate($data);
    }

    public function blockAction($type, $user)
    {
        return $this->userRepository->blockAction($type, $user);
    }

    public function allBlockedUsers()
    {
        return $this->userRepository->allBlockedUsers();
    }

}
