<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin_or_super_admin; // Check if the user is an admin
    }

    // You can add more methods for different actions
}
