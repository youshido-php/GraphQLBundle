<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field;

use BastSys\UtilsBundle\Repository\AEntityRepository;

/**
 * Interface IOneRepositoryField
 * @package BastSys\GraphQLBundle\GraphQL\Field
 * @author  mirkl
 */
interface IOneRepositoryField
{
    /**
     * @return AEntityRepository
     */
    function getFieldRepository(): AEntityRepository;
}
