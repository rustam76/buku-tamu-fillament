<?php

namespace App\Filament\Widgets;

use App\Models\Tamu;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class LineChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;
    protected static ?string $heading = 'Fakultas';

    protected function getData(): array
    {
        // Ambil nilai startDate dan endDate dari filter (jika ada)
        $startDate = $this->filters['startDate'] ?? now()->subMonth()->toDateString();
        $endDate = $this->filters['endDate'] ?? now()->toDateString();


        // Jika startDate atau endDate tidak ada, kembalikan data kosong
        if (!$startDate || !$endDate) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Ambil data tamu per fakultas
        $data = Tamu::with('jurusan.fakultas')
            ->whereBetween('created_at', [$startDate, $endDate]) // Filter berdasarkan tanggal
            ->get()
            ->groupBy(fn ($item) => $item->jurusan->fakultas->name ?? 'Tanpa Fakultas') // Kelompokkan berdasarkan nama fakultas
            ->map(function ($group) {
                $fakultas = $group->first()->jurusan->fakultas ?? null;
                return [
                    'data' => $group->groupBy(fn ($item) => $item->created_at->toDateString())
                        ->map(fn ($items) => $items->count())
                        ->toArray(),
                    'color' => $fakultas->color ?? '#000000', // Ambil warna dari database, default hitam
                ];
            });

        // Format data untuk chart
        $labels = $this->generateLabels($startDate, $endDate); // Label tanggal dalam rentang waktu
        $datasets = $data->map(function ($item, $fakultas) use ($labels) {
            $values = array_map(
                fn ($label) => $item['data'][$label] ?? 0, 
                $labels
            );

            return [
                'label' => $fakultas,
                'data' => $values,
                'borderColor' => $item['color'],
                'fill' => false,
                'tension' => 0.4, // Kurva garis
            ];
        })->values()->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Fungsi untuk menghasilkan rentang tanggal sebagai label
    private function generateLabels(Carbon $startDate, Carbon $endDate): array
    {
        $labels = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->toDateString();
            $currentDate->addDay();
        }
        return $labels;
    }
}
