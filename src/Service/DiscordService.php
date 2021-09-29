<?php

namespace App\Service;

use Discord\Discord;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class DiscordService
{
    public const DISCORD_PROVIDER = 'discord';

    public function __construct(
        protected OAuth2ClientInterface $discordClient,
        protected ?AccessToken          $accessToken,
    ) {}

    /**
     * @param AccessToken|null $accessToken
     */
    public function setAccessToken(?AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function fetchUserInfo(?AccessToken $accessToken)
    {
        $provider = $this->discordClient->getOAuth2Provider();
        $userRequest = $provider
            ->getRequest(
                Request::METHOD_GET,
                $provider->getResourceOwnerDetailsUrl(
                    $accessToken
                        ?? $this->accessToken
                )
            )
            ->withAddedHeader(
                'Authorization',
                'Bearer ' . ($accessToken ? $accessToken->getToken() : $this->accessToken->getToken())
            );

        $result = $provider->getResponse($userRequest)
            ->getBody()
            ->getContents();

        return (new JsonDecode())->decode($result, JsonEncoder::FORMAT);
    }
}