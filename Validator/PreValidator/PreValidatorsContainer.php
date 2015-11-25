<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Validator\PreValidator;


use Youshido\GraphQLBundle\GraphQL\Request;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class PreValidatorsContainer implements PreValidatorInterface
{

    /** @var PreValidatorInterface[] */
    private $preValidators = [];

    public function addPreValidator(PreValidatorInterface $preValidator)
    {
        $this->preValidators[] = $preValidator;
    }

    public function validate(Request $request, ValidationErrorList $errorList)
    {
        foreach ($this->preValidators as $preValidator) {
            $preValidator->validate($request, $errorList);
        }
    }
}