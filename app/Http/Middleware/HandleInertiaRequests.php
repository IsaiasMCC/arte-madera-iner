<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $currentPage = $request->path();
        return array_merge(parent::share($request), [
            'cart' => function () use ($request) {
                // Suponiendo que guardas el carrito en sesión
                return session('cart', []);
            },
            'auth' => [
                'user' => $request->user() ? $request->user()->only('id', 'nombres', 'apellidos', 'email') : null,
                'permissions' => $request->user()
                    ? $request->user()->getAllPermissions()->pluck('name')
                    : [],
            ],
            // Agregar visitas
            'visitasActuales' => PageVisit::where('page', $currentPage)->value('visits') ?? 0,
            'visitasTotales' => PageVisit::sum('visits'),
        ]);
    }
}
