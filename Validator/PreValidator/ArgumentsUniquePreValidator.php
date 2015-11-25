<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Validator\PreValidator;


use Youshido\GraphQLBundle\GraphQL\Request;
use Youshido\GraphQLBundle\Parser\Ast\Argument;
use Youshido\GraphQLBundle\Parser\Ast\Query;
use Youshido\GraphQLBundle\Validator\Exception\ValidationException;
use Youshido\GraphQLBundle\Validator\ValidationErrorList;

class ArgumentsUniquePreValidator implements PreValidatorInterface
{

    public function validate(Request $request, ValidationErrorList $errorList)
    {
        $this->validateQueriesList($request->getQueries(), $errorList);
    }

    /**
     * @param Query[]             $queries
     * @param ValidationErrorList $errorList
     */
    protected function validateQueriesList($queries, ValidationErrorList $errorList)
    {
        foreach ($queries as $query) {
            if ($query instanceof Query) {
                if ($query->hasArguments()) {
                    $this->validateArguments($query->getArguments(), $errorList);
                }

                $this->validateQueriesList($query->getFields(), $errorList);
            }

        }
    }

    /**
     * @param Argument[]          $arguments
     * @param ValidationErrorList $errorList
     */
    protected function validateArguments($arguments, ValidationErrorList $errorList)
    {
        $names = [];

        foreach ($arguments as $argument) {
            if (in_array($argument->getName(), $names)) {
                $errorList->addError(new ValidationException(
                    sprintf('There can be only one argument named \"%\".', $argument->getName())
                ));

                break;
            } else {
                $names[] = $argument->getName();
            }
        }
    }
}