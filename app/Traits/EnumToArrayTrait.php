<?php

namespace App\Traits;

use BackedEnum;
use Illuminate\Support\Collection;

trait EnumToArrayTrait
{
    /**
     * @return array <string,int|string>
     */
    public static function toInverseArray(): array
    {
        return array_combine(self::values()->toArray(), self::names()->toArray());
    }

    /**
     * @return array <string,int|string>
     */
    public static function toArray(): array
    {
        return array_combine(self::names()->toArray(), self::values()->toArray());
    }

    /**
     * @return Collection
     */
    public static function names(): Collection
    {
        return collect(array_column(self::cases(), 'name'));
    }

    /**
     * @return Collection
     */
    public static function values(): Collection
    {
        return collect(array_column(self::cases(), 'value'));
    }

    /**
     * @param string $name
     * @return BackedEnum
     */
    public static function fromName(string $name): BackedEnum
    {
        return self::enumByName()[$name];
    }

    /**
     * @return array <string,UnitEnum>
     */
    public static function enumByName(): array
    {
        return array_combine(self::names()->toArray(), self::cases());
    }

    /**
     * @return array <int|string,UnitEnum>
     */
    public static function enumByValue(): array
    {
        return array_combine(self::values()->toArray(), self::cases());
    }
}
