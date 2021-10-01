<?php

namespace App\Controller;

use App\Service\DiscordService;
use DateTime;
use Grafika\Grafika;
use Intervention\Image\ImageManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use thiagoalessio\TesseractOCR\TesseractOCR;


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
            ->redirect(
                [
                    'identify',
                    'email',
                    'guilds',
                    'guilds.join'
                ],
                []
            );
    }

    #[Route('/connect/discord/check', name: 'connect_discord_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
    }


    #[Route('/ocr', name: 'ocr')]
    public function ocr()
    {
        $tmp = tmpfile();
        $matches = [];
        
        $prep = tmpfile();
        file_put_contents(stream_get_meta_data($prep)['uri'], \file_get_contents('https://media.discordapp.net/attachments/863381973816311808/891216281557159946/Screenshot_2021-09-25-09-53-34-73_fa71678f3a82dbaa47d69a1c0a162b21.jpg'));

        $image = Grafika::createImage(stream_get_meta_data($prep)['uri']);
        $editor = Grafika::createEditor();
        
        $editor->resizeExactHeight($image, 800);
        $filterGrayscale = Grafika::createFilter('Grayscale');
        $filterInvert = Grafika::createFilter('Invert');
        $filterGamma = Grafika::createFilter('Gamma', 0.7);
        $editor->apply($image, $filterGamma);
        $editor->apply($image, $filterGrayscale);
        $editor->apply($image, $filterInvert);
        $editor->crop($image, 500, 700, 'center-left');


        // $image = (new ImageManager(array('driver' => 'imagick')))->make('https://media.discordapp.net/attachments/863381973816311808/891216281557159946/Screenshot_2021-09-25-09-53-34-73_fa71678f3a82dbaa47d69a1c0a162b21.jpg')
        //     ->resizeCanvas(1024, 768, 'top-left')
        //     ->crop(500, 768, 0, 0)
        //     ->gamma(0.7)
        //     ->greyscale()
        //     ->invert()
        // ;


        try {
            $editor->save($image, stream_get_meta_data($tmp)['uri']);
            $editor->free($image);

            $rawText = (new TesseractOCR(stream_get_meta_data($tmp)['uri']))
            ->lang('eng', 'rus')
            ->run();

            $lines = explode("\n", $rawText);
            
            \dump($lines);
            preg_match('/\((.*?)\)/', $lines[6], $matches);
            $member = $matches[1];

            list(
                $timestamp,
                $amount,
                $member
            ) = [
                DateTime::createFromFormat('Y-m-d H:i:s', $lines[0]),
                intval(filter_var($lines[2], FILTER_SANITIZE_NUMBER_INT)),
                $member
            ];
            dd($timestamp, $amount, $member, $matches);
        } catch (\Exception $e) {
            // \dd($e);
        }


        $httpFoundationFactory = new HttpFoundationFactory();
        $response = new BinaryFileResponse(stream_get_meta_data($tmp)['uri']);
        // $response->headers->add(['Content-Type' => 'image/png']);
        return $response;
        // return $httpFoundationFactory->createResponse(stream_get_meta_data($tmp)['uri']);
        
        // dump((new TesseractOCR('/home/gibz/dev/text.jpg'))
        //     ->run());
        // dd((new TesseractOCR('/home/gibz/dev/image0.png'))
        // ->lang('eng', 'rus')
        //     ->run());
    }
}
