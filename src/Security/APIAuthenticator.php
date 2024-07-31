<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class APIAuthenticator extends AbstractAuthenticator
{

    //this method returns true if the request should be handled by this authenticator
    public function supports(Request $request): ?bool
    {
        return  $request->headers->has('Authorization') && str_contains($request->headers->get('Authorization'), 'Bearer');
    }

    //this method is called when supports() returns true
    public function authenticate(Request $request): Passport
    {
        //take the token from the header and put it in $identifier variable and create a new passport
        $identifier = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        return new SelfValidatingPassport(
            new UserBadge($identifier)
        );
    }

    //this method is called when supports() returns true
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse( [
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}
