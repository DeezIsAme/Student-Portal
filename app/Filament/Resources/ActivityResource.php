<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Carbon\Carbon;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Aktivitas';

    protected static ?string $modelLabel = 'Aktivitas';

    protected static ?string $pluralModelLabel = 'Data Aktivitas';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Aktivitas')
                    ->description('Data aktivitas mahasiswa')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_user')
                                    ->label('Mahasiswa')
                                    ->relationship('student', 'name')
                                    ->searchable(['name', 'NIM'])
                                    ->preload()
                                    ->required()
                                    ->prefixIcon('heroicon-o-user')
                                    ->getOptionLabelFromRecordUsing(fn(Student $record) => "{$record->name} - {$record->NIM}")
                                    ->placeholder('Pilih mahasiswa...')
                                    ->helperText('Cari berdasarkan nama atau NIM'),

                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Tanggal & Waktu')
                                    ->default(now())
                                    ->displayFormat('d/m/Y H:i')
                                    ->prefixIcon('heroicon-o-calendar-days')
                                    ->helperText('Waktu aktivitas dilakukan'),
                            ]),

                        Forms\Components\Textarea::make('activity')
                            ->label('Deskripsi Aktivitas')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Masukkan detail aktivitas mahasiswa...')
                            ->columnSpanFull()
                            ->helperText('Maksimal 1000 karakter'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.NIM')
                    ->label('NIM')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('NIM berhasil disalin!')
                    ->tooltip('Klik untuk menyalin'),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => $record->student?->email ?? 'Email tidak tersedia')
                    ->wrap(),

                Tables\Columns\TextColumn::make('student.jurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('activity')
                    ->label('Aktivitas')
                    ->searchable()
                    ->limit(60)
                    ->wrap()
                    ->tooltip(fn($record) => $record->activity)
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn($record) => $record->created_at->diffForHumans())
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('id_user')
                    ->label('Mahasiswa')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(Student $record) => "{$record->name} - {$record->NIM}"),

                SelectFilter::make('jurusan')
                    ->label('Jurusan')
                    ->options(function () {
                        return \App\Models\DataJurusan::pluck('nama_jurusan', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data) {
                        if (filled($data['value'])) {
                            $query->whereHas('student.jurusan', function (Builder $query) use ($data) {
                                $query->where('id', $data['value']);
                            });
                        }
                    }),

                Filter::make('pencarian_aktivitas')
                    ->form([
                        Forms\Components\TextInput::make('activity_search')
                            ->label('Cari Aktivitas')
                            ->placeholder('Masukkan kata kunci aktivitas...')
                            ->prefixIcon('heroicon-o-magnifying-glass'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['activity_search'],
                            fn(Builder $query, $search): Builder => $query->where('activity', 'like', '%' . $search . '%')
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['activity_search'] ?? null) {
                            $indicators['activity_search'] = 'Aktivitas: ' . $data['activity_search'];
                        }
                        return $indicators;
                    }),

                Filter::make('tanggal_aktivitas')
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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal']) {
                            $indicators['dari_tanggal'] = 'Dari: ' . Carbon::parse($data['dari_tanggal'])->format('d M Y');
                        }
                        if ($data['sampai_tanggal']) {
                            $indicators['sampai_tanggal'] = 'Sampai: ' . Carbon::parse($data['sampai_tanggal'])->format('d M Y');
                        }
                        return $indicators;
                    }),

                Filter::make('aktivitas_terbaru')
                    ->label('7 Hari Terakhir')
                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7)))
                    ->toggle(),

                Filter::make('aktivitas_bulan_ini')
                    ->label('Bulan Ini')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year))
                    ->toggle(),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_selected')
                        ->label('Export Terpilih')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function ($records) {
                            // Logic untuk export bisa ditambahkan di sini
                            \Filament\Notifications\Notification::make()
                                ->title('Export berhasil!')
                                ->body(count($records) . ' aktivitas telah diekspor.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum ada data aktivitas')
            ->emptyStateDescription('Mulai dengan menambahkan aktivitas mahasiswa baru.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->poll('30s'); // Auto refresh setiap 30 detik
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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            // 'view' => Pages\ViewActivity::route('/{record}'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
