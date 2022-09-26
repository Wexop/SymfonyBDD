<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        // création du formulaire d'ajout
        $categorieNew = new Categorie(); // on crée une catégorie vide
        $form = $this->createForm(CategorieType::class, $categorieNew);

        //gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la ranger dans la bdd
            $em->persist($categorieNew);

            //générer l'insert
            $em->flush();


        }

        //Pour aller chercherles catégories, je vais utiliser un repository
        //pour me servir de doctrine j'ajoute le paramètre $doctrine à la méthode

        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();

        return $this->render('categories/index.html.twig', [
            "categories" => $categories,
            "formulaire" => $form->createView()
        ]);
    }
}
