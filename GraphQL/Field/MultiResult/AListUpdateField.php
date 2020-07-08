<?php

namespace BastSys\GraphQLBundle\GraphQL\Field\MultiResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\Field\IOneRepositoryField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use BastSys\GraphQLBundle\GraphQL\InputType\NonNullMultiUuidInputType;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class AListUpdateField
 * @package BastSys\GraphQLBundle\GraphQL\Field\MultiResult
 * @author mirkl
 */
abstract class AListUpdateField extends ABaseField implements IOneRepositoryField
{
    /**
     * @var TypeInterface
     */
    private $updateInputType;

    /**
     * @param FieldConfig $config
     * @throws ConfigurationException
     */
    public final function build(FieldConfig $config)
    {
        $this->updateInputType = $this->getUpdateInputType();

        $config->addArgument('filter', $this->getFilterType());
        $config->addArgument('input', $this->updateInputType);
    }

    /**
     * @return TypeInterface
     */
    abstract protected function getUpdateInputType(): TypeInterface;

    /**
     * @return TypeInterface
     * @throws ConfigurationException
     */
    protected final function getFilterType(): TypeInterface
    {
        return new NonNullType(
            new NonNullMultiUuidInputType()
        );
    }

    /**
     * @return AbstractType|ListType|AbstractObjectType
     */
    public final function getType()
    {
        return new ListType(
            $this->getItemType()
        );
    }

    /**
     * @return TypeInterface
     */
    abstract protected function getItemType(): TypeInterface;

    /**
     * @param GraphQLRequest $request
     * @return array|IIdentifiableEntity[]|object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public final function handle(GraphQLRequest $request)
    {
        $em = $this->getEntityManager();
        $entities = $this->identifyEntities($request);
        $updatedEntities = [];

        try {
            foreach ($entities as $entity) {
                $updatedEntities[] = $entity;
                $this->updateEntity($entity, $request);
            }
        } catch (Exception $ex) {
            // An exception occurred. All changes must be removed.
            foreach ($updatedEntities as $updatedEntity) {
                $em->refresh($updatedEntity);
            }
            throw $ex;
        }

        if ($em->isOpen()) {
            $em->flush();
        }

        return $entities;
    }

    /**
     * @param GraphQLRequest $request
     * @return array
     * @throws EntityNotFoundByIdException
     */
    protected function identifyEntities(GraphQLRequest $request): array
    {
        $repo = $this->getFieldRepository();
        $ids = $request->getParameter('filter.id');

        $entities = [];
        foreach ($ids as $id) {
            $entities[] = $repo->findById($id, true);
        }

        return $entities;
    }

    /**
     * @param IIdentifiableEntity $entity
     * @param GraphQLRequest $request
     * @throws Exception
     */
    protected function updateEntity(IIdentifiableEntity $entity, GraphQLRequest $request)
    {
        if ($this->updateInputType instanceof IEntityApplicable) {
            $this->updateInputType->applyOnEntity($entity, $request->createSubRequest('input'));
            return;
        }

        $thisClass = get_class($this);
        $applicableInterface = IEntityApplicable::class;
        $updateInputTypeClass = get_class($this->updateInputType);

        throw new Exception("Update input type cannot be applied on given entity. Override 'updateEntity' method in $thisClass or implement $applicableInterface in $updateInputTypeClass.", 501);
    }
}
