<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field\MultiResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\Field\IOneRepositoryField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class ARawListResultField
 *
 * Returns raw array result containing all items
 *
 * @package BastSys\GraphQLBundle\GraphQL\Field
 */
abstract class ARawListResultField extends ABaseField implements IOneRepositoryField
{
    /**
     * Creates raw list (array) type
     *
     * @return AbstractType|ListType|AbstractObjectType
     * @throws ConfigurationException
     */
    public function getType()
    {
        return new NonNullType(new ListType(
            new NonNullType($this->getListElementType())
        ));
    }

    /**
     * @return TypeInterface
     */
    abstract function getListElementType(): TypeInterface;

    /**
     * Handles the request, returns all results
     *
     * @param GraphQLRequest $request
     *
     * @return object[]
     */
    public function handle(GraphQLRequest $request)
    {
        return $this->identifyEntities($request);
    }

    /**
     * Override this method to customize find algorithm
     *
     * @param GraphQLRequest $request
     * @return array
     */
    protected function identifyEntities(GraphQLRequest $request): array
    {
        return $this->getFieldRepository()->getObjectRepository()->findAll();
    }
}
