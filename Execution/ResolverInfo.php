<?php


namespace Youshido\GraphQLBundle\Execution;

use Youshido\GraphQL\Execution\ResolveInfo as BaseResolveInfo;
use Youshido\GraphQL\Execution\Context\ExecutionContextInterface;
use Youshido\GraphQL\Field\FieldInterface;

class ResolveInfo extends BaseResolveInfo
{
    /** @var mixed */
    protected $parentValue;

    public function __construct(
        FieldInterface $field,
        array $fieldASTList,
        ExecutionContextInterface $executionContext,
        $parentValue = null
    ) {
        $this->parentValue = $parentValue;
        parent::__construct($field, $fieldASTList, $executionContext);
    }

    /**
     * @return mixed
     */
    public function getParentValue()
    {
        return $this->parentValue;
    }
}