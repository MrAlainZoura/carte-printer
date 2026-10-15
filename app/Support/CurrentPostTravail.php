<?php

namespace App\Support;

use App\Models\PostTravail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CurrentPostTravail
{
    public const SESSION_POST_ID = 'current_post_travail_id';

    public const SESSION_GLOBAL = 'dashboard_global';

    public static function id(): ?int
    {
        $id = Session::get(self::SESSION_POST_ID);

        return $id !== null ? (int) $id : null;
    }

    public static function get(): ?PostTravail
    {
        $id = self::id();

        return $id ? PostTravail::query()->find($id) : null;
    }

    public static function isGlobal(): bool
    {
        return (bool) Session::get(self::SESSION_GLOBAL, false);
    }

    public static function set(int $postTravailId): void
    {
        Session::put(self::SESSION_POST_ID, $postTravailId);
        Session::forget(self::SESSION_GLOBAL);
    }

    public static function setGlobal(): void
    {
        Session::forget(self::SESSION_POST_ID);
        Session::put(self::SESSION_GLOBAL, true);
    }

    public static function clear(): void
    {
        Session::forget(self::SESSION_POST_ID);
        Session::forget(self::SESSION_GLOBAL);
    }

    public static function isReady(): bool
    {
        if (self::id()) {
            return true;
        }

        $user = Auth::user();

        return self::isGlobal() && $user && $user->hasRole('admin');
    }
}
