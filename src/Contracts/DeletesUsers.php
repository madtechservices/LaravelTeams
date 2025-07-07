<?php

namespace Madtechservices\LaravelTeams\Contracts;

interface DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(object $user): void;
}
