<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;
//On utilise la bibliothèque
final class ApiController extends AbstractController
{
    //On la passe en paramètre de classe
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

//    #[Route('/api/evenements', name: 'app_api', methods: ['GET'])]
//    public function index(): Response
//    {
//        $evenement = $this->store->getEvenement();
//
//        return $this->json($evenement);
//    }


    #[Route('/api/evenements/{id}', name: 'app_api_id', methods: ['GET'])]
    public function apiId(int $id): Response
    {
        $evenement = $this->store->getEvenement();
        $apiId = null;

        foreach ($evenement as $item) {
            if($item['id'] === $id) {
                $apiId = $item;
                break;
            }
        }

        if(is_null($apiId)) {
            // throw new NotFoundHttpException("API JSON not found for this event. Please try another ID");
            $this->addFlash('danger', "Aucun JSON ne correspond à l'ID $id.");
            return $this->redirectToRoute('app_home');
        }

        return $this->json($apiId);

    }

    #[Route('/api/evenements/', name: 'app_api_filtre', methods: ['GET'])]
    public function apiFiltre(Request $request): Response
    {
        $evenement = $this->store->getEvenement();

        //Je récupère mes éléments de request : catégorie et accès
        $categorie = $request->query->get('categorie');
        $acces = $request->query->get('acces');


        //Je déclare un tableau vide
        $eventFiltre = [];

        //On parcours mes evenements
        foreach ($evenement as $item) {

            if //Si la catégorie n'est pas renseignée OU qu'elle correspond à la recherche
            (
                (
                    $categorie == null
                    || $categorie == $item['categorie']
                )
                && //Si l'acces n'est pas renseigné OU (prix = 0 et accès renseigné GRATUIT) OU (prix !=0 et accès PAYANT)
                (
                    $acces == null
                    || ($item['prix'] == 0 && $acces == 'gratuit')
                    || ($item['prix'] != 0 && $acces == 'payant')
                )
            )
            {
                $eventFiltre[] = $item;
            }

        }
        return $this->json($eventFiltre);
    }

}
