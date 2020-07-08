<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Doctrine\DBAL\Types\BooleanType;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;

/**
 * Class ADeleteInputType
 *
 * Contains id that deletes the entity
 *
 * @deprecated use update input type and delete indicator instead
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 */
abstract class ADeleteInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     */
    public final function build($config)
    {
        $this->buildSubFields($config);
    }

    /**
     * Use this function to build sub fields to delete
     *
     * @param InputObjectTypeConfig $config
     */
    protected function buildSubFields($config): void
    {
        $config->addFields([
            'id' => [
                'type' => new BooleanType(),
                'description' => 'Deletes entity'
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Delete input type contains always id. If set to true, the entity is deleted.';
    }
}
