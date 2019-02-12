<?php
declare(strict_types=1);

namespace AlexTartan\Helpers;

final class ReflectionHelper
{
    /**
     * @param mixed $value
     */
    public static function setPrivatePropertyValue(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionProperty(get_class($object), $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * @return mixed
     */
    public static function getPrivatePropertyValue(object $object, string $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @return mixed
     */
    public static function callPrivateMethod(object $object, string $methodName, array $methodArgs)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArgs);
    }
}
