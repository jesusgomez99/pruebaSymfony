<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Form\FacturaType;
use App\Repository\FacturaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/factura')]
final class FacturaController extends AbstractController
{
    #[Route(name: 'app_factura_index', methods: ['GET'])]
    public function index(FacturaRepository $facturaRepository): Response
    {
        return $this->render('factura/index.html.twig', [
            'facturas' => $facturaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_factura_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factura);
            $entityManager->flush();

            return $this->redirectToRoute('app_factura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('factura/new.html.twig', [
            'factura' => $factura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_factura_show', methods: ['GET'])]
    public function show(Factura $factura): Response
    {
        return $this->render('factura/show.html.twig', [
            'factura' => $factura,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_factura_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Factura $factura, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Agregar un mensaje flash
            $this->addFlash('success', 'Factura guardada correctamente.');

            return $this->redirectToRoute('app_factura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('factura/edit.html.twig', [
            'factura' => $factura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_factura_delete', methods: ['POST'])]
    public function delete(Request $request, Factura $factura, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factura->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($factura);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_factura_index', [], Response::HTTP_SEE_OTHER);
    }
}
