<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Interval;

use BastSys\UtilsBundle\Model\Interval\Interval;
use Youshido\GraphQL\Type\Scalar\FloatType;

/**
 * Class IntervalFloatType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Interval
 * @author mirkl
 */
class IntervalFloatType extends FloatType
{
    /** @var Interval */
    private Interval $interval;

    /**
     * IntervalFloatType constructor.
     * @param Interval $interval
     */
    public function __construct(Interval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return false|string
     */
    public function getName()
    {
        return 'IntervalFloat';
    }

    /**
     * @param $value
     * @return bool
     */
    public function isValidValue($value)
    {
        return is_null($value) || (is_numeric($value) && $this->interval->contains($value));
    }

    /**
     * @param null $value
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        $error = parent::getValidationError($value);
        if ($error) {
            return $error;
        }

        return "Value must be at interval $this->interval";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' Value must be part of a certain interval.';
    }
}
