<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;

use Youshido\GraphQLBundle\GraphQL\Schema\InputValue\InputValue\AbstractInput;
use Youshido\GraphQLBundle\Validator\Exception\ResolveException;
use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class EnumType extends AbstractInput implements TypeInterface
{

    public function __construct($required = false, $options = [])
    {
        parent::__construct($required, $options);

        if (!array_key_exists('values', $this->options) || !is_array($this->options['values']) || !count($this->options['values'])) {
            throw new \Exception('Values need to specified for EnumType');
        }
    }

    function validate(ValidationErrorList $errorList, $name)
    {
        if (!in_array($this->getValue(), $this->options['values'])) {
            $errorList->addError(new ValidationException(
                sprintf('Argument \"%s\" expected value is one of \"%s\"', $name, implode(', ', $this->options['values']))
            ));
        }
    }

    /**
     * @inheritdoc
     */
    public function resolve($value = null, $args = [])
    {
        if (in_array($value, $this->options['values'])) {
            return $value;
        }

        throw new ResolveException(
            sprintf('Value \"%s\" must be in one of \"%s\"', $value, implode(', ', $this->options['values']))
        );
    }
}