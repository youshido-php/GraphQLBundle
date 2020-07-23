<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class AGraphQLAuthenticator
 *
 * Extend this class to login a user via graphql
 *
 * @package BastSys\GraphQLBundle\Security
 * @author mirkl
 */
abstract class AGraphQLAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var string[]
     */
    private array $responseHeaders;

    /**
     * AGraphQLAuthenticator constructor.
     * @param string[] $responseHeaders headers contained in every graphql response
     */
    public function __construct(array $responseHeaders = [])
    {
        $this->responseHeaders = $responseHeaders;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->getMethod() === 'OPTIONS') {
            return null;
        }

        if ($this->allowUnauthorisedUser($request)) {
            return null; // Request does not contain auth token - means that user wants to use only free operations
        }

        // Authorization is required
        return new JsonResponse([
            'graphQLErrors' => [
                [
                    'code' => 401,
                    'message' => 'Authentication required'
                ]
            ]
        ], 401, $this->responseHeaders);
    }

    /**
     * @param Request $request
     * @return bool whether unauthorised user should be allowed to stay
     */
    protected abstract function allowUnauthorisedUser(Request $request): bool;

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null; // user remains authenticated
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     *
     * @return Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'graphQLErrors' => [
                [
                    'code' => 401,
                    'message' => 'Authentication required'
                ]
            ]
        ], 401, $this->responseHeaders);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param Request $request
     * @return ['Authorization' => string|null, 'bearerToken' => string|null]
     */
    public function getCredentials(Request $request)
    {
        $authorization = $request->headers->get('Authorization');

        $bearerToken = null;
        $bearerMatch = [];
        if(preg_match('/^Bearer (\w+)$/', $authorization ?? '', $bearerMatch)) {
            $bearerToken = $bearerMatch[0];
        }

        return [
            'Authorization' => $authorization,
            'bearerToken' => $bearerToken
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // user is found by an API token
        return true;
    }
}
