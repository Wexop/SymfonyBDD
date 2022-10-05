<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
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
            return $this->redirectToRoute("app_home");

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
}
