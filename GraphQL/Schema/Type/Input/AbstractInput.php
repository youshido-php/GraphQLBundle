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
    /** @var  mixed */
    protected $value;

    /** @var bool */
    protected $required = false;

    /** @var array */
    protected $options = [];

    public function __construct($required = false, $options = [])
    {
        $this->required = $required;
        $this->options  = $options;
    }

    abstract function validate(ValidationErrorList $errorList, $name);

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}