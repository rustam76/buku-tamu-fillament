<?php

namespace App\Filament\Widgets;

use App\Models\Tamu;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PieChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Jenis Kelamin';

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

        // Ambil data tamu dan hitung jumlah jenis kelamin
        $data = Tamu::whereBetween('created_at', [$startDate, $endDate]) // Filter berdasarkan tanggal
            ->get()
            ->groupBy('jenis_kelamin'); // Kelompokkan berdasarkan jenis_kelamin

        // Hitung jumlah laki-laki dan perempuan
        $maleCount = $data->get('Laki-laki', collect())->count();
        $femaleCount = $data->get('Perempuan', collect())->count();

        // Format data untuk chart
        return [
            'datasets' => [
                [
                    'data' => [$maleCount, $femaleCount], // Data untuk Laki-laki dan Perempuan
                    'backgroundColor' => ['#4e73df', '#e74a3b'], // Warna chart
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'], // Label untuk chart
        ];
    }

    protected function getType(): string
    {
        return 'pie'; 
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true, // Membuat chart responsif
            'maintainAspectRatio' => false, // Memungkinkan untuk mengubah rasio aspek chart
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'height' => 300, // Menentukan tinggi chart
           
        ];
    }
}
