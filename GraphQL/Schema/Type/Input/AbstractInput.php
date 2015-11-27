<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\InputValue\InputValue;


use Youshido\GraphQLBundle\Validator\ValidationErrorList;

abstract class AbstractInput
{
    /** @var array */
    protected $options = [];

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    abstract function parseValue($value, $name, ValidationErrorList $errorList);

}