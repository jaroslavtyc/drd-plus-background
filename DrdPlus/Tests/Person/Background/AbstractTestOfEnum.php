<?php
namespace DrdPlus\Tests\Person\Background;

use Doctrineum\Scalar\ScalarEnum;
use Doctrineum\Scalar\ScalarEnumType;
use DrdPlus\Tests\Tables\Measurements\TestWithMockery;

abstract class AbstractTestOfEnum extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_use_it_as_an_enum()
    {
        $sutClass = $this->getSutClass();
        $this->assertTrue(is_a($sutClass, ScalarEnum::class, true));

        $typeClass = $this->getEnumTypeClass();
        $this->assertTrue(class_exists($typeClass));
        $this->assertTrue(is_a($typeClass, ScalarEnumType::class, true));
        $typeClass::registerSelf();

        $this->assertTrue(ScalarEnumType::hasType($this->getEnumCode()));
    }

    /**
     * @return ScalarEnumType
     */
    private function getEnumTypeClass()
    {
        $enumClassBaseName = $this->getEnumClassBasename();
        $enumTypeClassBasename = $enumClassBaseName . 'Type';

        $enumClass = $this->getSutClass();
        $reflection = new \ReflectionClass($enumClass);
        $enumNamespace = $reflection->getNamespaceName();

        $enumTypeNamespace = $enumNamespace . '\\' . 'EnumTypes';

        return $enumTypeNamespace . '\\' . $enumTypeClassBasename;
    }

    private function getEnumClassBasename()
    {
        $enumClass = $this->getSutClass();
        preg_match('~(?<basename>\w+$)~', $enumClass, $matches);
        $enumClassBaseName = $matches['basename'];

        return $enumClassBaseName;
    }

    private function getEnumCode()
    {
        $enumClassBaseName = $this->getEnumClassBasename();
        $underscored = preg_replace('~([a-z])([A-Z])~', '$1_$2', $enumClassBaseName);
        $code = strtolower($underscored);

        return $code;
    }

    /**
     * @return ScalarEnum
     */
    protected function getSutClass()
    {
        $sutClass = preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);

        return $sutClass;
    }
}
