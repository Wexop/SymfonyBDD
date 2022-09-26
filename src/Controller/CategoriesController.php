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


    #[Route('/categorie/modifier/{id}', name: 'categorie_modifier')]
    public function modifierCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        //Récupérer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //si on n'a rien trouvé -> 404

        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        //si on arrive la, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs)
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // le handleRequest a rempli notre objet $vcategorieNew
            // qui n'est plus vide
            //pour sauvegarder, on va récupérer un entitymanager de doctrine
            //qui comme son nom l'indique gère les identités
            $em = $doctrine->getManager();
            // on lui dit de la ranger dans la bdd
            $em->persist($categorie);

            //générer l'insert
            $em->flush();

            //retour à la page d'accueil
            return $this->redirectToRoute("app_home");

        }


        return $this->render("categories/modifier.html.twig", [
            "categorie" => $categorie,
            "formulaire" => $form->createView()
        ]);


    }
}
