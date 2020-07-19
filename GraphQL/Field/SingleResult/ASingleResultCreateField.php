<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field\SingleResult;

use BastSys\GraphQLBundle\GraphQL\Field\ABaseField;
use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\InputTypeInterface;
use Youshido\GraphQL\Type\NonNullType;

/**
 * Class ASingleResultCreateField
 * @package BastSys\GraphQLBundle\GraphQL\Field\SingleResult
 * @author  mirkl
 */
abstract class ASingleResultCreateField extends ABaseField
{
    /** @var InputTypeInterface */
    private $updateInputType;

    public function __construct()
    {
        $this->updateInputType = $this->getCreateInputType();

        parent::__construct();
    }

    /**
     * @return InputTypeInterface
     */
    abstract protected function getCreateInputType(): InputTypeInterface;

    /**
     * @param FieldConfig $config
     *
     * @throws ConfigurationException
     */
    public function build(FieldConfig $config)
    {
        $config->addArgument('input', new NonNullType($this->updateInputType));
    }

    /**
     * @param GraphQLRequest $request
     *
     * @return array|object
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function handle(GraphQLRequest $request)
    {
        $em = $this->getEntityManager();

        $entity = $this->createEntity($request);
        $em->persist($entity);

        try {
            $this->updateEntity($entity, $request);
        } catch (Exception $ex) {
            $em->detach($entity);
            throw $ex;
        }

        $em->flush();

        return $entity;
    }

    /**
     * @param GraphQLRequest $request
     *
     * @return object
     */
    abstract protected function createEntity(GraphQLRequest $request): object;

    /**
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
