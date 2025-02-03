<?php

namespace App\Storages;

trait BaseStorage
{
    public static function getStorageKey(): string
    {
        return static::STORAGE_KEY;
    }

    public static function put($data): void
    {
        session()->put(self::getStorageKey(), $data);
    }

    public static function exists(): bool
    {
        return session()->has(self::getStorageKey());
    }

    public static function clear(): void
    {
        session()->forget(self::getStorageKey());
    }
}
