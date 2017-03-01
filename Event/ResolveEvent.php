<?php

namespace Youshido\GraphQLBundle\Event;

use Symfony\Component\EventDispatcher\GenericEvent;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Field;

class ResolveEvent extends GenericEvent
{
    /**
     * @var Field */
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
        parent::__construct('ResolveEvent', [$field, $astFields]);
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
