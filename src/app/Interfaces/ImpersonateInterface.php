<?php

namespace MHMartinez\ImpersonateUser\app\Interfaces;

interface ImpersonateInterface
{
    /**
     * Whether this model has permission to impersonate others
     */
    public function canImpersonateOthers(): bool;

    /**
     * Whether this model can be impersonated by others
     * E.g.: you might not want admins to impersonate super admins!
     */
    public function canBeImpersonated(): bool;
}