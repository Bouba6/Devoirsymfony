<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NiveauRepository;
use App\Repository\CoursRepository;
use App\Entity\Niveau;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class NiveauController extends AbstractController
{
    #[Route('/niveau', name: 'app_niveau1')]
    public function index(): Response
    {
        $htmlContent = file_get_contents('/home/bouba/Documents/examensymfony/src/Views/niveaux/index.html');

        return new Response($htmlContent);
    }



    #[Route('/api/niveau', name: 'app_niveau2')]
    public function apiNiveau(NiveauRepository $niveauRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 5;  // Nombre d'articles par page

        // Créer une requête pour récupérer tous les articles
        $queryBuilder = $niveauRepository->createQueryBuilder('a');

        // Utiliser KNP Paginator pour paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder,   // La requête
            $page,           // La page actuelle
            $limit           // Nombre d'articles par page
        );

        // Extraire les articles et les informations de pagination
        $niveaux = $pagination->getItems();
        $totalItems = $pagination->getTotalItemCount();
        $totalPages = $pagination->count();

        // Préparer les données à retourner
        $data = [
            'niveaux' => array_map(function ($niveau) {
                return [
                    'id' => $niveau->getId(),
                    'nomNiveau' => $niveau->getNomNiveau(),
                ];
            }, $niveaux),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalItems,
            ]
        ];

        // Retourner les données sous forme de JSON
        return $this->json($data);
    }


    #[Route('/niveau/store', name: 'app_niveau3')]
    public function store(): Response
    {
        $htmlContent = file_get_contents('/home/bouba/Documents/examensymfony/src/Views/niveaux/form.html');

        return new Response($htmlContent);
    }

    #[Route('/api/add/niveau', name: 'app_niveau4')]
    public function addNiveau(Request $request, NiveauRepository $niveauRepository, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();
        $niveau = new Niveau();
        $niveau->setNomNiveau($data['niveau']);
        $entityManager->persist($niveau);
        $entityManager->flush();
        return $this->json($niveau);
    }
}
