<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Scalar;


use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class BooleanType extends AbstractScalar
{

    function validate(ValidationErrorList $errorList, $name)
    {
        if (!is_bool($this->getValue())) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected type \"Boolean\"', $name)
            ));
        }
    }

    public function resolve($value = null, $args = [], $parent = null)
    {
        return (bool)$value;
    }
}