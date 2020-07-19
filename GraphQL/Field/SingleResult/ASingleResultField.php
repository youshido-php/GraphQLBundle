<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field\SingleResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\Field\IOneRepositoryField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\NonNullUuidInputType;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\NonNullType;

/**
 * A field that allows to get an entity by its id
 */
abstract class ASingleResultField extends ABaseField implements IOneRepositoryField
{
    /**
     * @param FieldConfig $config
     * @throws ConfigurationException
     */
    public final function build(FieldConfig $config)
    {
        $filterType = $this->getFilter();
        if ($filterType) {
            $config->addArgument('filter', [
                'type' => $filterType,
                'description' => 'AFilter applied when searching for entity'
            ]);
        }
    }

    /**
     * @return AbstractType
     * @throws ConfigurationException
     */
    protected function getFilter(): ?AbstractType
    {
        return new NonNullType(
            new NonNullUuidInputType()
        );
    }

    /**
     * @param GraphQLRequest $request
     * @return array|object|null
     * @throws EntityNotFoundByIdException
     */
    public final function handle(GraphQLRequest $request)
    {
        return $this->identifyEntity($request);
    }

    /**
     * Identifies entity that should be returned
     *
     * @param GraphQLRequest $request
     * @return mixed
     * @throws EntityNotFoundByIdException
     */
    protected function identifyEntity(GraphQLRequest $request)
    {
        $id = $request->getParameter("filter.id");
        $repo = $this->getFieldRepository();

        return $repo->findById($id, true);
    }

    /**
     * Gets repository to perform 'id' search on
     *
     * @return AEntityRepository
     */
    public abstract function getFieldRepository(): AEntityRepository;
}
