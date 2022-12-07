<?php

namespace App\Controller;

use App\Entity\Proprietaire;
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
}
