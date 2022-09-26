<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // création du formulaire d'ajout
        $categorieNew = new Categorie(); // on crée une catégorie vide
        $form = $this->createForm(CategorieType::class, $categorieNew);

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
