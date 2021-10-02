<?php

namespace App\Controller;

use App\Service\DiscordService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/profile', name: 'profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(RequestStack $requestStack, DiscordService $discordService): Response
    {
        $session = $requestStack->getSession();

        return $this->json([
            'discordToken' => $session->get(DiscordService::DISCORD_PROVIDER),
            'discordUser' => $discordService->fetchUserInfo($session->get(DiscordService::DISCORD_PROVIDER)),
            'user' => $this->getUser()
        ]);
    }

}
