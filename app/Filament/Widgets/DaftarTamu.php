<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Tamu;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DaftarTamu extends BaseWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getFilteredQuery())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori_tamu.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori_tamu_lainnya')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jurusan.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jurusan_lainnya')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->limit(20)
                    ->searchable(),
            ]);
    }

    private function getFilteredQuery()
    {
        $startDate = $this->filters['startDate'] ?? now()->subMonth()->toDateString();
        $endDate = $this->filters['endDate'] ?? now()->toDateString();

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        return Tamu::query()
            ->whereBetween('created_at', [$startDate, $endDate]);
    }

    public static function filters(): array
    {
        return [
            Tables\Filters\Filter::make('Tanggal')
                ->form([
                    Tables\Forms\Components\DatePicker::make('startDate')
                        ->label('Start Date')
                        ->reactive()
                        ->default(fn () => now()->subMonth()->toDateString()),
                    Tables\Forms\Components\DatePicker::make('endDate')
                        ->label('End Date')
                        ->reactive()
                        ->default(fn () => now()->toDateString()),
                ])
                ->query(function ($query, $data) {
                    $startDate = $data['startDate'] ?? now()->subMonth()->toDateString();
                    $endDate = $data['endDate'] ?? now()->toDateString();

                    $startDate = Carbon::parse($startDate)->startOfDay();
                    $endDate = Carbon::parse($endDate)->endOfDay();

                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                }),
        ];
    }
}
