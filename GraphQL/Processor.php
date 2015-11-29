<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL;


use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Youshido\GraphQLBundle\GraphQL\Builder\FieldListBuilder;
use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\SchemaInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\ListType;
use Youshido\GraphQLBundle\Parser\Ast\Field;
use Youshido\GraphQLBundle\Parser\Ast\Query;
use Youshido\GraphQLBundle\Parser\Parser;
use Youshido\GraphQLBundle\Parser\SyntaxErrorException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class Processor
{

    /** @var ValidationErrorList */
    private $errorList;

    /** @var  array */
    private $data = [];

    /** @var PropertyAccessor */
    private $propertyAccessor;

    public function __construct(SchemaInterface $schema)
    {
        $this->schema = $schema;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function processQuery($query, $variables = [])
    {
        $this->errorList = new ValidationErrorList();

        $request = $this->createRequest($query);

        $this->executeQueries($request, $variables);
    }

    /**
     * @param $query string
     *
     * @throws SyntaxErrorException
     *
     * @return Request
     */
    private function createRequest($query)
    {
        $parser = new Parser($query);

        return $parser->parse();
    }

    protected function executeQueries(Request $request, $variables = [])
    {
        $this->data = [];

        $fieldListBuilder = new FieldListBuilder();
        $this->schema->getFields($fieldListBuilder);

        foreach ($request->getQueries() as $query) {
            $this->data = array_merge($this->data, $this->executeQuery($fieldListBuilder, $query));
        }
    }

    /**
     * @param ListBuilderInterface $listBuilder
     * @param Query|Field          $query
     * @param null                 $value
     *
     * @return array
     */
    protected function executeQuery(ListBuilderInterface $listBuilder, $query, $value = null)
    {
        if ($listBuilder->has($query->getName())) {
            $querySchema = $listBuilder->get($query->getName());

            $fieldListBuilder = new FieldListBuilder();
            $querySchema->getType()->getFields($fieldListBuilder);

            if ($query instanceof Field) {
                $alias = $query->getName();

                $preResolvedValue = $this->getPreResolvedValue($value, $query);
                $value            = $querySchema->getType()->resolve($preResolvedValue, []);
            } else {
                //todo: replace variables with arguments
                //todo: here check arguments

                $value = [];
                $alias = $query->hasAlias() ? $query->getAlias() : $query->getName();

                $resolvedValue = $querySchema->getType()->resolve($value, $query->getArguments());

                //todo: check result is equal to type

                if ($querySchema->getType() instanceof ListType) {
                    foreach ($resolvedValue as $resolvedValueItem) {
                        $value[$alias][] = [];
                        $index           = count($value[$alias]) - 1;

                        foreach ($query->getFields() as $field) {
                            $value[$alias][$index] = array_merge($value[$alias][$index], $this->executeQuery($fieldListBuilder, $field, $resolvedValueItem));
                        }
                    }
                } else {
                    foreach ($query->getFields() as $field) {
                        $value = array_merge($value, $this->executeQuery($fieldListBuilder, $field, $resolvedValue));
                    }
                }
            }

            return [$alias => $value];
        } else {
            $this->errorList->addError(new \Exception(
                sprintf('Field "%s" not found in schema', $query->getName())
            ));
        }

        return null;
    }

    /**
     * @param $value
     * @param $query Field
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function getPreResolvedValue($value, $query)
    {
        if (is_array($value)) {
            if (array_key_exists($query->getName(), $value)) {
                return $value[$query->getName()];
            } else {
                throw new \Exception('Not found in resolve result', $query->getName());
            }
        } elseif (is_object($value)) {
            return $this->propertyAccessor->getValue($value, $query->getName());
        }

        return $value;
    }

    public function getResponseData()
    {
        $result = [];

        if ($this->data) {
            $result['data'] = $this->data;
        }

        if ($this->errorList->hasErrors()) {
            $result['errors'] = $this->errorList->toArray();
        }

        return $result;
    }
}