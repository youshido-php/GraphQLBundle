<?php
declare(strict_types=1);

namespace Youshido\GraphQLBundle\Event;

use Symfony\Component\EventDispatcher\GenericEvent;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Field;

/**
 * Class ResolveEvent
 * @package Youshido\GraphQLBundle\Event
 * @author mirkl
 */
class ResolveEvent extends GenericEvent
{
    /**
     * @var Field */
    private $field;

    /** @var array */
    private $astFields;
    
    /** @var mixed|null */
    private $resolvedValue;

    /**
     * Constructor.
     *
     * @param FieldInterface $field
     * @param array $astFields
     * @param mixed|null $resolvedValue
     */
    public function __construct(FieldInterface $field, array $astFields, $resolvedValue = null)
    {
        $this->field = $field;
        $this->astFields = $astFields;
        $this->resolvedValue = $resolvedValue;
        parent::__construct('ResolveEvent', [$field, $astFields, $resolvedValue]);
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

    /**
     * Returns the resolved value.
     * 
     * @return mixed|null
     */
    public function getResolvedValue()
    {
        return $this->resolvedValue;
    }

    /**
     * Allows the event listener to manipulate the resolved value.
     * 
     * @param $resolvedValue
     */
    public function setResolvedValue($resolvedValue)
    {
        $this->resolvedValue = $resolvedValue;
    }
}

