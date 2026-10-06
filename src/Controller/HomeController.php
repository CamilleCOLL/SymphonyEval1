<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;

final class HomeController extends AbstractController
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        //ON RECUPERE LES EVENEMENTS DU STORE
        $evenement = $this->store->getEvenement();

        //ON COUPE LE TABLEAU POUR N AVOIR QUE LES TROIS DERNIERS ET DONC LES PLUS RECENTS
        $lastEvent = array_slice($evenement, -3, 3, true);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'evenement' => $evenement,
            //'lastEvent' => $lastEvent,
            'events' => $lastEvent,
        ]);
    }
}
