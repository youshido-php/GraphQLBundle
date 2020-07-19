<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL;

use BastSys\GraphQLBundle\GraphQL\Field\IFreeOperation;
use BastSys\GraphQLBundle\Security\Voter\AFreeOperationVoter;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Field\Field;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

/**
 * Class ASchema
 * @package BastSys\GraphQLBundle\GraphQL
 * @author mirkl
 */
abstract class ASchema extends AbstractSchema
{
    /**
     * @var AbstractObjectType
     */
    private AbstractObjectType $queryType;
    /**
     * @var AbstractObjectType
     */
    private AbstractObjectType $mutationType;

    /**
     * @var AFreeOperationVoter
     */
    private AFreeOperationVoter $freeOperationVoter;

    /**
     * ASchema constructor.
     * @param AbstractObjectType $queryType
     * @param AbstractObjectType $mutationType
     * @param AFreeOperationVoter $freeOperationVoter
     */
    public function __construct(AbstractObjectType $queryType, AbstractObjectType $mutationType, AFreeOperationVoter $freeOperationVoter)
    {
        // before __construct build
        $this->queryType = $queryType;
        $this->mutationType = $mutationType;
        $this->freeOperationVoter = $freeOperationVoter;

        parent::__construct();
    }

    /**
     * @param SchemaConfig $config
     */
    public final function build(SchemaConfig $config)
    {
        $config->setQuery($this->queryType);
        $config->setMutation($this->mutationType);

        $this->checkForFreeOperations(
            $config->getQuery()->getFields()
        );
        $this->checkForFreeOperations(
            $config->getMutation()->getFields()
        );
    }

    /**
     * Checks for free operations
     *
     * @param Field[] $fields
     */
    private function checkForFreeOperations(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof IFreeOperation) {
                $this->freeOperationVoter->addFreeOperation(
                    $field->getName()
                );
            }
        }
    }
}
