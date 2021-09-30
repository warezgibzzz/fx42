<?php

namespace App\Controller;

use App\Service\DiscordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/api/profile', name: 'profile')]
    public function profile(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        

        return $this->json([
            'discordToken' => $session->get(DiscordService::DISCORD_PROVIDER),
            'user' => $this->getUser()
        ]);
    }

}
