<?php
declare(strict_types=1);

namespace AlexTartan\Helpers;

final class ReflectionHelper
{
    public static function setPrivatePropertyValue($object, string $property, $value)
    {
        $reflection = new \ReflectionProperty(get_class($object), $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    public static function getPrivatePropertyValue($object, string $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public static function callPrivateMethod($object, string $methodName, array $methodArgs)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArgs);
    }
}
