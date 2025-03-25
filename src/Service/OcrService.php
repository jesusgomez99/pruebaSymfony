<?php

namespace App\Service;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function reconocerTexto($rutaImagen)
    {
        // Ahora puedes usar la clase porque la has importado arriba
        $ocr = new TesseractOCR($rutaImagen);
        return $ocr->run();
    }
    
}
