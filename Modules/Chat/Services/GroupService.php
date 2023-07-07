<?php

namespace Modules\Chat\Services;

use Modules\Chat\Repositories\GroupRepository;
use Modules\Chat\Repositories\UserRepository;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository  $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getAllGroup(){
        return $this->groupRepository->getAllGroup();
    }

}
