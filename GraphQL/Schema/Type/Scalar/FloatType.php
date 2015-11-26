<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Scalar;


use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class FloatType extends AbstractScalar
{

    function validate(ValidationErrorList $errorList, $name)
    {
        if (!is_float($this->getValue())) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected type \"Float\"', $name)
            ));
        }
    }

    public function resolve($value = null, $args = [])
    {
        return (float)$value;
    }
}