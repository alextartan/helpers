<?php

declare(strict_types=1);

namespace AlexTartan\Helpers;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

use function get_class;

final class ReflectionHelper
{
    /** @throws ReflectionException */
    public static function setPrivatePropertyValue(object $object, string $property, mixed $value, string $className = null): void
    {
        if ($className === null) {
            $className = get_class($object);
        }

        $reflection = new ReflectionProperty($className, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * @param class-string $className
     *
     * @throws ReflectionException
     */
    public static function getPrivatePropertyValue(object $object, string $propertyName, string $className = null): mixed
    {
        if ($className === null) {
            $className = get_class($object);
        }
        $reflection = new ReflectionClass($className);

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @throws ReflectionException
     */
    public static function callPrivateMethod(object $object, string $methodName, array $methodArgs): mixed
    {
        $reflection = new ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArgs);
    }

    /**
     * @param class-string $className
     *
     * @throws ReflectionException
     */
    public static function getConstant(object $object, string $constantName, string $className = null): int|string|null
    {
        if ($className === null) {
            $className = get_class($object);
        }
        $reflection = new ReflectionClass($className);
        $constants  = $reflection->getConstants();

        return $constants[$constantName] ?? null;
    }
}
