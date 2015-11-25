<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL;


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

    public function __construct(HelpersContainer $helpersContainer,
                                PreValidatorsContainer $preValidatorsContainer)
    {
        $this->helpersContainer      = $helpersContainer;
        $this->preValidatorContainer = $preValidatorsContainer;
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
        /** todo */
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