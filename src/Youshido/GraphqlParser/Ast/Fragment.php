<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class Fragment
{

    protected $name;

    protected $model;

    /** @var Field[]|Query[] */
    protected $children;

    /**
     * Fragment constructor.
     *
     * @param                 $name
     * @param                 $model
     * @param Field[]|Query[] $children
     */
    public function __construct($name, $model, $children)
    {
        $this->name     = $name;
        $this->model    = $model;
        $this->children = $children;
    }


}