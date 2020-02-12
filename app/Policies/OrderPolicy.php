<?php

namespace App\Policies;

use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use TopDigital\Auth\Models\User;

class OrderPolicy
{
    use HandlesAuthorization;

    public function index(?User $user) : bool
    {
        return $this->checkMainPolicy($user);
    }

    public function create(?User $user) : bool
    {
        return true;
    }

    public function update(?User $user, Order $order) : bool
    {
        return false;
    }

    public function delete(?User $user, Order $order) : bool
    {
        return false;
    }

    public function checkMainPolicy(?User $user) : bool
    {
        return !!$user;
    }
}
