<?php

namespace App\Repositories\User;

use App\Models\User;

interface IUserRepository
{

    public function create($collection = []);
    public function update($id, $collection = []);
    public function delete($id);
    public function getAll();
}