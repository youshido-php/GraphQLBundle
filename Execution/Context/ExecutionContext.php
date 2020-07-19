<?php
declare(strict_types=1);
/**
 * This file is a part of GraphQLBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 9/23/16 11:20 PM
 */

namespace Youshido\GraphQLBundle\Execution\Context;

use Youshido\GraphQL\Execution\Context\ExecutionContext as BaseExecutionContext;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Validator\ConfigValidator\ConfigValidator;
use Youshido\GraphQLBundle\Config\Rule\TypeValidationRule;

/**
 * Class ExecutionContext
 * @package Youshido\GraphQLBundle\Execution\Context
 * @author mirkl
 */
class ExecutionContext extends BaseExecutionContext
{
    /**
     * ExecutionContext constructor.
     * @param AbstractSchema $schema
     */
    public function __construct(AbstractSchema $schema) {
        $validator = ConfigValidator::getInstance();
        $validator->addRule('type', new TypeValidationRule($validator));

        parent::__construct($schema);
    }


}
