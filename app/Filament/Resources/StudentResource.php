<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $modelLabel = 'Mahasiswa';

    protected static ?string $pluralModelLabel = 'Data Mahasiswa';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->description('Data dasar mahasiswa')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama lengkap')
                                    ->prefixIcon('heroicon-o-user'),

                                Forms\Components\TextInput::make('NIM')
                                    ->label('Nomor Induk Mahasiswa')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20)
                                    ->placeholder('Contoh: 2021001001')
                                    ->prefixIcon('heroicon-o-identification'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->required()
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('student@example.com')
                                    ->prefixIcon('heroicon-o-envelope'),

                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->maxDate(now()->subYears(16))
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-calendar-days'),
                            ]),
                    ]),

                Forms\Components\Section::make('Informasi Kontak')
                    ->description('Detail kontak dan alamat')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(15)
                                    ->placeholder('08123456789')
                                    ->prefixIcon('heroicon-o-phone'),

                                Forms\Components\Select::make('jurusan_id')
                                    ->label('Jurusan')
                                    ->relationship('jurusan', 'nama_jurusan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-academic-cap'),
                            ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Masukkan alamat lengkap...')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('alamat_link')
                            ->label('Link OpenStreetMap')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://www.openstreetmap.org/...')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->helperText('Contoh: https://www.openstreetmap.org/#map=18/-6.2088/106.8456')
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open_map')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->url(fn($get) => $get('alamat_link'))
                                    ->openUrlInNewTab()
                                    ->visible(fn($get) => filled($get('alamat_link')))
                                    ->tooltip('Buka di OpenStreetMap')
                            ),
                    ]),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->description('Data pelengkap mahasiswa')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Forms\Components\Textarea::make('kesukaan')
                            ->label('Hobi & Minat')
                            ->rows(2)
                            ->maxLength(300)
                            ->placeholder('Tuliskan hobi dan minat...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('NIM')
                    ->label('NIM')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('NIM berhasil disalin!')
                    ->tooltip('Klik untuk menyalin'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->placeholder('Tidak ada data')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable()
                    ->description(function ($record) {
                        if ($record->tanggal_lahir) {
                            $umur = Carbon::parse($record->tanggal_lahir)->age;
                            return $umur . ' tahun';
                        }
                        return null;
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->wrap()
                    ->placeholder('Tidak ada data')
                    ->tooltip(fn($record) => $record->alamat)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Tables\Columns\IconColumn::make('alamat_link')
                //     ->label('Map')
                //     ->icon('heroicon-o-map-pin')
                //     ->color('success')
                //     ->boolean()
                //     ->url(fn($record) => $record->alamat_link)
                //     ->openUrlInNewTab()
                //     ->tooltip('Buka lokasi di OpenStreetMap')
                //     ->toggleable(),

                Tables\Columns\TextColumn::make('kesukaan')
                    ->label('Hobi')
                    ->limit(30)
                    ->placeholder('Tidak ada data')
                    ->badge()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama_jurusan')
                    ->searchable()
                    ->preload(),

                Filter::make('pencarian_alamat')
                    ->form([
                        Forms\Components\TextInput::make('alamat_search')
                            ->label('Cari Alamat')
                            ->placeholder('Masukkan kata kunci alamat...')
                            ->prefixIcon('heroicon-o-magnifying-glass'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['alamat_search'],
                            fn(Builder $query, $search): Builder => $query->where('alamat', 'like', '%' . $search . '%')
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['alamat_search'] ?? null) {
                            $indicators['alamat_search'] = 'Alamat: ' . $data['alamat_search'];
                        }
                        return $indicators;
                    }),

                Filter::make('lokasi_area')
                    ->form([
                        Forms\Components\Select::make('area')
                            ->label('Area/Wilayah')
                            ->options([
                                'jakarta' => 'Jakarta',
                                'bogor' => 'Bogor',
                                'depok' => 'Depok',
                                'tangerang' => 'Tangerang',
                                'bekasi' => 'Bekasi',
                                'bandung' => 'Bandung',
                                'surabaya' => 'Surabaya',
                                'yogyakarta' => 'Yogyakarta',
                                'semarang' => 'Semarang',
                                'medan' => 'Medan',
                            ])
                            ->searchable()
                            ->placeholder('Pilih area...')
                            ->prefixIcon('heroicon-o-map-pin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['area'],
                            fn(Builder $query, $area): Builder => $query->where('alamat', 'like', '%' . $area . '%')
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['area'] ?? null) {
                            $indicators['area'] = 'Area: ' . ucfirst($data['area']);
                        }
                        return $indicators;
                    }),

                Filter::make('dengan_alamat')
                    ->label('Memiliki Alamat')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('alamat')->where('alamat', '!=', ''))
                    ->toggle(),

                Filter::make('dengan_map_link')
                    ->label('Memiliki Link Map')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('alamat_link')->where('alamat_link', '!=', ''))
                    ->toggle(),

                Filter::make('tanggal_lahir')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal_lahir', '<=', $date),
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\Action::make('lihat_lokasi')
                    ->label('Lihat Lokasi')
                    ->icon('heroicon-o-map-pin')
                    ->color('success')
                    ->url(fn($record) => $record->alamat_link)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => filled($record->alamat_link))
                    ->tooltip('Buka lokasi di OpenStreetMap'),

                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data mahasiswa')
            ->emptyStateDescription('Mulai dengan menambahkan mahasiswa baru.')
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession();
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            // 'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
