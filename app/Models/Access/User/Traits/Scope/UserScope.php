<?php

namespace App\Models\Access\User\Traits\Scope;

/**
 * Class UserScope.
 */
trait UserScope
{
    /**
     * @param $query
     * @param bool $emailVerified
     *
     * @return mixed
     */
    public function scopeEmailVerified($query, $emailVerified = true)
    {
        return $query->where('email_verified', $emailVerified);
    }

    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = true)
    {
        return $query->where('status', $status);
    }
}
