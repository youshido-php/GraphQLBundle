<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Scalar;


use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class StringType extends AbstractScalar
{

    function validate(ValidationErrorList $errorList, $name)
    {
        if (!is_string($this->getValue())) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected type \"String\"', $name)
            ));
        }
    }

    public function resolve($value = null, $args = [])
    {
        return (string)$value;
    }
}