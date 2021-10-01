<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\ApiToken;
use App\Service\DiscordService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\DiscordClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Wohali\OAuth2\Client\Provider\DiscordResourceOwner;

class DiscordAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RouterInterface $router,
        protected UserPasswordHasherInterface $passwordHasher,
        protected ClientRegistry $clientRegistry,
        protected DiscordService $discordService,
        protected RequestStack $requestStack,
        protected ?AccessToken $accessToken
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_discord_check';
    }

    /**
     * @return OAuth2ClientInterface
     */
    private function getDiscordClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient(DiscordService::DISCORD_PROVIDER);
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $client = $this->getDiscordClient();
        $this->accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($this->accessToken->getToken(), function () use ($client) {
                /** @var DiscordResourceOwner $userFromToken */
                $userFromToken = $client->fetchUserFromToken($this->accessToken);

                $email = $userFromToken->getEmail();

                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['discordId' => $userFromToken->getId()]);
                if ($existingUser) {
                    return $existingUser;
                }

                $discordUser = $this->discordService->fetchUserInfo($this->accessToken);

                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    $user = new User();
                    $user->setEmail($discordUser->email);
                    $user->setDiscordId($userFromToken->getId());
                    $user->setUsername($discordUser->username);
                    $user->setPassword($this->passwordHasher->hashPassword($user, sha1(random_bytes(30))));
                }

                if ($user->getAvatar() !== $discordUser->avatarHash) {
                    $user->setAvatar($discordUser->avatarHash);
                }

                $apiToken = $this->entityManager->getRepository(ApiToken::class)->findOneBy(['token' => $this->accessToken->getToken(), 'owner' => $user]);
                if (!$apiToken) {
                    $apiToken = new ApiToken($user);
                    $apiToken->setToken($this->accessToken->getToken());
                    $apiToken->setRefreshToken($this->accessToken->getRefreshToken());
                    $apiToken->setExpiresAt((new DateTime())->setTimestamp($this->accessToken->getExpires()));
                }

                if ($this->accessToken->hasExpired()) {
                    $this->accessToken = $client->refreshAccessToken($this->accessToken->getRefreshToken());
                    $apiToken->setToken($this->accessToken->getToken());
                    $apiToken->setRefreshToken($this->accessToken->getRefreshToken());
                    $apiToken->setExpiresAt((new DateTime())->setTimestamp($this->accessToken->getExpires()));
                }

                $this->entityManager->persist($apiToken);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        $this->requestStack->getSession()->set(DiscordService::DISCORD_PROVIDER, $this->accessToken);
     
        return new RedirectResponse($this->router->generate('index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    // public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    // {
    //     return new RedirectResponse(
    //         $this->router->generate('connect_discord_start'),
    //         Response::HTTP_TEMPORARY_REDIRECT
    //     );
    // }
}