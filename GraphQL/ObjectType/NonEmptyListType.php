<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use Youshido\GraphQL\Config\Object\ListTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Exception\ValidationException;
use Youshido\GraphQL\Type\ListType\AbstractListType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

/**
 * Class NonEmptyListType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
class NonEmptyListType extends AbstractListType
{

    /**
     * NonEmptyListType constructor.
     * @param $itemType
     * @throws ConfigurationException
     * @throws ValidationException
     */
    public function __construct($itemType)
    {
        parent::__construct();

        $this->config = new ListTypeConfig(['itemType' => $itemType], $this, true);
    }

    /**
     * @return callable|mixed|AbstractObjectType|null
     */
    public function getItemType()
    {
        return $this->getConfig()->get('itemType');
    }

    /**
     * @return false|string|null
     */
    public function getName()
    {
        return null;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return parent::isValidValue($value) && ($value === null || !empty($value));
    }

    /**
     * @param null $value
     *
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        if (empty($value)) {
            return 'The list type is empty';
        }

        return parent::getValidationError($value);
    }

}
