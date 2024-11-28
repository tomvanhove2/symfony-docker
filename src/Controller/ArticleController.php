<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article',
        ]);
    }

    #[Route('/article/creer', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        
        // Crée le formulaire
        $form = $this->createForm(ArticleType::class, $article);
    
        // Gère la requête HTTP
        $form->handleRequest($request);
    
        // Si le formulaire est soumis et valide, on persiste les données
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'L\'article a été créé avec succès.');
    
            // Reste sur la même page (retourne au même formulaire)
            return $this->render('article/creer.html.twig', [
                'form' => $form->createView(),
                'titre' => 'Créer un nouvel article',
            ]);
        }
    
        // Affiche le formulaire s'il n'est pas soumis ou pas valide
        return $this->render('article/creer.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Créer un nouvel article',
        ]);
    }

    #[Route('/article/liste', name: 'app_article_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/liste.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Liste des articles',
            'articles' => $articles
        ]);
    }

    #[Route('/article/modifier/{id}', name: 'app_article_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Ajouter un message flash de succès après la modification
            $this->addFlash('success', 'L\'article a été modifié avec succès!');

            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('article/modifier.html.twig', [
            'form' => $form->createView(),
            'article' => $article, // Transmettre l'article pour éviter l'erreur
            'titre' => 'Modifier l\'article',
        ]);
    }

    #[Route('/article/supprimer/{id}', name: 'app_article_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException("L'article avec l'ID $id n'existe pas.");
        }

        $entityManager->remove($article);
        $entityManager->flush();

        // Ajouter un message flash de succès après la suppression
        $this->addFlash('success', 'L\'article a été supprimé avec succès!');

        return $this->redirectToRoute('app_article_list');
    }
}