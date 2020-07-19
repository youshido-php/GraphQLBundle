<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL;

use ArrayAccess;
use BastSys\GraphQLBundle\Exception\Process\GraphQLRequiredParameterException;
use BastSys\GraphQLBundle\Exception\Process\GraphQLRequiredTranslationParameterException;
use BastSys\GraphQLBundle\GraphQL\InputType\IEntityApplicable;
use BastSys\LocaleBundle\Entity\Translation\ITranslatable;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundException;
use BastSys\UtilsBundle\Exception\NotImplementedException;
use BastSys\UtilsBundle\Model\DimensionalArray;
use BastSys\UtilsBundle\Model\Lists\Input\FieldDirectionPair;
use BastSys\UtilsBundle\Model\Lists\Input\OrderBy;
use BastSys\UtilsBundle\Model\Lists\Input\OrderByDirection;
use BastSys\UtilsBundle\Model\Lists\Input\Pagination;
use BastSys\UtilsBundle\Model\Strings;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Youshido\GraphQL\Execution\ResolveInfo;

/**
 * Class GraphQLRequest
 * @package BastSys\GraphQLBundle\GraphQL
 * @author mirkl
 */
class GraphQLRequest implements ArrayAccess
{
    /** @var ContainerInterface */
    private ContainerInterface $container;
    /** @var EntityManager|null entity manager if loaded; if not loaded, is loaded when requested */
    private ?EntityManager $entityManager;
    /** @var */
    private $value;
    /** @var DimensionalArray */
    private DimensionalArray $args;
    /** @var ResolveInfo */
    private ResolveInfo $info;

    /**
     * GraphQLRequest constructor.
     *
     * @param ContainerInterface $container
     * @param                    $value
     * @param array $args
     * @param ResolveInfo $info
     */
    public function __construct(ContainerInterface $container, $value, array $args, ResolveInfo $info)
    {
        $this->container = $container;
        $this->value = $value;
        $this->args = new DimensionalArray($args);
        $this->info = $info;
    }

    /**
     * @param GraphQLRequest $request
     * @return Pagination
     */
    public static function createPagination(GraphQLRequest $request): Pagination
    {
        return new Pagination(
            $request->getParameter('pagination.offset', 0),
            $request->getParameter('pagination.limit', 50)
        );
    }

    /**
     * Performs search in dimensional array of arguments
     *
     * @param string $key
     * @param null $defaultValue
     *
     * @return mixed
     */
    public function getParameter(string $key, $defaultValue = null)
    {
        return $this->args->get($key, $defaultValue);
    }

    /**
     * @param GraphQLRequest $request
     * @return OrderBy
     */
    public static function createOrderBy(GraphQLRequest $request): OrderBy
    {
        $fieldDirectionInput = $request->getParameter('orderBy', []);

        $orderByPairs = [];
        foreach ($fieldDirectionInput as $rawPair) {
            $orderByPairs[] = new FieldDirectionPair(
                $rawPair['field'],
                isset($rawPair['direction']) ? $rawPair['direction'] : OrderByDirection::getOptions()[0]
            );
        }

        return new OrderBy($orderByPairs);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        }

