<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use BastSys\UtilsBundle\Model\Strings;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class RegExpStringType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
abstract class RegExpStringType extends StringType
{
    use TDynamicName;

    /**
     * @var string
     */
    private $regExp;
    /**
     * @var bool
     */
    private $accentFold;

    /**
     * RegExpStringType constructor.
     * @param string $regExp
     * @param bool $accentFold if true, special characters are converted to [A-Za-z] before performing regex test
     */
    public function __construct(string $regExp, bool $accentFold = false)
    {
        $this->regExp = $regExp;
        $this->accentFold = $accentFold;
    }

    /**
     * @param $value
     * @return bool
     */
    public final function isValidValue($value)
    {
        return is_null($value) || (
                is_string($value) &&
                preg_match($this->regExp, $this->accentFold ? Strings::accentFold($value) : $value)
            );
    }

    /**
     * @param null $value
     * @return string|null
     */
    public final function getValidationError($value = null)
    {
        return parent::getValidationError($value) ?? "Value ($value) does not match /$this->regExp/";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $name = $this->getName();
        $re = $this->regExp;

        $possibleNot = !$this->accentFold ? ' not' : ' ';
        return parent::getDescription() . " Represents $name type. Value must match $re. Special characters are$possibleNot converted to [A-Za-z]";
    }
}
