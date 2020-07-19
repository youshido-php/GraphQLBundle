<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field\SingleResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\Field\IOneRepositoryField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use BastSys\GraphQLBundle\GraphQL\InputType\NonNullUuidInputType;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\InputTypeInterface;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class ASingleResultUpdateField
 * @package BastSys\GraphQLBundle\GraphQL\Field\SingleResult
 * @author mirkl
 */
abstract class ASingleResultUpdateField extends ABaseField implements IOneRepositoryField
{
    /** @var InputTypeInterface */
    private $updateInputType;

    public function __construct()
    {
        // needed before construct
        $this->updateInputType = $this->getUpdateInputType();

        parent::__construct();

    }

    /**
     * Gets update input type that is used to get data for the mutation
     *
     * @return InputTypeInterface
     */
    protected abstract function getUpdateInputType(): InputTypeInterface;

    /**
     * @param FieldConfig $config
     *
     * @throws ConfigurationException
     */
    public function build(FieldConfig $config)
    {
        $filterType = $this->getFilter();
        if ($filterType) {
            $config->addArgument('filter', $filterType);
        }
        $config->addArgument('input', new NonNullType($this->updateInputType));
    }

    /**
     * @return NonNullType
     * @throws ConfigurationException
     */
    protected function getFilter(): ?TypeInterface
    {
        return new NonNullType(new NonNullUuidInputType());
    }

    /**
     * @param GraphQLRequest $request
     *
     * @return object|null
     * @throws Exception
     */
    public function handle(GraphQLRequest $request)
    {
        $entity = $this->identifyEntity($request);
        $em = $this->getEntityManager();
        try {
            $this->updateEntity($entity, $request);
        } catch (Exception $exception) {
            // refresh entity on error - removing all local changed by dtb values
            $em->refresh($entity);
            throw $exception;
        }

        if ($em->isOpen()) {
            try {
                $em->flush();
            } /** @noinspection PhpRedundantCatchClauseInspection */ catch (ForeignKeyConstraintViolationException $ex) {
                throw new Exception('This entity cannot be deleted, because it is used by other entities', 400, $ex);
            }
        }

        return $entity;
    }

    /**
     * @param GraphQLRequest $request
     * @return object
     * @throws EntityNotFoundByIdException
     */
    protected function identifyEntity(GraphQLRequest $request): object
    {
        $repo = $this->getFieldRepository();
        $id = $request->getParameter('filter.id');

        return $repo->findById($id, true);
    }

    /**
     * Updates entity with graphql request. Override this method to custom updates that are not performed via update input type.
     *
     *
     *
     * @param                $entity
     * @param GraphQLRequest $request
     *
     * @throws Exception
     */
    protected function updateEntity($entity, GraphQLRequest $request): void
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
