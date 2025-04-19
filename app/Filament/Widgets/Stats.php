<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Tamu;

class Stats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subMonth()->toDateString();
        $endDate = $this->filters['endDate'] ?? now()->toDateString();

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Mendefinisikan kategori tamu
        $categories = [
            'Mahasiswa' => 1,
            'Dosen' => 2,
            'Lainnya' => 3,
        ];

        $categoryData = [];
        $totalCounts = [];
        foreach ($categories as $label => $kategoriTamuId) {
            $data = Tamu::where('kategori_tamu_id', $kategoriTamuId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray();

            $chartData = $this->fillMissingDates($data, $startDate, $endDate);
            $total = array_sum($chartData);

            $categoryData[$label] = $chartData;
            $totalCounts[$label] = $total;
        }

        // Tentukan level berdasarkan total
        $sortedCategories = array_keys($totalCounts);
        arsort($totalCounts); // Urutkan total descending
        $rankedCategories = array_keys($totalCounts); // Ambil urutan kategori

        // Tentukan level
        $levels = [];
        foreach ($rankedCategories as $index => $label) {
            if ($index === 0) {
                $levels[$label] = 'Paling Tinggi';
            } elseif ($index === 1) {
                $levels[$label] = 'Medium';
            } else {
                $levels[$label] = 'Rendah';
            }
        }

        // Buat stats untuk setiap kategori
        $stats = [];
        foreach ($categories as $label => $kategoriTamuId) {
            $chartData = $categoryData[$label];
            $total = $totalCounts[$label];
            $level = $levels[$label];

            $trend = $this->calculateTrend($chartData);

            $stats[] = Stat::make($label, $total)
                ->description("$level | {$trend['description']}")
                ->descriptionIcon($trend['icon'])
                ->chart(array_values($chartData))
                ->color($trend['color']);
        }

        return $stats;
    }

    private function fillMissingDates(array $data, Carbon $startDate, Carbon $endDate): array
    {
        $filledData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->toDateString();
            $filledData[$dateString] = $data[$dateString] ?? 0;
            $currentDate->addDay();
        }

        return $filledData;
    }

    private function calculateTrend(array $data): array
{
    // Pastikan ada cukup data untuk menghitung tren
    if (count($data) < 2) {
        return [
            'description' => 'Tidak ada data tren',
            'icon' => 'heroicon-o-exclamation-circle',
            'color' => 'gray',
        ];
    }

    // Ambil nilai pertama dan terakhir
    $first = reset($data);
    $last = end($data);

    // Hitung perubahan persentase
    $percentageChange = $first > 0 ? (($last - $first) / $first) * 100 : 0;

    // Tentukan deskripsi tren
    if ($percentageChange > 0) {
        $trendDescription = 'Meningkat';
        $icon = 'heroicon-m-arrow-trending-up';
        $color = 'success';
    } elseif ($percentageChange < 0) {
        $trendDescription = 'Menurun';
        $icon = 'heroicon-m-arrow-trending-down';
        $color = 'danger';
    } else {
        $trendDescription = 'Stabil';
        $icon = 'heroicon-m-minus';
        $color = 'warning';
    }

    return [
        'description' => $trendDescription,
        'icon' => $icon,
        'color' => $color,
    ];
}

}
