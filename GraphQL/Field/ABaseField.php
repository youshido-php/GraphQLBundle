<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\Field;

use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\UtilsBundle\Model\Strings;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQLBundle\Field\AbstractContainerAwareField;

/**
 * Class ABaseField
 * @package BastSys\GraphQLBundle\GraphQL\Field
 * @author mirkl
 */
abstract class ABaseField extends AbstractContainerAwareField
{
    /**
     * Gets doctrine entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * Gets doctrine repository of given entity
     *
     * @param string $entityClass
     *
     * @return AEntityRepository
     */
    public function getRepository(string $entityClass): AEntityRepository
    {
        return $this->container->get(
            Strings::getEntityRepositoryServiceName($entityClass)
        );
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        $tokenStorage = $this->container->get('security.token_storage');

        return $tokenStorage->getToken()->getUser();
    }

    /**
     * A method that is called from Youshido GraphQL bundle
     * @param $value
     * @param array $args
     * @param ResolveInfo $info
     * @return array|object
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        $gqlRequest = new GraphQLRequest($this->container, $value, $args, $info);

        return $this->handle($gqlRequest);
    }

    /**
     * @param GraphQLRequest $request
     *
     * @return object|array response or entity that the response is created from
     */
    public abstract function handle(GraphQLRequest $request);
}
