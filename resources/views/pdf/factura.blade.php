<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numero_factura }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 26px;
        }

        .header {
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .row {
            width: 100%;
            margin-bottom: 12px;
            clear: both;
        }

        .col {
            float: left;
            width: 49%;
        }

        .text-right {
            text-align: right;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        h2 {
            margin: 0 0 6px;
            font-size: 14px;
        }

        p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .totals {
            margin-top: 16px;
            width: 42%;
            margin-left: auto;
        }

        .totals td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .totals .label {
            background: #f9fafb;
            font-weight: bold;
        }

        .totals .grand {
            font-size: 14px;
            font-weight: bold;
            background: #e5e7eb;
        }

        .footer {
            margin-top: 26px;
            border-top: 1px dashed #9ca3af;
            padding-top: 12px;
            font-size: 10px;
            color: #4b5563;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #9ca3af;
            border-radius: 2px;
            font-size: 10px;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="row">
            <div class="col">
                <h1>{{ strtoupper(config('app.name')) }}</h1>
                <p>NIT: 900000000-0</p>
                <p>Direccion: Calle Comercial 123</p>
                <p>Telefono: +57 300 000 0000</p>
                <p>Email: facturacion@empresa.local</p>
                <span class="badge">DOCUMENTO NO FISCAL</span>
            </div>
            <div class="col text-right">
                <h2>FACTURA DE VENTA</h2>
                <p><strong>No:</strong> {{ $factura->numero_factura }}</p>
                <p><strong>Fecha:</strong> {{ optional($factura->fecha_emicion)->format('Y-m-d H:i') }}</p>
                <p><strong>Estado:</strong> {{ $factura->estado }}</p>
                <p><strong>Metodo pago:</strong> {{ $factura->metodo_pago }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h2>Cliente</h2>
            <p><strong>Nombre:</strong> {{ $factura->cliente?->nombre ?? 'N/A' }}</p>
            <p><strong>Cedula:</strong> {{ $factura->cliente?->cedula ?? 'N/A' }}</p>
            <p><strong>Correo:</strong> {{ $factura->cliente?->correo ?? 'N/A' }}</p>
            <p><strong>Telefono:</strong> {{ $factura->cliente?->telefono ?? 'N/A' }}</p>
        </div>
        <div class="col text-right">
            <h2>Datos internos</h2>
            <p><strong>Movimiento:</strong> {{ $factura->movimiento_id }}</p>
            <p><strong>Producto:</strong> {{ $factura->movimiento?->producto?->nombre ?? 'N/A' }}</p>
            <p><strong>SKU:</strong> {{ $factura->movimiento?->producto?->sku ?? 'N/A' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Cant.</th>
                <th style="width: 45%;">Descripcion</th>
                <th style="width: 20%;">Precio unitario</th>
                <th style="width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format((float) ($factura->movimiento?->cantidad ?? 0), 2, '.', ',') }}</td>
                <td>{{ $factura->movimiento?->producto?->nombre ?? 'Producto' }}</td>
                <td>${{ number_format((float) ($factura->movimiento?->precio ?? 0), 2, '.', ',') }}</td>
                <td>${{ number_format((float) $factura->subtotal, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="label">Subtotal</td>
            <td>${{ number_format((float) $factura->subtotal, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td class="label">Impuestos</td>
            <td>${{ number_format((float) $factura->impuestos, 2, '.', ',') }}</td>
        </tr>
        <tr>
            <td class="grand">Total</td>
            <td class="grand">${{ number_format((float) $factura->total, 2, '.', ',') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Representacion grafica de factura generada desde el sistema interno.</p>
        <p>Este documento no sustituye obligaciones fiscales oficiales.</p>
    </div>
</body>

</html>
