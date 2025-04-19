<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TamuResource\Pages;
use App\Filament\Resources\TamuResource\RelationManagers;
use App\Models\Tamu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\TamuExporter;
use Filament\Forms\Components\Tabs\Tab;

class TamuResource extends Resource
{
    protected static ?string $model = Tamu::class;

    protected static ?string $navigationLabel = 'Daftar Tamu';

    protected static ?string $pluralLabel = 'Daftar Tamu';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    
    
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->email(),
            Forms\Components\TextInput::make('phone')
                ->tel()
                ->required(),
            Forms\Components\TextInput::make('address')
                ->required(),

            // Kategori Tamu
            Forms\Components\Select::make('kategori_tamu_id')
                ->relationship('kategori_tamu', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $kategoriTamu = \App\Models\KategoriTamu::where('id', $state)->first();
                    if ($kategoriTamu && strtolower($kategoriTamu->name) !== 'Lainnya') {
                        $set('kategori_tamu_lainnya', null); // Kosongkan jika bukan "lainnya".
                    }
                }),
            Forms\Components\TextInput::make('kategori_tamu_lainnya')
                ->label('Kategori Tamu Lainnya')
                ->visible(fn ($get) => \App\Models\KategoriTamu::where('id', $get('kategori_tamu_id'))
                    ->where('name', 'Lainnya')
                    ->exists()) 
                ->required(fn ($get) => \App\Models\KategoriTamu::where('id', $get('kategori_tamu_id'))
                    ->where('name', 'Lainnya')
                    ->exists()),
            Forms\Components\Select::make('jurusan_id')
                ->relationship('jurusan', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $jurusan = \App\Models\Jurusan::where('id', $state)->first();
                    if ($jurusan && strtolower($jurusan->name) !== 'lainnya') {
                        $set('jurusan_lainnya', null); // Kosongkan jika bukan "lainnya".
                    }
                }),
            Forms\Components\TextInput::make('jurusan_lainnya')
                ->label('Jurusan Lainnya')
                ->visible(fn ($get) => \App\Models\Jurusan::where('id', $get('jurusan_id'))
                    ->where('name', 'Lainnya')
                    ->exists())
                ->required(fn ($get) => \App\Models\Jurusan::where('id', $get('jurusan_id'))
                    ->where('name', 'Lainnya')
                    ->exists()),
            Forms\Components\Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])
                ->required(),
            Forms\Components\Textarea::make('description')
                ->required(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                ->exporter(TamuExporter::class)
                ->label('Export Data'),
            ])
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
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                   ,
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTamus::route('/'),
            'create' => Pages\CreateTamu::route('/create'),
            'edit' => Pages\EditTamu::route('/{record}/edit'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
