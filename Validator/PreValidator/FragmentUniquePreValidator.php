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

class FragmentUniquePreValidator implements PreValidatorInterface
{

    public function validate(Request $request, ValidationErrorList $errorList)
    {
        if ($request->hasFragments()) {
            $names = [];

            foreach ($request->getFragments() as $fragment) {
                if (in_array($fragment->getName(), $names)) {
                    $errorList->addError(new ValidationException(
                        sprintf('There can only be one fragment named \"%s\"', $fragment->getName())
                    ));

                    break;
                } else {
                    $names[] = $fragment->getName();
                }
            }
        }
    }
}