<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OfficeResource\Pages;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Humaidem\FilamentMapPicker\Fields\OSMMap;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    // untik membuat urutan tabel pada menu navigasi
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = "Office Management";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                OSMMap::make('location')
                                    ->label('Location')
                                    ->showMarker()
                                    ->draggable()
                                    ->extraControl([
                                        'zoomDelta'           => 1,
                                        'zoomSnap'            => 0.25,
                                        'wheelPxPerZoomLevel' => 60,
                                    ])
                                    ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, $record) {
                                        if ($record) {                  //kalau record sudah ada maka akan menjalankan perintah dibawah, jika belum ada data baru maka tidak akan menjalankan codingan dibawah
                                            $latitude  = $record->latitude; //codingan ini dipake untuk meng set marker untuk di maps
                                            $longitude = $record->longitude;

                                            if ($latitude && $longitude) {
                                                $set('location', ['lat' => $latitude, 'lng' => $longitude]);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $set('latitude', $state['lat']);
                                        $set('longitude', $state['lng']);
                                    })
                                    ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('longitude')
                                            ->required()
                                            ->numeric(),
                                    ])->columns((2)),
                            ]),

                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('radius')
                                    ->required()
                                    ->numeric(),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('radius')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index'  => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit'   => Pages\EditOffice::route('/{record}/edit'),
        ];
    }
}
