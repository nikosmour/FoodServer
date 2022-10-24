<?php

namespace App\Traits;

//use App\Traits\Enum\Names;
//use App\Traits\Enum\Values;
use Illuminate\Support\Collection;
use UnitEnum;

trait EnumToArrayTrait
{
//    use Values, Names;
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
     * @param string $name
     * @return UnitEnum
     */
    public static function getEnumByName(string $name): UnitEnum
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
     * @param int|string $value
     * @return UnitEnum
     */
    public static function getEnumByValue(int|string $value): UnitEnum
    {
        return self::enumByValue()[$value];
    }

    /**
     * @return array <int|string,UnitEnum>
     */
    public static function enumByValue(): array
    {
        return array_combine(self::values()->toArray(), self::cases());
    }
}
