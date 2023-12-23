<?php

namespace App\Repositories\User;

use App\Models\User;

interface IUserRepository
{

    public function createOrUpdate($id = null, $collection = []);
    public function findById($id);
    public function delete($id);
    public function getAll();
}
