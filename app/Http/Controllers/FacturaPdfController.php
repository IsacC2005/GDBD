<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Service\Factura\GenerarFacturaPdfService;
use Illuminate\Http\Response;

class FacturaPdfController extends Controller
{
    public function __invoke(Factura $factura, GenerarFacturaPdfService $generarFacturaPdfService): Response
    {
        $pdf = $generarFacturaPdfService->generar($factura);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="factura-'.$factura->numero_factura.'.pdf"',
        ]);
    }
}
