<?php
declare(strict_types=1);

/**
 * Date: 23.05.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Config\Rule;

use Youshido\GraphQL\Type\TypeService;
use Youshido\GraphQL\Validator\ConfigValidator\Rules\TypeValidationRule as BaseTypeValidationRule;

/**
 * Class TypeValidationRule
 * @package Youshido\GraphQLBundle\Config\Rule
 * @author mirkl
 */
class TypeValidationRule extends BaseTypeValidationRule
{

    /**
     * @param $data
     * @param $ruleInfo
     * @return bool
     */
    public function validate($data, $ruleInfo)
    {
        if (!is_string($ruleInfo)) {
            return false;
        }

        if (($ruleInfo == TypeService::TYPE_CALLABLE) && (
                is_callable($data) ||
                (is_array($data) && count($data) == 2 && substr($data[0], 0, 1) == '@'))
        ) {
            return true;
        }
        return parent::validate($data, $ruleInfo);
    }


}
