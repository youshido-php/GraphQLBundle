
<?php
/**
 * Date: 23.05.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Field;

use Youshido\GraphQL\Type\Object\AbstractObjectType;


class ContainerAwareField extends AbstractContainerAwareField
{

    /**
     * @return AbstractObjectType
     */
    public function getType()
    {
        return $this->getConfigValue('type');
    }
}