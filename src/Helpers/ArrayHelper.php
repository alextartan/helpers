<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use InvalidArgumentException;

final class ArrayHelper
{
    /** @throws InvalidArgumentException */
    public static function indexArrayOfObjectsByMethod(
        array $objects,
        string $methodName,
        bool $failOnDuplicates = true
    ): array {
        $outputArray = [];
        foreach ($objects as $inputObject) {
            if (!is_object($inputObject)) {
                throw new InvalidArgumentException('Parameter is not an object');
            }

            if (!method_exists($inputObject, $methodName)) {
                throw new InvalidArgumentException("Object does not implement '$methodName' method");
            }

            $key = $inputObject->$methodName();

            if (!is_string($key) && !is_int($key)) {
                throw new InvalidArgumentException('Key is neither a string nor an integer');
            }
            if ($failOnDuplicates && isset($outputArray[$key])) {
                throw new InvalidArgumentException('Duplicate key found: ' . $key);
            }
            $outputArray[$key] = $inputObject;
        }

        return $outputArray;
    }

    /** @throws InvalidArgumentException */
    public static function indexArrayOfArraysByKey(
        array $arrayOfArrays,
        string $keyName,
        bool $failOnDuplicates = true
    ): array {
        $outputArray = [];
        foreach ($arrayOfArrays as $array) {
            if (!array_key_exists($keyName, $array)) {
                throw new InvalidArgumentException("Array does not have '$keyName' key");
            }

            $key = $array[$keyName];

            if (!is_string($key) && !is_int($key)) {
                throw new InvalidArgumentException('Key is neither a string nor an integer');
            }
            if ($failOnDuplicates && isset($outputArray[$key])) {
                throw new InvalidArgumentException('Duplicate key found: ' . $key);
            }
            $outputArray[$key] = $array;
        }

        return $outputArray;
    }
}
