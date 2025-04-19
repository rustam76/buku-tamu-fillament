<?php

namespace App\Filament\Widgets;

use App\Models\Tamu;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class BarChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Jurusan';
   

    protected function getData(): array
    {
        // Ambil nilai startDate dan endDate dari filter
        $startDate = $this->filters['startDate'] ?? now()->subMonth()->toDateString();
        $endDate = $this->filters['endDate'] ?? now()->toDateString();

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Ambil data tamu per jurusan dalam rentang tanggal yang diberikan
        $data = Tamu::with('jurusan')
            ->whereBetween('created_at', [$startDate, $endDate])  // Filter berdasarkan tanggal
            ->selectRaw('jurusan_id, COUNT(*) as total')
            ->groupBy('jurusan_id')
            ->get();

        // Format data untuk chart
        $labels = $data->map(fn ($item) => $item->jurusan->name)->toArray();
        $values = $data->map(fn ($item) => $item->total)->toArray();

        // Ambil warna dari jurusan untuk background
        $backgroundColors = $this->getColorByJurusan($data);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tamu',
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Fungsi untuk memberikan warna berdasarkan nama jurusan
    private function getColorByJurusan($data): array
    {
        // Ambil warna berdasarkan nama jurusan
        return $data->map(function ($item) {
            // Pastikan model 'jurusan' memiliki atribut 'color' atau tentukan warna berdasarkan nama jurusan
            return $item->jurusan->color ?? '#FFEB3B'; // Jika tidak ada warna, gunakan warna default
        })->toArray();
    }

   
}