        return $this->entityManager;
    }

    /**
     * @return Request
     */
    public function getHttpRequest(): Request
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * @return DimensionalArray
     */
    public function getParameters(): DimensionalArray
    {
        return $this->args;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->hasParameter($offset);
    }

    /**
     * Performs check in dimensional array of arguments
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasParameter(string $key): bool
    {
        return $this->args->has($key);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getParameter($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws NotImplementedException
     */
    public function offsetSet($offset, $value)
    {
        throw new NotImplementedException();
    }

    /**
     * @param mixed $offset
     * @throws NotImplementedException
     */
    public function offsetUnset($offset)
    {
        throw new NotImplementedException();
    }

    /**
     * Processes a key of an entity using sub-entity applicable.
     *
     * @param string $key
     * @param object $entity
     * @param IEntityApplicable $applicable
     */
    public function processSubEntity(string $key, object $entity, IEntityApplicable $applicable)
    {
        $this->processAsSubRequest($key, function (GraphQLRequest $subRequest) use ($key, $entity, $applicable) {
            $subEntityGetter = Strings::getGetterName($key);
            $subEntity = $entity->$subEntityGetter();
            if (!$subEntity) {
                throw new EntityNotFoundException('Cannot process a sub entity, that does not exist');
            }

            $applicable->applyOnEntity($subEntity, $subRequest);
        });
    }

    /**
     * @param string $key
     * @param callable $process function(GraphQLRequest $subRequest)
     */
    public function processAsSubRequest(string $key, callable $process)
    {
        if ($this->hasNonNullParameter($key)) {
            $subRequest = $this->createSubRequest($key);

            $process($subRequest);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasNonNullParameter(string $key): bool
    {
        return $this->hasParameter($key) && !is_null($this->getParameter($key));
    }

    /**
     * Creates a sub request that contains all the information from the previous one, but uses submerged arguments
     *
     * @param string $key
     *
     * @return GraphQLRequest
     */
    public function createSubRequest(string $key): GraphQLRequest
    {
        $subArgs = $this->getParameter($key);
        if (!is_array($subArgs)) {
            throw new InvalidArgumentException("Invalid sub parameters for key '$key'");
        }

        $subRequest = new GraphQLRequest(
            $this->container,
            $this->value,
            $subArgs,
            $this->info
        );
        $subRequest->entityManager = $this->entityManager;

        return $subRequest;
    }

    /**
     * Processes array parameter found at $key as sub requests
     *
     * @param string $key
     * @param callable $process function(GraphQLRequest $request)
     */
    public function processAsSubRequests(string $key, callable $process): void
    {
        $parameters = $this->getParameter($key);

        if ($parameters) {
            foreach ($parameters as $parameter) {
                $process(
                    new GraphQLRequest($this->container, $this->value, $parameter, $this->info)
                );
            }
        }
    }

    /**
     * Sets value of given parameter if contained to given entity using normalized setter.
     * E.g. for key === 'name', method 'setName' is chosen.
     *
     * @param string $fieldName Must not contain '.'
     * @param object $entity Entity to set the value at. Must contain setter for this parameter
     * @param bool $required If true and parameter is not included in the request, GraphQLRequiredParameterException
     *                          is thrown
     *
     * @throws InvalidArgumentException
     * @throws GraphQLRequiredParameterException
     */
    public function processEntityField(string $fieldName, $entity, bool $required = false)
    {
        if (preg_match('/\./', $fieldName) > 0) {
            throw new InvalidArgumentException("Key in this method must not contain '.'", 500);
        }

        if ($this->hasNonNullParameter($fieldName)) {
            $value = $this->getParameter($fieldName);
            $setMethodName = Strings::getSetterName($fieldName);
            $entity->$setMethodName($value);
        } else if ($required) {
            throw new GraphQLRequiredParameterException($fieldName);
        }
    }

    /**
     * @param string $fieldName
     * @param $entity
     */
    public function processEntityDeleteField(string $fieldName, $entity): void
    {
        $this->processIfNull($fieldName, function () use ($fieldName, $entity) {
            $setMethodName = Strings::getSetterName($fieldName);
            $entity->$setMethodName(null);
        });
    }

    /**
     * Processes parameter if it is defined && its value is null
     *
     * @param string $key
     * @param callable $process
     */
    public function processIfNull(string $key, callable $process): void
    {
        if ($this->hasParameter($key) && $this->getParameter($key) === null) {
            $process();
        }
    }

    /**
     * Processed id entity field. Tries to find entity by id in its repository and then estabilishes a connection via setter
     *
     * @param string $idFieldName
     * @param string $connectedEntityClass
     * @param $entity
     * @param bool $required
     * @throws GraphQLRequiredParameterException
     * @throws EntityNotFoundByIdException
     */
    public function processEntityIdConnectionField(string $idFieldName, string $connectedEntityClass, $entity, bool $required = false)
    {
        if (preg_match('/\./', $idFieldName) > 0) {
            throw new InvalidArgumentException("Key in this method must not contain '.'", 500);
        }

        if ($this->hasNonNullParameter($idFieldName)) {
            $id = $this->getParameter($idFieldName);
            /** @var AEntityRepository $repository */
            $repository = $this->getContainer()->get(
                Strings::getEntityRepositoryServiceName($connectedEntityClass)
            );
            $connectionEntity = $repository->findById($id, true);

            $setMethodName = Strings::getSetterName($idFieldName);
            $entity->$setMethodName($connectionEntity);
        } else if ($required) {
            throw new GraphQLRequiredParameterException($idFieldName);
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Processes translated parameter for ITranslatable entity. Can affect either none or multiple translations.
     *
     * @param string $fieldName name of translated field, must not contain '.'
     * @param ITranslatable $entity
     * @param bool $required if true and none translation field was affected by translation input,
     *                                 GraphQLRequiredTranslationParameterException is thrown
     *
     * @throws GraphQLRequiredTranslationParameterException
     */
    public function processEntityTranslatedField(string $fieldName, ITranslatable $entity, bool $required = false): void
    {
        $translationInputs = $this->getParameter('translations', []);
        $oneChanged = false;
        $translationFieldSetter = Strings::getSetterName($fieldName);

        foreach ($translationInputs as $translationInput) {
            if (isset($translationInput[$fieldName])) {
                $locale = $translationInput['locale'];
                $translation = $entity->getTranslation($locale);

                $translation->$translationFieldSetter(
                    $translationInput[$fieldName]
                );
                $oneChanged = true;
            }
        }

        if ($required && !$oneChanged) {
            throw new GraphQLRequiredTranslationParameterException($fieldName);
        }
    }

    /**
     * Processes parameter if its value is true. Uses basic processParameter function
     *
     * @param string $key
     * @param callable $process function($parameter)
     */
    public function processIfTrue(string $key, callable $process): void
    {
        $parameter = $this->getParameter($key);
        if ($parameter === true) {
            $process();
        }
    }

    /**
     * Calls $process function if $key parameter is contained.
     * Null value is considered as undefined.
     *
     * @param string $key
     * @param callable $process
     * @param bool $required
     *
     * @throws GraphQLRequiredParameterException
     */
    public function processParameter(string $key, callable $process, $required = false): void
    {
        if ($this->hasNonNullParameter($key)) {
            $value = $this->getParameter($key);
            $process($value);
        } else if ($required) {
            throw new GraphQLRequiredParameterException($key);
        }
    }

    /**
     * Processed parameter.
     * Throws GraphQLRequiredParameterException if not defined.
     * Null value is considered undefined.
     *
     * @param string $key
     * @param callable $process
     *
     * @throws GraphQLRequiredParameterException thrown when parameter not included in request
     */
    public function processRequired(string $key, callable $process): void
    {
        if (!$this->hasNonNullParameter($key)) {
            throw new GraphQLRequiredParameterException($key);
        }

        $process(
            $this->getParameter($key)
        );
    }
}
