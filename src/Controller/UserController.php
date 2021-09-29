<?php

namespace App\Controller;

use App\Service\DiscordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(DiscordService $discordService, Security $security): Response
    {
        dd($security->getUser());
//        $discordUser = $discordService->fetchUserInfo()

        return $this->json([]);
    }

}
