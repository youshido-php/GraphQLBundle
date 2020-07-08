<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Youshido\GraphQL\Field\InputFieldInterface;
use Youshido\GraphQL\Parser\Ast\ArgumentValue\InputObject;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\TypeMap;

/**
 * Class AInputObjectType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 */
abstract class AInputObjectType extends AbstractInputObjectType
{
    /**
     * Changed parent::isValidValue because it returns always true if the object is empty
     * This one contains the correct validation method
     *
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        if ($value instanceof InputObject) {
            $value = $value->getValue();
        }

        if (is_null($value)) {
            return true;
        }

        if (!is_array($value)) {
            $this->lastValidationError = 'Value is not array';
            return false;
        }

        $typeConfig = $this->getConfig();
        $requiredFields = array_filter($typeConfig->getFields(), function (InputFieldInterface $field) {
            return $field->getType()->getKind() == TypeMap::KIND_NON_NULL;
        });

        foreach ($value as $valueKey => $valueItem) {
            if (!$typeConfig->hasField($valueKey)) {
                // Schema validation will generate the error message for us.
                return false;
            }

            $field = $typeConfig->getField($valueKey);
            if (!$field->getType()->isValidValue($valueItem)) {
                $error = $field->getType()->getValidationError($valueItem) ?: '(no details available)';
                $this->lastValidationError = sprintf('Not valid type for field "%s" in input type "%s": %s', $field->getName(), $this->getName(), $error);
                return false;
            }

            if (array_key_exists($valueKey, $requiredFields)) {
                unset($requiredFields[$valueKey]);
            }
        }
        if (count($requiredFields)) {
            $this->lastValidationError = sprintf('%s %s required on %s', implode(', ', array_keys($requiredFields)), count($requiredFields) > 1 ? 'are' : 'is', $typeConfig->getName());
        }

        return !(count($requiredFields) > 0);
    }

}
