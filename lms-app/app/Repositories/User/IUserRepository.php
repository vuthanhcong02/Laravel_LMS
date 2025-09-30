<?php

namespace App\Repositories\User;

interface IUserRepository
{
    public function create($collection = []);

    public function update($id, $collection = []);

    public function delete($id);

    public function getAll();
}
