<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Scalar;


use Youshido\GraphQLBundle\GraphQL\Schema\Type\AbstractInputType;
use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class StringType extends AbstractInputType
{

    function parseValue($value, $name, ValidationErrorList $errorList)
    {
        if (!is_string($value)) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected type \"String\"', $name)
            ));

            return null;
        }

        return $value;
    }

    public function resolve($value = null, $args = [])
    {
        return (string)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'String';
    }
}