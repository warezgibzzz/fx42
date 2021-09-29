<?php

namespace App\Controller;

use App\Security\DiscordAuthenticator;
use App\Service\DiscordService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DiscordController extends AbstractController
{
    /**
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    #[Route('/connect/discord', name: 'connect_discord_start')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient(DiscordService::DISCORD_PROVIDER) // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'identify',
                'email',
                'guilds',
                'guilds.join'
            ]);
    }

    #[Route('/connect/discord/check', name: 'connect_discord_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
    }
}
