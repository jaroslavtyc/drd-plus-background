<?php
namespace DrdPlus\Tests\Person\Background\BackgroundParts\Partials;

use DrdPlus\Person\Background\BackgroundParts\Ancestry;
use DrdPlus\Person\Background\BackgroundParts\Partials\AbstractHeritageDependent;
use DrdPlus\Person\Background\BackgroundPoints;

abstract class AbstractHeritageDependentTest extends AbstractBackgroundAdvantageTest
{
    protected function createSut($spentBackgroundPoints)
    {
        /** @var AbstractHeritageDependent $sutClass */
        $sutClass = self::getSutClass();

        return $sutClass::getIt($spentBackgroundPoints, $this->createHeritage($spentBackgroundPoints));
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|Ancestry
     */
    protected function createHeritage($value)
    {
        $ancestry = $this->mockery(Ancestry::class);
        $ancestry->shouldReceive('getValue')
            ->andReturn($value);
        $ancestry->shouldReceive('getSpentBackgroundPoints')
            ->andReturn($value);

        return $ancestry;
    }

    /**
     * @test
     * @dataProvider provideBackgroundPointsAndHeritage
     * @param int $spentBackgroundPoints
     * @param int $ancestryBackgroundPoints
     */
    public function I_can_create_it($spentBackgroundPoints, $ancestryBackgroundPoints)
    {
        /** @var AbstractHeritageDependent $sutClass */
        $sutClass = self::getSutClass();
        $sut = $sutClass::getIt($spentBackgroundPoints, $this->createHeritage($ancestryBackgroundPoints));
        self::assertSame($spentBackgroundPoints, $sut->getValue());
        self::assertSame($spentBackgroundPoints, $sut->getSpentBackgroundPoints());
    }

    public function provideBackgroundPointsAndHeritage()
    {
        return [
            [0, 0],
            [1, 0],
            [2, 0],
            [3, 0],
            [4, 4],
            [5, 3],
            [6, 3],
            [7, 4],
            [8, 8],
        ];
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|BackgroundPoints
     */
    protected function createBackgroundPoints($value)
    {
        $points = $this->mockery(BackgroundPoints::class);
        $points->shouldReceive('getValue')
            ->andReturn($value);

        return $points;
    }

    /**
     * @test
     * @dataProvider provideTooMuchBackgroundPointsToHeritage
     * @expectedException \DrdPlus\Person\Background\BackgroundParts\Partials\Exceptions\TooMuchSpentBackgroundPoints
     *
     * @param int $spentBackgroundPoints
     * @param int $ancestryBackgroundPoints
     */
    public function I_can_not_spent_more_than_three_more($spentBackgroundPoints, $ancestryBackgroundPoints)
    {
        /** @var AbstractHeritageDependent $sutClass */
        $sutClass = self::getSutClass();
        self::assertGreaterThan($ancestryBackgroundPoints + 3, $spentBackgroundPoints);
        self::assertLessThanOrEqual(8, $spentBackgroundPoints);
        $sutClass::getIt($spentBackgroundPoints, $this->createHeritage($ancestryBackgroundPoints));
    }

    public function provideTooMuchBackgroundPointsToHeritage()
    {
        return [
            [4, 0],
            [5, 0],
            [6, 0],
            [7, 0],
            [8, 4],
            [7, 3],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Background\BackgroundParts\Exceptions\UnexpectedBackgroundPoints
     */
    public function I_can_not_spent_negative_points()
    {
        /** @var AbstractHeritageDependent $sutClass */
        $sutClass = self::getSutClass();
        $sutClass::getIt(-1, $this->createHeritage(0));
    }

    /**
     * @test
     * @expectedException \DrdPlus\Person\Background\BackgroundParts\Exceptions\UnexpectedBackgroundPoints
     */
    public function I_can_not_spent_more_than_eight_points()
    {
        /** @var AbstractHeritageDependent $sutClass */
        $sutClass = self::getSutClass();
        $sutClass::getIt(9, $this->createHeritage(8));
    }
}
