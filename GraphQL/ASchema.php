<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL;

use BastSys\GraphQLBundle\Field\ISecuredField;
use BastSys\GraphQLBundle\GraphQL\Field\IFreeOperation;
use BastSys\GraphQLBundle\Security\Voter\FreeFieldVoter;
use BastSys\GraphQLBundle\Security\Voter\SecuredFieldVoter;
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
     * @var FreeFieldVoter
     */
    private FreeFieldVoter $freeOperationVoter;

    /** @var SecuredFieldVoter */
    private SecuredFieldVoter $securedFieldVoter;

    /**
     * ASchema constructor.
     * @param AbstractObjectType $queryType
     * @param AbstractObjectType $mutationType
     * @param FreeFieldVoter $freeOperationVoter
     * @param SecuredFieldVoter $securedFieldVoter
     */
    public function __construct(AbstractObjectType $queryType, AbstractObjectType $mutationType, FreeFieldVoter $freeOperationVoter, SecuredFieldVoter $securedFieldVoter)
    {
        // before __construct build
        $this->queryType = $queryType;
        $this->mutationType = $mutationType;
        $this->freeOperationVoter = $freeOperationVoter;
        $this->securedFieldVoter = $securedFieldVoter;

        parent::__construct();
    }

    /**
     * @param SchemaConfig $config
     */
    public final function build(SchemaConfig $config)
    {
        $config->setQuery($this->queryType);
        $config->setMutation($this->mutationType);

        $queries = $config->getQuery()->getFields();
        $mutations = $config->getMutation()->getFields();

        $this->registerFreeFields($queries);
        $this->registerFreeFields($mutations);

        $this->registerSecuredFields($queries);
        $this->registerSecuredFields($mutations);
    }

    /**
     * Checks for free operations
     *
     * @param Field[] $fields
     */
    private function registerFreeFields(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof IFreeOperation) {
                $this->freeOperationVoter->addFreeOperation($field);
            }
        }
    }

    /**
     * @param array $fields
     */
    private function registerSecuredFields(array $fields) {
        foreach ($fields as $field) {
            if($field instanceof ISecuredField) {
                $this->securedFieldVoter->addSecuredField($field);
            }
        }
    }
}
