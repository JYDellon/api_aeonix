<?php

namespace App\Controller\Admin;

use App\Entity\PageVisit;
use App\Form\PageVisitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


#[Route('/admin/page-visits', name: 'admin_page_visit_')]
class PageVisitAdminController extends AbstractController
{
    

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function index(): Response
    {
        // Utilisez l'objet doctrine injecté pour accéder au repository
        $visits = $this->doctrine->getRepository(PageVisit::class)->findAll();

        return $this->render('admin/page_visits.html.twig', [
            'visits' => $visits,
        ]);
    }


    
    
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $pageVisit = new PageVisit();
        $form = $this->createForm(PageVisitType::class, $pageVisit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($pageVisit);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle visite de page créée avec succès.');

            return $this->redirectToRoute('admin_page_visit_index');
        }

        return $this->render('admin/page_visit/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/{id}', name: 'show', methods: ['GET'])]
    // public function show(PageVisit $pageVisit): Response
    // {
    //     return $this->render('admin/page_visit/show.html.twig', [
    //         'pageVisit' => $pageVisit,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PageVisit $pageVisit): Response
    {
        $form = $this->createForm(PageVisitType::class, $pageVisit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'Visite de page mise à jour avec succès.');

            return $this->redirectToRoute('admin_page_visit_index');
        }

        return $this->render('admin/page_visit/edit.html.twig', [
            'form' => $form->createView(),
            'pageVisit' => $pageVisit,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, PageVisit $pageVisit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pageVisit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($pageVisit);
            $entityManager->flush();

            $this->addFlash('success', 'Visite de page supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_page_visit_index');
    }




    #[Route('/reset', name: 'admin_page_visit_reset', methods: ['POST'])]
    public function resetVisits(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $repository = $this->doctrine->getRepository(PageVisit::class);
    
        // Supprime tous les enregistrements de la table
        $pageVisits = $repository->findAll();
        foreach ($pageVisits as $visit) {
            $entityManager->remove($visit);
        }
        $entityManager->flush();
    
        $this->addFlash('success', 'Tous les enregistrements ont été supprimés avec succès.');
    
        return $this->redirectToRoute('admin_page_visit_index');
    }
    


    #[Route('/admin/page-visits/{id}', name: 'admin_page_visit_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $repository = $this->doctrine->getRepository(PageVisit::class);
        $pageVisit = $repository->find($id);
    
        if (!$pageVisit) {
            throw new NotFoundHttpException('Visite de page introuvable.');
        }
    
        return $this->render('admin/page_visit/show.html.twig', [
            'pageVisit' => $pageVisit,
        ]);
    }
    



    
}