<?php

namespace App\Controller;

use App\Entity\Ejemplo;
use App\Form\EjemploType;
use App\Repository\EjemploRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ejemplo')]
final class EjemploController extends AbstractController
{
    #[Route(name: 'app_ejemplo_index', methods: ['GET'])]
    public function index(EjemploRepository $ejemploRepository, EntityManagerInterface $entityManager): Response
    {

        //pilla todos los usuarios de la pase de datos
        //$usuarios = $ejemploRepository->findAll();


        /*  almacena en la variable todos los ejemplos cuya edad sea 43
            se ordena por el nombre de forma ascendente
            se puede limitar el número de usuarios mostrados con el número final
        */
        //$usuarios = $ejemploRepository->findBy( array('edad'=>'43'), array('nombre'=>'ASC'), 2 ); //ASC o DESC


        //Se usa el Repository creado en EjemploRepository llamado findByName para filtrar
        //$usuarios = $ejemploRepository->findByName("lionel");

        $usuarios = $entityManager->getRepository(Ejemplo::class)->findByName('susana');

        $findbynamequery = $entityManager->getRepository(Ejemplo::class)->findByNameQuery("lionel");

        return $this->render('ejemplo/index.html.twig', [
            //muestra todos los ejemplos/usuarios de la bd
            'ejemplos' => $ejemploRepository->findAll(),
            
            //muestra los usuarios indicados
            //'ejemplos' => $findbynamequery,
        ]);

    }

    #[Route('/new', name: 'app_ejemplo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ejemplo = new Ejemplo();
        $form = $this->createForm(EjemploType::class, $ejemplo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ejemplo);
            $entityManager->flush();

            return $this->redirectToRoute('app_ejemplo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ejemplo/new.html.twig', [
            'ejemplo' => $ejemplo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ejemplo_show', methods: ['GET'])]
    public function show(Ejemplo $ejemplo): Response
    {
        return $this->render('ejemplo/show.html.twig', [
            'ejemplo' => $ejemplo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ejemplo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ejemplo $ejemplo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EjemploType::class, $ejemplo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ejemplo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ejemplo/edit.html.twig', [
            'ejemplo' => $ejemplo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ejemplo_delete', methods: ['POST'])]
    public function delete(Request $request, Ejemplo $ejemplo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ejemplo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ejemplo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ejemplo_index', [], Response::HTTP_SEE_OTHER);
    }
}
