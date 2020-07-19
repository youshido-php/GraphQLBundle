<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field\MultiResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\IFilterCreator;
use BastSys\GraphQLBundle\GraphQL\InputType\OrderByInputType;
use BastSys\GraphQLBundle\GraphQL\InputType\PaginationInputType;
use BastSys\GraphQLBundle\GraphQL\ObjectType\PageInfoType;
use BastSys\UtilsBundle\Exception\NotImplementedException;
use BastSys\UtilsBundle\Model\Lists\Input\AFilter;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class AListResultField
 * @package BastSys\GraphQLBundle\GraphQL\Field\MultiResult
 * @author mirkl
 */
abstract class AListResultField extends ABaseField
{
    /**
     * @param FieldConfig $config
     */
    public final function build(FieldConfig $config)
    {
        $filterType = $this->getFilterInputType();
        if ($filterType) {
            $config->addArgument('filter', $filterType);
        }

        $config->addArguments([
            'orderBy' => new ListType(
                new OrderByInputType()
            ),
            'pagination' => new PaginationInputType(),
        ]);
    }

    /**
     * @return AbstractInputObjectType|IFilterCreator|null
     */
    public function getFilterInputType(): ?AbstractInputObjectType
    {
        return null;
    }

    /**
     * @return AbstractType|AbstractObjectType|ObjectType
     * @throws ConfigurationException
     */
    public final function getType()
    {
        $listElementType = $this->getListElementType();
        $connectionTypeName = $listElementType->getName() . 'Connection';

        return new NonNullType(new ObjectType([
            'name' => $connectionTypeName,
            'fields' => [
                'totalCount' => [
                    'type' => new NonNullType(new IntType()),
                    'description' => 'Total count of items found by given criteria',
                ],
                'pageInfo' => [
                    'type' => new NonNullType(new PageInfoType()),
                    'description' => 'Information about the current page of returned items',
                ],
                'edges' => [
                    'type' => new NonNullType(new ListType(
                        new NonNullType($listElementType)
                    )),
                    'description' => 'Returned items for this page',
                ],
            ],
        ]));
    }

    /**
     * Gets type of entities returned by this field
     *
     * @return TypeInterface
     */
    public abstract function getListElementType(): TypeInterface;

    /**
     * @param GraphQLRequest $request
     *
     * @return array|object
     * @throws NotImplementedException
     */
    public final function handle(GraphQLRequest $request)
    {
        $fieldRepo = $this->getFieldRepository();

        $pagination = GraphQLRequest::createPagination($request);
        $orderBy = GraphQLRequest::createOrderBy($request);
        $filter = $this->createFilter($request);

        return $fieldRepo->listEntities($pagination, $orderBy, $filter);
    }

    /**
     * Gets the repository this field works with
     *
     * @return AEntityRepository
     */
    public abstract function getFieldRepository(): AEntityRepository;

    /**
     * @param GraphQLRequest $request
     * @return AFilter|null
     * @throws NotImplementedException
     */
    protected function createFilter(GraphQLRequest $request): ?AFilter
    {
        $filterType = $this->getFilterInputType();
        if (!$filterType) {
            return null;
        }

        if ($filterType instanceof IFilterCreator) {
            if (!$request->hasParameter('filter')) {
                return null;
            }

            return $filterType->createFilter($request->createSubRequest('filter'));
        }

        throw new NotImplementedException();
    }
}
