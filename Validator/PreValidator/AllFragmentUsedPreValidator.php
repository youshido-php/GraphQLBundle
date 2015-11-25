<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Validator\PreValidator;


use Youshido\GraphQLBundle\GraphQL\Request;
use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class AllFragmentUsedPreValidator implements PreValidatorInterface
{

    public function validate(Request $request, ValidationErrorList $errorList)
    {
        if ($request->hasFragments()) {
            foreach ($request->getFragments() as $fragment) {
                if (!$fragment->isUsed()) {
                    $errorList->addError(new ValidationException(
                        sprintf('Fragment \"%\" is never used.', $fragment->getName())
                    ));
                }
            }
        }
    }
}