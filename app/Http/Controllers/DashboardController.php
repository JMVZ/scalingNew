<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $systemInfo = [];
        
        // Solo mostrar información en modo desarrollo
        if (config('app.env') === 'local') {
            $systemInfo = [
                // Información de Laravel y PHP
                'laravel_version' => Application::VERSION,
                'php_version' => PHP_VERSION,
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'app_url' => config('app.url'),
                'timezone' => config('app.timezone'),
                
                // Base de datos
                'db_driver' => config('database.default'),
                'db_database' => config('database.connections.' . config('database.default') . '.database'),
                'db_host' => config('database.connections.' . config('database.default') . '.host'),
                'db_port' => config('database.connections.' . config('database.default') . '.port'),
                
                // Cache y sesiones
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'broadcast_driver' => config('broadcasting.default'),
                
                // Información del usuario
                'user_name' => auth()->user()->name ?? 'N/A',
                'user_email' => auth()->user()->email ?? 'N/A',
                'empresa_nombre' => auth()->user()->empresa->nombre ?? 'N/A',
                'empresa_id' => auth()->user()->empresa_id ?? 'N/A',
                
                // Rutas disponibles
                'routes' => $this->getRoutes(),
                
                // Información del servidor
                'server_os' => PHP_OS,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ];
        }
        
        return Inertia::render('Dashboard', [
            'systemInfo' => $systemInfo,
        ]);
    }
    
    private function getRoutes(): array
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        
        foreach ($routeCollection as $route) {
            // Solo rutas web autenticadas
            if (in_array('web', $route->middleware()) && str_contains($route->uri(), 'api') === false) {
                $routes[] = [
                    'name' => $route->getName(),
                    'uri' => '/' . $route->uri(),
                    'method' => implode('|', $route->methods()),
                ];
            }
        }
        
        return $routes;
    }
}
