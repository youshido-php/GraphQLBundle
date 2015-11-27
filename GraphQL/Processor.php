<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL;


use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Youshido\GraphQLBundle\GraphQL\Builder\ArgumentListBuilder;
use Youshido\GraphQLBundle\GraphQL\Builder\FieldListBuilder;
use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\SchemaInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\ListType;
use Youshido\GraphQLBundle\GraphQL\Schema\Validator\ValidatorInterface;
use Youshido\GraphQLBundle\Helper\HelpersContainer;
use Youshido\GraphQLBundle\Parser\Ast\Field;
use Youshido\GraphQLBundle\Parser\Ast\Query;
use Youshido\GraphQLBundle\Parser\Parser;
use Youshido\GraphQLBundle\Parser\SyntaxErrorException;
use Youshido\GraphQLBundle\Validator\PreValidator\PreValidatorsContainer;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class Processor
{

    /** @var  HelpersContainer */
    private $helpersContainer;

    /** @var  PreValidatorsContainer */
    private $preValidatorContainer;

    /** @var ValidationErrorList */
    private $errorList;

    /** @var  array */
    private $data;

    /** @var ValidatorInterface */
    private $schemaValidator;

    /** @var  string */
    private $querySchemaClass;

    /** @var PropertyAccessor */
    private $propertyAccessor;

    public function __construct(HelpersContainer $helpersContainer,
                                PreValidatorsContainer $preValidatorsContainer,
                                ValidatorInterface $schemaValidator
    )
    {
        $this->helpersContainer      = $helpersContainer;
        $this->preValidatorContainer = $preValidatorsContainer;
        $this->schemaValidator       = $schemaValidator;

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }


    public function process($query, $variables = [])
    {
        $this->preProcess();

        try {
            $request = $this->createRequest($query);

            if ($request->hasFragments()) {
                $this->helpersContainer->process($request);
            }

            $this->preValidatorContainer->validate($request, $this->errorList);

            $this->execute($request, $variables);
        } catch (\Exception $e) {
            $this->errorList->addError($e);
        }
    }

    private function preProcess()
    {
        $this->errorList = new ValidationErrorList();
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

    protected function execute(Request $request, $variables = [])
    {
        /** @var SchemaInterface $querySchema */
        $querySchema = $this->getQuerySchema();
        $this->schemaValidator->validate($querySchema);

        $fieldListBuilder = new FieldListBuilder();
        $querySchema->getFields($fieldListBuilder);

        $data = [];

        foreach ($request->getQueries() as $query) {
            $this->executeQuery($fieldListBuilder, $query, null, $data);
        }

        $this->data = $data;
    }

    public function getQuerySchema()
    {
        $valid = true;
        if (!$this->getQuerySchemaClass() || !class_exists($this->getQuerySchemaClass())) {
            $valid = false;
        }

        if ($valid) {
            $querySchemaClass = $this->getQuerySchemaClass();
            $querySchema      = new $querySchemaClass();

            if (in_array('Youshido\GraphQLBundle\GraphQL\Schema\SchemaInterface', class_implements($querySchemaClass))) {
                return $querySchema;
            }
        }

        throw new \Exception('Not valid object was set as query schema');
    }

    /**
     * @return string
     */
    public function getQuerySchemaClass()
    {
        return $this->querySchemaClass;
    }

    /**
     * @param string $querySchemaClass
     */
    public function setQuerySchemaClass($querySchemaClass)
    {
        $this->querySchemaClass = $querySchemaClass;
    }

    /**
     * TODO: this code not end result, just test
     *
     * @param ListBuilderInterface $listBuilder
     * @param Query|Field          $query
     * @param null                 $value
     * @param array                $data
     */
    protected function executeQuery(ListBuilderInterface $listBuilder, $query, $value = null, &$data)
    {
        if ($listBuilder->has($query->getName())) {
            $querySchema = $listBuilder->get($query->getName());

            $fieldListBuilder = new FieldListBuilder();
            $querySchema->getType()->getFields($fieldListBuilder);

            if ($query instanceof Field) {
                $preResolvedValue = $this->getPreResolvedValue($value, $query);
                $resolvedValue    = $querySchema->getType()->resolve($preResolvedValue, []);

                $data[$query->getName()] = $resolvedValue;
            } else {
                //todo: replace variables with arguments
                //todo: here check arguments

                $resolvedValue = $querySchema->getType()->resolve($value, $query->getArguments());

                //todo: check result is equal to type

                $valueProperty        = $query->hasAlias() ? $query->getAlias() : $query->getName();
                $data[$valueProperty] = [];

                if ($querySchema->getType() instanceof ListType) {
                    foreach ($resolvedValue as $resolvedValueItem) {
                        $data[$valueProperty][] = [];
                        foreach ($query->getFields() as $field) {
                            $this->executeQuery($fieldListBuilder, $field, $resolvedValueItem, $data[$valueProperty][count($data[$valueProperty]) - 1]);
                        }
                    }
                } else {
                    foreach ($query->getFields() as $field) {
                        $this->executeQuery($fieldListBuilder, $field, $resolvedValue, $data[$valueProperty]);
                    }
                }
            }
        } else {
            $this->errorList->addError(new \Exception(
                sprintf('Field "%s" not found in schema', $query->getName())
            ));
        }
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

    public function getErrors()
    {
        return $this->errorList;
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