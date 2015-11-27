<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


use Youshido\GraphQLBundle\GraphQL\Schema\InputValue\InputValue\AbstractInput;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

abstract class AbstractInputType extends AbstractInput implements TypeInterface
{

    /**
     * @inheritdoc
     */
    public function getFields()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getArguments()
    {
        return [];
    }

}