<?php

namespace App\Service\Factura;

use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerarFacturaPdfService
{
    public function generar(Factura $factura): string
    {
        $factura->loadMissing([
            'cliente',
            'movimiento.producto',
        ]);

        return Pdf::loadView('pdf.factura', [
            'factura' => $factura,
        ])
            ->setPaper('a4')
            ->output();
    }
}
