<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;
use StringBackedEnum;

interface Enum extends StringBackedEnum
{
    /**
     * @return array <string,int|string>
     */
    public static function toInverseArray(): array;

    /**
     * @return array <string,int|string>
     */
    public static function toArray(): array;

    /**
     * @return Collection
     */
    public static function names(): Collection;

    /**
     * @return Collection
     */
    public static function values(): Collection;

    /**
     * @param string $name
     * @return Enum
     */
    public static function fromName(string $name): Enum;

    /**
     * @return array <string,Enum>
     */
    public static function enumByName(): array;

    /**
     * @return array <int|string,Enum>
     */
    public static function enumByValue(): array;
}
