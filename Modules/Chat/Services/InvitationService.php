<?php

namespace Modules\Chat\Services;

use Modules\Chat\Repositories\InvitationRepository;

class InvitationService
{
    protected $invitationRepository;

    public function __construct(InvitationRepository  $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function invitationCreate($to, $status)
    {
        return $this->invitationRepository->invitationCreate($to, $status);
    }

    public function myRequest()
    {
        return $this->invitationRepository->myRequest();
    }

    public function peopleRequest()
    {
        return $this->invitationRepository->peopleRequest();
    }

    public function invitationUpdate($type, $id)
    {
        return $this->invitationRepository->invitationUpdate($type, $id);
    }

    public function getAllConnectedUsers()
    {
        return $this->invitationRepository->getAllConnectedUsers();
    }

    public function getBlockUsers()
    {
        return $this->invitationRepository->blocksResult();
    }

}
