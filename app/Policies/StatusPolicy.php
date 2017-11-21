<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Status;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     *  当前用户的 id 与要删除的微博作者 id 相同时，验证才能通过。
     * @param  User   $user   [description]
     * @param  Status $status [description]
     * @return [type]         [description]
     */
    public function destroy(User $user, Status $status)
    {
        return $user->id === $status->user_id;
    }
}
