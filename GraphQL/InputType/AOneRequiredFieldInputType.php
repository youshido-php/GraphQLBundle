<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\Exception\Process\GraphQLSchemaException;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Type\NonNullType;

/**
 * Class AOneRequiredFieldInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 *
 * Input fields that extend this field are valid only when submitting exactly one filter value
 */
abstract class AOneRequiredFieldInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     * @throws GraphQLSchemaException
     */
    public final function build($config)
    {
        $this->buildFields($config);

        foreach ($config->getFields() as $field) {
            if ($field instanceof NonNullType) {
                throw new GraphQLSchemaException('AOneRequiredFieldInputType must not contain required types');
            }
        }
    }

    /**
     * @param InputObjectTypeConfig $config
     */
    protected abstract function buildFields($config): void;

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Implements AOneRequiredFieldInputType. A valid value contains exactly one filled field.";
    }

    /**
     * @param $value
     * @return bool
     */
    public function isValidValue($value)
    {
        return parent::isValidValue($value) &&
            is_array($value) &&
            count($value) === 1;
    }

    /**
     * @param null $value
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        $error = parent::getValidationError($value);
        if ($error) {
            return $error;
        }

        if (!is_array($value)) {
            return 'Value is not an array';
        }

        $count = count($value);
        if ($count !== 1) {
            return "Exactly one field must be submitted. ($count) given";
        }

        return null;
    }
}
