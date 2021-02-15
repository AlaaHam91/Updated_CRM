<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\User;

class UserRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IUserRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return User::class;
    }
}
