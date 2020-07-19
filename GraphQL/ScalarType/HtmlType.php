<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class HtmlType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
class HtmlType extends StringType
{
    use TDynamicName;

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' Represents HtmlType - a string that contains html content.';
    }
}
