<?php

namespace App\Controller;

use App\Entity\Factura;
use App\Service\OcrService;
use App\Repository\ProveedorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class OcrController extends AbstractController
{

    public OcrService $ocrService;
    public ProveedorRepository $proveedorRepository;
    public EntityManagerInterface $entityManager;

//---------------------------------------------

    //Inyección del servicio OcrService en el controlador
    public function __construct(
        OcrService $ocrService, 
        ProveedorRepository $proveedorRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->ocrService = $ocrService;
        $this->proveedorRepository = $proveedorRepository;
        $this->entityManager = $entityManager;
    }




// OK, Inicio ---------------------------------------------

    #[Route('/ocr', name: 'inicio')]
    public function index(Request $request): Response
    {
        // Renderiza la vista sin texto reconocido inicialmente
        return $this->render('ocr/index.html.twig', [
            'controller_name' => 'OcrController',
            'textoReconocido' => null,
        ]);
    }


// OK, Escaner ---------------------------------------------

    #[Route('/ocr/escaner', name: 'app_ocr')]
    public function escaner(Request $request): Response
    {
        // Renderiza la vista sin texto reconocido inicialmente
        return $this->render('ocr/escaner.html.twig', [
            'controller_name' => 'OcrController',
            'textoReconocido' => null,
        ]);
    }




// OK, Procesar texto con Ocr ---------------------------------------------

    #[Route('/ocr/procesar', name: 'app_ocr_procesar', methods: ['POST'])]
    public function procesarImagen(Request $request): Response
    {
        $textoReconocido = null;
    
        // Verificación de si se ha enviado una imagen.
        if ($request->files->has('imagen')) {
            
            // Obtener la ruta temporal de la imagen subida
            $imagen = $request->files->get('imagen');
            $rutaImagen = $imagen->getPathname();
    
            // Llamar al servicio OCR para obtener el texto de la imagen
            $textoReconocido = $this->ocrService->reconocerTexto($rutaImagen);
            
            // Guardar el texto reconocido en la sesión
            $request->getSession()->set('textoReconocido', $textoReconocido);
        }
    
        // Redireccionar a la página de resultados
        return $this->redirectToRoute('app_ocr_resultado');
    }


//POR HACER---------------------------------------------
    #[Route('/ocr/resultado', name: 'app_ocr_resultado')]
    public function mostrarResultado(Request $request): Response
    {
        // Obtener el texto reconocido de la sesión
        $textoReconocido = $request->getSession()->get('textoReconocido');
        
        // Limpiar la sesión
        $request->getSession()->remove('textoReconocido');

        // Obtiene los proveedores de bd
        $proveedores = $this->proveedorRepository->findAll();

        
        return $this->render('ocr/resultado.html.twig', [
            'controller_name' => 'OcrController',
            'textoReconocido' => $textoReconocido,
            'proveedores' => $proveedores,
        ]);
    }

    #[Route('/ocr/guardar', name: 'app_ocr_guardar', methods: ['POST'])]
    public function guardarResultado(Request $request): Response
    {

        // Obtenemos los datos del formulario
        $numeroFactura = $request->request->get('numero');
        $textoFactura = $request->request->get('texto');

        $idProveedor = $request->request->get('proveedor');

        // Buscar el proveedor en la base de datos
        $proveedor = $this->proveedorRepository->find($idProveedor);

        // Crear nueva factura
        $factura = new Factura();
        $factura->setNumero($numeroFactura);
        $factura->setTexto($textoFactura);
        $factura->setProveedor($proveedor);


        try {
            // Guardar en la base de datos
            $this->entityManager->persist($factura);
            $this->entityManager->flush();
    
            // Agregar un mensaje flash
            $this->addFlash('success', 'Factura guardada correctamente.');
    
            // Redirigir sin parámetros en la URL
            return $this->redirectToRoute('app_factura_index');
    
        } catch (UniqueConstraintViolationException $e) {
            // Mensaje de error si el número de factura ya existe
            $this->addFlash('danger', "El número de factura '{$numeroFactura}' ya existe, introduzca un número válido.");
    
            return $this->render('ocr/resultado.html.twig', [
                'controller_name' => 'OcrController',
                'textoReconocido' => $textoFactura,
                'proveedores' => $this->proveedorRepository->findAll(),
                'idProveedorSeleccionado' => $idProveedor
            ]);
        }
    }


}
