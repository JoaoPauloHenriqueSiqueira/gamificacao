<?php

namespace App\Role;


class UserCheck
{
    public function check($user)
    {
        if ($user) {
            if ($user->active) {
                return true;
            }
        }
        return false;
    }
}
