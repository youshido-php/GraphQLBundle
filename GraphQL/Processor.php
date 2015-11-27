<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL;


use Youshido\GraphQLBundle\GraphQL\Schema\Validator\ValidatorInterface;
use Youshido\GraphQLBundle\Helper\HelpersContainer;
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

    public function __construct(HelpersContainer $helpersContainer,
                                PreValidatorsContainer $preValidatorsContainer,
                                ValidatorInterface $schemaValidator
    )
    {
        $this->helpersContainer      = $helpersContainer;
        $this->preValidatorContainer = $preValidatorsContainer;
        $this->schemaValidator       = $schemaValidator;
    }


    public function process($query, $arguments = [])
    {
        $this->preProcess();

        try {
            $request = $this->createRequest($query);

            if ($request->hasFragments()) {
                $this->helpersContainer->process($request);
            }

            $this->preValidatorContainer->validate($request, $this->errorList);

            $this->execute($request, $arguments);
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

    protected function execute(Request $request, $arguments = [])
    {
        $querySchema = $this->getQuerySchema();
        $this->schemaValidator->validate($querySchema);

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

//            if (!$querySchema instanceof SchemaInterface) {
            return $querySchema;
//            }
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

    public function getErrors()
    {
        return $this->errorList;
    }

    public function getResponseData()
    {
        if ($this->errorList->hasErrors()) {
            return ['errors' => $this->errorList->toArray()];
        }

        return ['data' => $this->data];
    }
}