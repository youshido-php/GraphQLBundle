<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Validator\PreValidator;


use Youshido\GraphQLBundle\GraphQL\Request;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

interface PreValidatorInterface
{

    public function validate(Request $request, ValidationErrorList $errorList);

}