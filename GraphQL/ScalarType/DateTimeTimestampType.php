<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\DateTimeType;

/**
 * Class DateTimeTimestampType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
class DateTimeTimestampType extends DateTimeType
{
    /**
     * DateTimeTimestampType constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
