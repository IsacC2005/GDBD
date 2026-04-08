<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GDBD') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    @endif
</head>
<body class="bg-gray-950 text-white antialiased">

    {{-- Navbar --}}
    <header class="fixed top-0 inset-x-0 z-50 border-b border-white/5 bg-gray-950/80 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="text-lg font-semibold tracking-tight">{{ config('app.name', 'GDBD') }}</span>
                </div>

                @auth
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-950">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ir al Panel
                    </a>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-950">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6 pt-16">
            {{-- Background glow --}}
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                <div class="h-[600px] w-[600px] rounded-full bg-indigo-500/10 blur-3xl"></div>
            </div>
            <div class="pointer-events-none absolute top-1/4 left-1/4 h-72 w-72 rounded-full bg-violet-500/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-4xl text-center">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-4 py-1.5 text-sm text-indigo-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                    Gestión empresarial inteligente
                </div>

                <h1 class="mb-6 text-5xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl">
                    Control total de
                    <span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                        tu negocio
                    </span>
                </h1>

                <p class="mx-auto mb-10 max-w-2xl text-lg text-gray-400">
                    Gestiona ventas, inventario, clientes, proveedores y facturas desde un único panel. Simple, rápido y eficiente.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-400">
                            Ir al Panel Admin
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-400">
                            Comenzar ahora
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Scroll indicator --}}
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </section>

        {{-- Stats --}}
        <section class="border-y border-white/5 bg-white/[0.02] py-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                    @foreach([
                        ['label' => 'Módulos integrados', 'value' => '8+'],
                        ['label' => 'Gestión en tiempo real', 'value' => '100%'],
                        ['label' => 'Reportes disponibles', 'value' => '∞'],
                        ['label' => 'Uptime garantizado', 'value' => '99.9%'],
                    ] as $stat)
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $stat['value'] }}</div>
                        <div class="mt-1 text-sm text-gray-500">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section class="py-24 px-6">
            <div class="mx-auto max-w-7xl lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Todo lo que necesitas en un solo lugar
                    </h2>
                    <p class="mt-4 text-gray-400">
                        Módulos diseñados para cubrir cada aspecto de tu operación comercial.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach([
                        [
                            'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                            'title' => 'Ventas',
                            'desc' => 'Registra y controla todas tus ventas con trazabilidad completa.',
                            'color' => 'indigo',
                        ],
                        [
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10',
                            'title' => 'Inventario',
                            'desc' => 'Mantén tu stock actualizado y evita quiebres de inventario.',
                            'color' => 'violet',
                        ],
                        [
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0',
                            'title' => 'Clientes',
                            'desc' => 'Gestiona tu cartera de clientes y su historial de compras.',
                            'color' => 'sky',
                        ],
                        [
                            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                            'title' => 'Proveedores',
                            'desc' => 'Administra compras y relaciones con tus proveedores.',
                            'color' => 'emerald',
                        ],
                        [
                            'icon' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-7-7 7V5a2 2 0 012-2h10a2 2 0 012 2v16z',
                            'title' => 'Productos',
                            'desc' => 'Organiza tu catálogo de productos por categorías.',
                            'color' => 'amber',
                        ],
                        [
                            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'title' => 'Facturas',
                            'desc' => 'Genera y gestiona facturas de manera rápida y sencilla.',
                            'color' => 'rose',
                        ],
                        [
                            'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                            'title' => 'Compras',
                            'desc' => 'Registra órdenes de compra y controla tus gastos.',
                            'color' => 'teal',
                        ],
                        [
                            'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                            'title' => 'Categorías',
                            'desc' => 'Clasifica productos para una mejor organización.',
                            'color' => 'orange',
                        ],
                    ] as $feature)
                    <div class="group relative rounded-2xl border border-white/5 bg-white/[0.03] p-6 transition hover:border-white/10 hover:bg-white/[0.05]">
                        <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $feature['color'] }}-500/10">
                            <svg class="h-5 w-5 text-{{ $feature['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-white">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $feature['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-24 px-6">
            <div class="mx-auto max-w-7xl lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 px-8 py-16 text-center">
                    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(255,255,255,0.1),_transparent_60%)]"></div>
                    <h2 class="relative text-3xl font-bold text-white sm:text-4xl">
                        ¿Listo para gestionar tu negocio?
                    </h2>
                    <p class="relative mt-4 text-indigo-200">
                        Accede al panel de control y empieza a tomar decisiones basadas en datos reales.
                    </p>
                    <div class="relative mt-8">
                        @auth
                            <a href="{{ route('filament.admin.pages.dashboard') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3 text-base font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                Ir al Panel Admin
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('filament.admin.auth.login') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3 text-base font-semibold text-indigo-600 transition hover:bg-indigo-50">
                                Iniciar sesión
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-white/5 py-8">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-2">
                    <div class="flex h-6 w-6 items-center justify-center rounded bg-indigo-500">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-400">{{ config('app.name', 'GDBD') }}</span>
                </div>
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} {{ config('app.name', 'GDBD') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
