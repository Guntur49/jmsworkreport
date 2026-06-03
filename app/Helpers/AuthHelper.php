<?php

namespace App\Helpers;

/**
 * Authorization Helper
 * Provides utility functions for checking user permissions
 */
class AuthHelper
{
    /**
     * Check if the user can edit/create/delete data
     * NMS users can only view data
     */
    public static function canManageData(): bool
    {
        return session('user_role') !== 'NMS';
    }

    /**
     * Check if the user is NMS admin (read-only)
     */
    public static function isNmsAdmin(): bool
    {
        return session('user_role') === 'NMS';
    }

    /**
     * Check if the user is JMS admin (full access)
     */
    public static function isJmsAdmin(): bool
    {
        return session('user_role') === 'JMS';
    }

    /**
     * Get a message for NMS read-only restriction
     */
    public static function getReadOnlyMessage(): string
    {
        return 'Admin NMS hanya memiliki akses untuk melihat data. Tidak dapat melakukan perubahan atau penghapusan data.';
    }
}
