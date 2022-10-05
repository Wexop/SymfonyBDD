<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    #[Route('/chatons', name: 'app_chatons')]
    public function index(): Response
    {
        return $this->render('chatons/index.html.twig', [
            'controller_name' => 'ChatonsController',
        ]);
    }

    #[Route('/chaton/ajouter/', name: 'chaton_ajouter')]
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();

        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la supprimer de la BDD
            $em->persist($chaton);

            //générer l'insert
            $em->flush();

            //retour à la page d'accueil
            return $this->redirectToRoute("voir_chatons", ["id" => $chaton->getCategorie()->getId()]);

        }


        return $this->render("chatons/index.html.twig", [
            "formulaire" => $form->createView()
        ]);


    }

    #[Route('/categorie/voirChatons/{id}', name: 'voir_chatons')]
    public function voirChatons($id, ManagerRegistry $doctrine, Request $request)
    {

        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        $chatons = $categorie->getChatons();


        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }


        return $this->render("chatons/voirChatons.html.twig", [
            "categorie" => $categorie,
            "chatons" => $chatons
        ]);


    }

    #[Route('/chatons/modifierChaton/{id}', name: 'modifier_chaton')]
    public function modifierCaton($id, ManagerRegistry $doctrine, Request $request)
    {
        //Récupérer la catégorie dans la BDD
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //si on n'a rien trouvé -> 404

        if (!$chaton) {
            throw $this->createNotFoundException("Aucun chaton avec l'id $id");
        }

        //si on arrive la, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs)
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la ranger dans la bdd
            $em->persist($chaton);

            //générer l'insert
            $em->flush();

            //retour à la page d'accueil
            return $this->redirectToRoute("voir_chatons", ["id" => $chaton->getCategorie()->getId()]);

        }


        return $this->render("chatons/modifierChaton.html.twig", [
            "chaton" => $chaton,
            "formulaire" => $form->createView()
        ]);


    }

    #[Route('/chatons/supprimer/{id}', name: 'chatons_supprimer')]
    public function supprimerChaton($id, ManagerRegistry $doctrine, Request $request)
    {
        //Récupérer la catégorie dans la BDD
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //si on n'a rien trouvé -> 404

        if (!$chaton) {
            throw $this->createNotFoundException("Aucun chaton avec l'id $id");
        }

        //si on arrive la, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs)
        $form = $this->createForm(ChatonSupprimerType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la supprimer de la BDD
            $em->remove($chaton);

            //générer l'insert
            $em->flush();

            //retour à la page d'accueil
            return $this->redirectToRoute("voir_chatons", ["id" => $chaton->getCategorie()->getId()]);

        }


        return $this->render("chatons/supprimer.html.twig", [
            "chaton" => $chaton,
            "formulaire" => $form->createView()
        ]);


    }
}
