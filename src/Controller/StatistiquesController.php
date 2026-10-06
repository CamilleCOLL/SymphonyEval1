<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;

final class StatistiquesController extends AbstractController
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    #[Route('/statistiques', name: 'app_statistiques', methods: ['GET'])]
    public function index(): Response
    {
        $evenement = $this->store->getEvenement();
        $sommePrix = 0;
        $nbEvent = 0;
        $nbPayant = 0;
        $nbGratuit = 0;
        $parCategorie = [];

        foreach($evenement as $item)
        {
            $sommePrix += $item['prix'];
            $nbEvent = count($evenement);

            if($item['prix'] == 0)
            {
                $nbGratuit ++;
            } else
            {
                $nbPayant ++;
            }

            //POUR AVOIR UNE REPARTION PAR CATEGORIE ON LA RECUPERE EN VARIABLE
            $categorie = $item['categorie'];

            //PUIS ON VERIFIE SI ELLE EXISTE DANS NOTRE TABLEAU ASSOCIATIF :
            //SI ELLE EXISTE ON AUGMENTE LA VALEUR DE 1
            if (isset($parCategorie[$categorie]))
            {
                $parCategorie[$categorie] += 1;
            }else
            //SI LA CATEGORIE EXISTE PAS ON INITALISE SA VALEUR A 1 MALGRE TOUT
            //LE TABLEAU REMPLI TOUT SEUL LA CLE : ON DONNE JUSTE LA VALEUR
            {
                $parCategorie[$categorie] = 1;
            }
        }


        //ON CALCULE LE PRIX MOYEN : COUNT COMPTE LE NOMBRE D ELEMENT DANS LE STORE
        $prixMoyen = $sommePrix / $nbEvent;

        return $this->render('statistiques/index.html.twig', [
            'controller_name' => 'StatistiquesController',
            'prixMoyen' => $prixMoyen,
            'nbEvent' => $nbEvent,
            'nbPayant' => $nbPayant,
            'nbGratuit' => $nbGratuit,
            'parCategorie' => $parCategorie,
        ]);
    }
}
