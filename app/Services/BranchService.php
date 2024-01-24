<?php

namespace App\Services;

use App\Repositories\BranchRepository;

class BranchService
{
    private $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function indexBranch()
    {
        return $this->branchRepository->indexBranch();
    }

    public function storeBranch($data)
    {
        return $this->branchRepository->storeBranch($data);
    }

    public function editBranch($id)
    {
        return $this->branchRepository->editBranch($id);
    }

    public function updateBranch($data, $id)
    {
        return $this->branchRepository->updateBranch($data, $id);
    }

    public function updateStatus($id)
    {
        return $this->branchRepository->updateStatus($id);
    }

    public function destroyBranch($id)
    {
        return $this->branchRepository->destroyBranch($id);
    }

    public function restoreBranch($id)
    {
        return $this->branchRepository->restoreBranch($id);
    }

    public function validateInputs($data)
    {
        $this->branchRepository->setName($data['name']);
        $is_name_exists = $this->branchRepository->isNameExists();
        if ($data->name == null) {
            return [
                'success' => false,
                'name_msg' => 'Please select a name',
            ];
        } else if($is_name_exists != null) {
            return [
                'success' => false,
                'name_msg' => 'Name already taken',
            ];
        }
        else {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }

    public function UpdateInputs($data)
    {
        $this->branchRepository->setName($data['name']);
        $is_name_exists_for_update = $this->branchRepository->isNameExistsForUpdate($data['current_name']);
        if ($data->name == null) {
            return [
                'success' => false,
                'name_msg' => 'Please select a name',
            ];
        }
        else if ($is_name_exists_for_update != null) {
            return [
                'success' => false,
                'name_msg' => 'Name already taken',

            ];
        } else {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }

    public function getBranches()
    {
        return $this->branchRepository->getBranches();
    }
}
