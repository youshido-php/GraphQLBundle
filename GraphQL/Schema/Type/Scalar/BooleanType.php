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

class BooleanType extends AbstractInputType
{

    function parseValue($value, $name, ValidationErrorList $errorList)
    {
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if (!is_bool($value)) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected type \"Boolean\"', $name)
            ));

            return null;
        }

        return $value;
    }

    public function resolve($value = null, $args = [])
    {
        return (bool)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Boolean';
    }
}