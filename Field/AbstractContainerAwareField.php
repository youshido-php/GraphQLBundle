<?php
declare(strict_types=1);

/**
 * Date: 23.05.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Field;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Field\AbstractField as BaseAbstractField;

/**
 * Class AbstractContainerAwareField
 * @package Youshido\GraphQLBundle\Field
 * @author mirkl
 */

/**
 * Class AbstractContainerAwareField
 * @package Youshido\GraphQLBundle\Field
 * @author mirkl
 */
abstract class AbstractContainerAwareField extends BaseAbstractField implements ContainerAwareInterface
{

    use ContainerAwareTrait;

}
