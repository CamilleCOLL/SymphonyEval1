<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Store;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNan;

final class EvenementController extends AbstractController
{

    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }


    //AFFICHER TOUS LES EVENEMENT DU STORE :

    #[Route('/evenements', name: 'app_evenement', methods: ['GET'])]
    public function index(): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();

        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
            //'evenement' => $evenement,
            'events' => $evenement,
        ]);
    }


    //FILTER PAR CATEGORIE

    #[Route('/evenements/categorie/{categorie}', name: 'app_evenement_categorie', requirements: ['categorie' => '\w+'], methods: ['GET'])]
    public function categorie(String $categorie): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();

        //METHODE 1 : PLUS EFFICACE

    //    $events = array_filter($event, function($l) use ($categorie) {
    //        return $l['categorie'] === $categorie;
    //    });

        //METHODE 2 : PLUS INTUITIVE :

        //ON DECLARE UN TABLEAU VIDE QUI CONTIENDRA NOS EVENEMENTS FILTRES
        $eventCat = [];

        //POUR CHAQUE ELEMENT DU STORE :
        foreach ($evenement as $item)
        {
            //SI LA CATEGORIE DE ITEM EST LA MEME QU EN PARAMETRES ON AJOUTE AU TABLEAU
            if ($item['categorie'] == $categorie)
            {
                $eventCat[] = $item;
            }
        }

        return $this->render('evenement/categorie.html.twig', [
            'controller_name' => 'EvenementController',
            'evenement' => $evenement,
            'categorie' => $categorie,
            //'eventCat' => $eventCat,
            'events' => $eventCat,
        ]);
    }

    //Requirements : la manière dont les paramètres apparaissent / sont formatés pour fonctionner
    #[Route('/evenements/{id}', name: 'app_evenement_id', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function id(String $id): Response
    {
        $evenement  = $this->store->getEvenement();

        //ON ITNITALISE A NULL CAR PAS BESOIN DE TABLEAU POUR UN SEUL ELEMENT
        $eventId = null;
        foreach($evenement as $item)
        {
            if($item['id'] == $id)
            {
                $eventId = $item;
            }
        }

        //SI LA VALEUR EST TOUJOURS NULL ON RENVOIE UNE ERREUR 404
        if (is_null($eventId))
        {
            // throw new NotFoundHttpException("No event for this ID. Please try another one");
            $this->addFlash('danger', "Aucun évènement ne correspond");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('evenement/show.html.twig', [
            'controller_name' => 'EvenementController',
            'evenement' => $evenement,
            'id' => $id,
            'eventId' => $eventId,

        ]);
    }
                                                                                    //On formate les donnée entrées dans l'URL : \d{4} nombre de chiffre qu'on peut mettre
                                                                                   // \d{1,2} entre 1 et 2 chiffres
    #[Route('/evenements/par-mois/{annee}/{mois}', name: 'app_evenement_tri', requirements: ['annee' => '\d+', 'mois' => '\d+'], methods: ['GET'])]
    public function parMois(int $annee, int $mois): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();

        if($annee < 2024 || $annee > 2030 || $mois < 1 || $mois > 12)
        {
            $this->addFlash('danger', "Aucun évènement ne correspond à votre recherche.");
            return $this->redirectToRoute('app_evenement');
        }

        //Si ca passe, on crée un tableau vide dans lequel on place tous les évènements qui correspondent
        // à la recherche
        $evenementsFiltres = [];
        foreach ($evenement as $item)
        {
            //On convertit la date de début en objet Date
            $dateDebut = date_create($item['date_debut']);

            //On récupère le mois ET l'année
            $anneeItem = (int)$dateDebut->format('Y');
            $moisItem = (int)$dateDebut->format('m');

            // Si l'année ET le mois correspondent à la date de début
            if ($anneeItem === $annee && $moisItem === $mois) {
                $evenementsFiltres[] = $item; // On ajoute l'événement au tableau
            }
        }

        //Si le tableau reste vide :
        if(empty($evenementsFiltres))
        {
            $this->addFlash('danger', "Aucun évènement ne correspond à votre recherche.");
            return $this->redirectToRoute('app_evenement');
        }

        return $this->render('evenement/parMoi.html.twig', [
            'controller_name' => 'EvenementController',
            'events' => $evenementsFiltres,
        ]);
    }

    //EXERCICES CHAT :
    // Afficher uniquement les événements auxquels il reste des places.


    #[Route('/evenements/disponibles', name: 'app_evenement_places', methods: ['GET'])]
    public function placesRestantes(): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();
        $placesRestantes = [];
        foreach ($evenement as $item)
        {
            if($item['places_disponibles'] > 0)
            {
                $placesRestantes[] = $item;
            }
        }


        return $this->render('evenement/place.html.twig', [
            'controller_name' => 'EvenementController',
            //'evenement' => $evenement,
            'events' => $placesRestantes,
        ]);
    }

    // Permettre à l'utilisateur de rechercher un événement à partir d'un mot présent dans son titre.
    //
    #[Route('/evenements/recherche', name: 'app_evenement_recherche', methods: ['GET'])]
    public function placesEtGratuit(Request $request): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();

        $mot = $request->query->get('mot');
        $eventFiltre = [];

        //Si le mot cherché n'est pas présent - ou que le tableau est vide - ou que y a pas de mot renseigné
        if($mot == null)
        {
            $this->addFlash('danger', "Aucun évènement ne correspond à la recherche.");
            return $this->redirectToRoute('app_evenement');
        }

        foreach ($evenement as $item)
        {
            $motPresent = stripos($item['titre'], $mot);

            if($motPresent!== false) //si c'est présent
            {
                $eventFiltre[] = $item;
            }

        }

        if(empty($eventFiltre))
        {
            $this->addFlash('danger', "Aucun évènement ne correspond à la recherche.");
            return $this->redirectToRoute('app_evenement');
        }

        return $this->render('evenement/recherche.html.twig', [
            'controller_name' => 'EvenementController',
            //'evenement' => $evenement,
            'events' => $eventFiltre,
            'mot' => $mot,
        ]);
    }

    //Afficher uniquement les événements auxquels on peut encore s'inscrire.

    //Donc évènements ouverts ET avec des places disponibles
    #[Route('/evenements/inscriptions', name: 'app_evenement_inscriptions', methods: ['GET'])]
    public function inscriptions(): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();
        $eventInscriptions = [];

        foreach ($evenement as $item)
        {
            if($item['statut'] == "ouvert" && $item['places_disponibles'] > 0)
            {
                $eventInscriptions[] = $item;

            }
        }

        if(empty($eventInscriptions))
        {
            $this->addFlash('danger', 'Aucune inscription pour un évènement');
            return $this->redirectToRoute('app_evenement');
        }

        return $this->render('evenement/inscriptions.html.twig', [
            'controller_name' => 'EvenementController',
            //'evenement' => $evenement,
            'events' => $eventInscriptions,
        ]);
    }

    //Le Controller doit afficher uniquement les événements qui respectent les deux critères d'URL:
    //la catégorie correspond à categorie
    //le prix est inférieur ou égal à prix

    #[Route('/evenements/recherche-categorie', name: 'app_evenement_recherche_categorie', methods: ['GET'])]
    public function rechercheCategorie(Request $request): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();
        $categorie = $request->query->get('categorie');
        $prix = $request->query->get('prix');
        $eventFiltre = [];

        if($categorie == null || $prix == null || $prix < 0)
        {
            $this->addFlash('danger', 'Veuillez saisir les deux paramètres catégorie et prix');
            return $this->redirectToRoute('app_evenement');
        }

        //Si le prix n'est pas null, on le convertit en float
        $prix = (float)$prix;

        foreach ($evenement as $item)
        {
            if($item['categorie'] == $categorie && $item['prix'] <= $prix)
            {
                $eventFiltre[] = $item;
            }
        }

        if(empty($eventFiltre))
        {
            $this->addFlash('danger', 'Aucun évènement ne correspond à la recherche.');
            return $this->redirectToRoute('app_evenement');
        }


        return $this->render('evenement/recherche_categorie.html.twig', [
            'controller_name' => 'EvenementController',
            //'evenement' => $evenement,
            'events' => $eventFiltre,
            'categorieRecherche' => $categorie,
            'prixRecherche' => $prix,
        ]);
    }


    //Afficher uniquement les événements dont la date de début est postérieure à aujourd'hui.

    #[Route('/evenements/a-venir', name: 'app_evenement_aVenir', methods: ['GET'])]
    public function aVenir(): Response
    {
        //ON RECUPERE LE STORE ET ON AFFICHE : TOUT SE FAIT DANS LE TWIG
        $evenement  = $this->store->getEvenement();
        $ventFiltre = [];
        $dateJour = date('Y-m-d H:i:s');

        foreach ($evenement as $item)
        {
            $dateItem = $item['date_debut'];
            if($dateItem > $dateJour)
            {
                $eventFiltre[] = $item;
            }
        }

        if(empty($eventFiltre))
        {
            $this->addFlash('danger', 'Aucun évènement dont le début est à venir');
            return $this->redirectToRoute('app_evenement');
        }

        return $this->render('evenement/aVenir.html.twig', [
            'controller_name' => 'EvenementController',
            'events' => $eventFiltre,
        ]);
    }


    //Utilisation du path() en Twig
    //Là c'est juste pour afficher des liens, pas de traitementd

    #[Route('/evenements/navigation', name: 'app_evenement_navigation', methods: ['GET'])]
    public function navigation(): Response
    {
        return $this->render('evenement/navigation.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }


    //Filtrer les évènements selon un nombre minimum de places disponibles
    //On ne veut les events qu'avec un nombre supérieur ou égal au paramètre
    //Afficher : nbEvent, détailEvent
    //Tous les event avec au moins 50% de places encore dispo apprait en vert sinon il apparait en jaune

    #[Route('/evenements/places/{minimum}', name: 'app_evenement_minPlaceDispo', requirements: ['minimum' => '\d+'], methods: ['GET'])]
    public function minimumPlace(int $minimum): Response
    {
        $event = $this->store->getEvenement();
        $eventFiltre = [];

        if($minimum < 1 || $minimum >300)
        {
            $this->addFlash('danger', 'Nombre de places minimum invalide');
            return $this->redirectToRoute('app_evenement');
        }

        foreach ($event as $item)
        {
            if($item['places_disponibles'] >= $minimum)
            {
                $eventFiltre[] = $item;
            }
        }

        return $this->render('evenement/minimumPlaceDispo.html.twig', [
            'controller_name' => 'EvenementController',
            'events' => $eventFiltre,
            'minimumPlace' => $minimum,
        ]);
    }


}
