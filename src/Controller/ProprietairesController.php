<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProprietairesController extends AbstractController
{
    #[Route('/proprietaires', name: 'app_proprietaires')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();

            $em->persist($proprietaire);

            $em->flush();


        }

        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaire = $repo->findAll();

        return $this->render('proprietaires/index.html.twig', [
            "proprietaires" => $proprietaire,
            "formulaire" => $form->createView()
        ]);

    }

    #[Route('/proprietaire/supprimer/{id}', name: 'proprietaire_supprimer')]
    public function supprimerProprietaire($id, ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);


        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun proprietaire avec l'id $id");
        }

        $form = $this->createForm(ProprietaireSupprimerType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->remove($proprietaire);

            $em->flush();

            return $this->redirectToRoute("app_proprietaires");

        }
        
        return $this->render("proprietaires/supprimer.html.twig", [
            "proprietaire" => $proprietaire,
            "formulaire" => $form->createView()
        ]);


    }

    #[Route('/proprietaires/modifierProprietaire/{id}', name: 'modifier_proprietaire')]
    public function modifierProprietaire($id, ManagerRegistry $doctrine, Request $request)
    {

        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun proprietaire avec l'id $id");
        }

        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($proprietaire);

            $em->flush();

            return $this->redirectToRoute("app_proprietaires", ["id" => $proprietaire->getId()]);
        }

        return $this->render("proprietaires/modifierProprietaire.html.twig", [
            "proprietaire" => $proprietaire,
            "formulaire" => $form->createView()
        ]);

    }
}
