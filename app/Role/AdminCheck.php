<?php

namespace App\Role;


class AdminCheck
{
    public function check($user)
    {
        if ($user) {
            if ($user->admin) {
                return true;
            }
        }
        return false;
    }
}
