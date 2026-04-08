<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataFakerSeeder extends Seeder
{
    public function run(): void
    {
        $years = 30;
        $startDate = now()->subYears($years);
        $endDate = now();

        $comprasObjetivo = (int) env('DATA_FAKER_COMPRAS', 1_500_000);
        $ventasObjetivo = (int) env('DATA_FAKER_VENTAS', 1_500_000);
        $chunkSize = (int) env('DATA_FAKER_CHUNK', 5000);

        $this->command?->warn('Iniciando DataFakerSeeder (volumen alto)...');
        $this->command?->line("Compras: {$comprasObjetivo} | Ventas: {$ventasObjetivo} | Ventana: {$years} años");

        $categoriaIds = $this->seedCategorias();
        $proveedorIds = $this->seedProveedores();
        $clienteIds = $this->seedClientes();

        [$productoIds, $precioPorProducto] = $this->seedProductos($categoriaIds);

        $stockPorProducto = array_fill_keys($productoIds, 0.0);

        $this->seedComprasMasivas(
            productoIds: $productoIds,
            proveedorIds: $proveedorIds,
            precioPorProducto: $precioPorProducto,
            stockPorProducto: $stockPorProducto,
            startDate: $startDate,
            endDate: $endDate,
            totalRegistros: $comprasObjetivo,
            chunkSize: $chunkSize,
        );

        $this->seedVentasMasivas(
            productoIds: $productoIds,
            clienteIds: $clienteIds,
            precioPorProducto: $precioPorProducto,
            stockPorProducto: $stockPorProducto,
            startDate: $startDate,
            endDate: $endDate,
            totalRegistros: $ventasObjetivo,
            chunkSize: $chunkSize,
        );

        $this->sincronizarStockProductos($stockPorProducto);

        $this->command?->info('DataFakerSeeder finalizado.');
    }

    /**
     * @return array<int, int>
     */
    private function seedCategorias(): array
    {
        $objetivo = max(50, (int) env('DATA_FAKER_CATEGORIAS', 80));
        $existentes = DB::table('categorias')->count();

        if ($existentes < $objetivo) {
            $faltantes = $objetivo - $existentes;
            $rows = [];

            for ($i = 0; $i < $faltantes; $i++) {
                $rows[] = [
                    'nombre' => 'Categoria '.str_pad((string) ($existentes + $i + 1), 4, '0', STR_PAD_LEFT),
                    'descripcion' => 'Categoria generada para pruebas masivas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('categorias')->insert($rows);
        }

        return DB::table('categorias')->pluck('id')->map(fn ($id): int => (int) $id)->all();
    }

    /**
     * @return array<int, int>
     */
    private function seedProveedores(): array
    {
        $objetivo = max(1000, (int) env('DATA_FAKER_PROVEEDORES', 2000));
        $existentes = DB::table('proveedores')->count();

        if ($existentes < $objetivo) {
            $faltantes = $objetivo - $existentes;
            $rows = [];

            for ($i = 0; $i < $faltantes; $i++) {
                $numero = $existentes + $i + 1;
                $rows[] = [
                    'nombre_empresa' => 'Proveedor '.$numero,
                    'nit_cedula' => 'P'.str_pad((string) $numero, 10, '0', STR_PAD_LEFT),
                    'telefono' => '3'.str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'direccion' => 'Calle '.random_int(1, 200).' # '.random_int(1, 99),
                    'correo' => 'proveedor'.$numero.'@seed.local',
                    'nombre_contacto' => 'Contacto '.$numero,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('proveedores')->insert($rows);
        }

        return DB::table('proveedores')->pluck('id')->map(fn ($id): int => (int) $id)->all();
    }

    /**
     * @return array<int, int>
     */
    private function seedClientes(): array
    {
        $objetivo = max(10000, (int) env('DATA_FAKER_CLIENTES', 20000));
        $existentes = DB::table('clientes')->count();

        if ($existentes < $objetivo) {
            $faltantes = $objetivo - $existentes;
            $rows = [];

            for ($i = 0; $i < $faltantes; $i++) {
                $numero = $existentes + $i + 1;
                $rows[] = [
                    'cedula' => str_pad((string) $numero, 10, '0', STR_PAD_LEFT),
                    'nombre' => 'Cliente '.$numero,
                    'correo' => 'cliente'.$numero.'@seed.local',
                    'telefono' => '3'.str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('clientes')->insert($rows);
        }

        return DB::table('clientes')->pluck('id')->map(fn ($id): int => (int) $id)->all();
    }

    /**
     * @param  array<int, int>  $categoriaIds
     * @return array{0: array<int, int>, 1: array<int, float>}
     */
    private function seedProductos(array $categoriaIds): array
    {
        $objetivo = max(3000, (int) env('DATA_FAKER_PRODUCTOS', 5000));
        $existentes = DB::table('productos')->count();

        if ($existentes < $objetivo) {
            $faltantes = $objetivo - $existentes;
            $rows = [];

            for ($i = 0; $i < $faltantes; $i++) {
                $numero = $existentes + $i + 1;
                $precioVenta = (float) random_int(500, 300000) / 100;
                $rows[] = [
                    'categoria_id' => $categoriaIds[array_rand($categoriaIds)],
                    'sku' => 'SKU-'.str_pad((string) $numero, 8, '0', STR_PAD_LEFT),
                    'nombre' => 'Producto '.$numero,
                    'descripcion' => 'Producto generado para cargas masivas de prueba',
                    'precio_venta' => $precioVenta,
                    'costo_promedio' => (float) random_int(300, 250000) / 100,
                    'stock' => 0,
                    'stock_minimo' => (float) random_int(1, 100),
                    'stock_maximo' => (float) random_int(1000, 100000),
                    'state' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('productos')->insert($rows);
        }

        $productos = DB::table('productos')->select(['id', 'precio_venta'])->get();
        $productoIds = [];
        $precioPorProducto = [];

        foreach ($productos as $producto) {
            $productoIds[] = (int) $producto->id;
            $precioPorProducto[(int) $producto->id] = (float) $producto->precio_venta;
        }

        return [$productoIds, $precioPorProducto];
    }

    /**
     * @param  array<int, int>  $productoIds
     * @param  array<int, int>  $proveedorIds
     * @param  array<int, float>  $precioPorProducto
     * @param  array<int, float>  $stockPorProducto
     */
    private function seedComprasMasivas(
        array $productoIds,
        array $proveedorIds,
        array $precioPorProducto,
        array &$stockPorProducto,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        int $totalRegistros,
        int $chunkSize,
    ): void {
        $this->command?->info('Generando compras masivas...');

        $insertados = 0;

        while ($insertados < $totalRegistros) {
            $rows = [];
            $lote = min($chunkSize, $totalRegistros - $insertados);

            for ($i = 0; $i < $lote; $i++) {
                $productoId = $productoIds[array_rand($productoIds)];
                $cantidad = (float) random_int(1, 200);
                $precio = $precioPorProducto[$productoId] * ((float) random_int(60, 98) / 100);
                $fecha = $this->randomDate($startDate, $endDate);

                $stockPorProducto[$productoId] += $cantidad;

                $rows[] = [
                    'producto_id' => $productoId,
                    'proveedor_id' => $proveedorIds[array_rand($proveedorIds)],
                    'precio' => $precio,
                    'precio_balance' => $precio,
                    'cantidad' => $cantidad,
                    'tipo_movimiento' => 'entrada',
                    'fecha_movimiento' => $fecha,
                    'motivo' => 'Compra historica masiva',
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
            }

            DB::table('inventarios')->insert($rows);

            $insertados += $lote;

            if ($insertados % ($chunkSize * 20) === 0) {
                $this->command?->line("Compras insertadas: {$insertados}/{$totalRegistros}");
            }
        }
    }

    /**
     * @param  array<int, int>  $productoIds
     * @param  array<int, int>  $clienteIds
     * @param  array<int, float>  $precioPorProducto
     * @param  array<int, float>  $stockPorProducto
     */
    private function seedVentasMasivas(
        array $productoIds,
        array $clienteIds,
        array $precioPorProducto,
        array &$stockPorProducto,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        int $totalRegistros,
        int $chunkSize,
    ): void {
        $this->command?->info('Generando ventas masivas...');

        $insertados = 0;
        $consecutivoFactura = 1;

        while ($insertados < $totalRegistros) {
            $inventarioRows = [];
            $facturaRows = [];
            $lote = min($chunkSize, $totalRegistros - $insertados);
            $startIdMovimiento = (int) DB::table('inventarios')->max('id') + 1;

            for ($i = 0; $i < $lote; $i++) {
                $productoId = $this->pickProductoConStock($productoIds, $stockPorProducto);

                if ($productoId === null) {
                    break;
                }

                $stockActual = $stockPorProducto[$productoId];
                $cantidad = min((float) random_int(1, 80), max(1.0, floor($stockActual)));
                $precio = $precioPorProducto[$productoId] * ((float) random_int(95, 125) / 100);
                $subtotal = $precio * $cantidad;
                $impuestos = $subtotal * 0.19;
                $total = $subtotal + $impuestos;
                $fecha = $this->randomDate($startDate, $endDate);

                $stockPorProducto[$productoId] -= $cantidad;

                $inventarioRows[] = [
                    'producto_id' => $productoId,
                    'proveedor_id' => null,
                    'precio' => $precio,
                    'precio_balance' => $precio,
                    'cantidad' => $cantidad,
                    'tipo_movimiento' => 'salida',
                    'fecha_movimiento' => $fecha,
                    'motivo' => 'Venta historica masiva',
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];

                $facturaRows[] = [
                    'movimiento_id' => $startIdMovimiento + $i,
                    'cliente_id' => $clienteIds[array_rand($clienteIds)],
                    'numero_factura' => 'DF-'.str_pad((string) $consecutivoFactura, 12, '0', STR_PAD_LEFT),
                    'fecha_emicion' => $fecha,
                    'metodo_pago' => ['Efectivo', 'Tarjeta', 'Transferencia'][array_rand(['Efectivo', 'Tarjeta', 'Transferencia'])],
                    'estado' => ['Pagada', 'Pendiente', 'Anulada'][array_rand(['Pagada', 'Pendiente', 'Anulada'])],
                    'subtotal' => $subtotal,
                    'impuestos' => $impuestos,
                    'total' => $total,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];

                $consecutivoFactura++;
            }

            if ($inventarioRows === []) {
                $this->command?->warn('No hay stock suficiente para continuar generando ventas.');

                break;
            }

            DB::table('inventarios')->insert($inventarioRows);
            DB::table('facturas')->insert($facturaRows);

            $insertados += count($inventarioRows);

            if ($insertados % ($chunkSize * 20) === 0) {
                $this->command?->line("Ventas insertadas: {$insertados}/{$totalRegistros}");
            }
        }
    }

    /**
     * @param  array<int, float>  $stockPorProducto
     */
    private function sincronizarStockProductos(array $stockPorProducto): void
    {
        $this->command?->info('Sincronizando stock final en productos...');

        foreach ($stockPorProducto as $productoId => $stock) {
            DB::table('productos')
                ->where('id', $productoId)
                ->update([
                    'stock' => max(0, $stock),
                    'updated_at' => now(),
                ]);
        }
    }

    private function randomDate(\DateTimeInterface $startDate, \DateTimeInterface $endDate): string
    {
        $timestamp = random_int($startDate->getTimestamp(), $endDate->getTimestamp());

        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @param  array<int, int>  $productoIds
     * @param  array<int, float>  $stockPorProducto
     */
    private function pickProductoConStock(array $productoIds, array $stockPorProducto): ?int
    {
        for ($intentos = 0; $intentos < 25; $intentos++) {
            $productoId = $productoIds[array_rand($productoIds)];

            if (($stockPorProducto[$productoId] ?? 0) > 1) {
                return $productoId;
            }
        }

        foreach ($stockPorProducto as $productoId => $stock) {
            if ($stock > 1) {
                return (int) $productoId;
            }
        }

        return null;
    }
}
