<p align="center">
  <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 32 32">
    <rect width="32" height="32" rx="8" fill="#6366f1"/>
    <path d="M7 21l5-5 4 4 9-9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
  </svg>
</p>

<h1 align="center">GDBD</h1>

<p align="center">
  Sistema de gestión empresarial construido con Laravel y Filament.
</p>

---

## Descripción

GDBD es un panel de administración para gestión de negocios. Permite controlar ventas, compras, inventario, facturación, clientes, proveedores y catálogo de productos desde una única interfaz.

## Módulos

- **Ventas** — registro y seguimiento de ventas
- **Compras** — órdenes de compra y control de gastos
- **Inventario** — movimientos de stock (entradas y salidas)
- **Facturas** — generación y gestión de facturas
- **Clientes** — cartera de clientes
- **Proveedores** — directorio de proveedores
- **Productos** — catálogo con categorías y precios
- **Roles y permisos** — control de acceso granular por usuario

## Stack

| Paquete | Versión |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.0 |
| Filament | ^5.0 |
| Filament Shield | ^4.2 |
| Filament Media Library Plugin | ^5.0 |
| Laravel DomPDF | ^3.1 |
| Spatie Laravel Permission | (via Shield) |
| Pest | ^4.4 |

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=ShieldSeeder
npm install && npm run build
```

O usando el script incluido:

```bash
composer run setup
php artisan db:seed --class=ShieldSeeder
```

## Acceso inicial

Tras correr el seeder, el usuario administrador por defecto es:

```
Email:    admin@admin.com
Password: password
```

## Desarrollo

```bash
composer run dev
```

Levanta en paralelo: servidor PHP, queue worker, log viewer (Pail) y Vite.
