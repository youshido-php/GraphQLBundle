<?php

namespace Youshido\GraphQLBundle\Event;

use Youshido\GraphQL\Field\FieldInterface;

class ResolveEvent
{
    /**
     * @var FieldInterface */
    private $field;

    /** @var array */
    private $astFields;

    /**
     * Constructor.
     *
     * @param FieldInterface $field
     * @param array $astFields
     */
    public function __construct(FieldInterface $field, array $astFields)
    {
        $this->field = $field;
        $this->astFields = $astFields;
    }

    /**
     * Returns the field.
     *
     * @return FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Returns the AST fields.
     *
     * @return array
     */
    public function getAstFields()
    {
        return $this->astFields;
    }
}
