<?php

namespace Youshido\GraphQLBundle\Event;

use Youshido\GraphQL\Parser\Ast\Field;

class ResolveEvent
{
    /**
     * @var Field */
    private $field;

    /** @var array */
    private $astFields;

    /**
     * Constructor.
     *
     * @param Field $field
     * @param array $astFields
     */
    public function __construct(Field $field, array $astFields)
    {
        $this->field = $field;
        $this->astFields = $astFields;
    }

    /**
     * Returns the field.
     *
     * @return Field
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
