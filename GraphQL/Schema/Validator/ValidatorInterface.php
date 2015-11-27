<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Validator;


use Youshido\GraphQLBundle\GraphQL\Schema\SchemaInterface;

interface ValidatorInterface
{

    public function validate(SchemaInterface $schema);

}