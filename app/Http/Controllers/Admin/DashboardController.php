<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ModeloMoto;
use App\Models\Motocicleta;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $meses = $this->buildMonthlyData();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_clientes'  => Cliente::count(),
                'total_motos'     => Motocicleta::count(),
                'total_vin1'      => Motocicleta::where('tipo_formulario', 'VIN1')->count(),
                'total_vin2'      => Motocicleta::where('tipo_formulario', 'VIN2')->count(),
                'modelos_top'     => ModeloMoto::withCount('motocicletas')
                                        ->having('motocicletas_count', '>', 0)
                                        ->orderByDesc('motocicletas_count')
                                        ->limit(8)
                                        ->get(['nombre', 'motocicletas_count']),
                'por_mes'         => $meses,
            ],
        ]);
    }

    private function buildMonthlyData(): array
    {
        $raw = Motocicleta::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->translatedFormat('M Y');
            $result[] = ['mes' => $label, 'total' => $raw->get($key, 0)];
        }
        return $result;
    }
}
