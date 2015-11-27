<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Object;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

class DoctrineORMObjectType implements TypeInterface
{


    /** @var  string */
    protected $description;

    /** @var  string */
    protected $entityClass = null;

    public function getFields(ListBuilderInterface $builder)
    {

    }

    public function getArguments(ListBuilderInterface $builder)
    {

    }

    /**
     * @param null  $value
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($value = null, $args = [])
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'DoctrineORMObject';
    }
}