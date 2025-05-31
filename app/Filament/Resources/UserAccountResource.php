<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAccountResource\Pages;
use App\Filament\Resources\UserAccountResource\RelationManagers;
use App\Models\UserAccount;
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
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Hash;

class UserAccountResource extends Resource
{
    protected static ?string $model = UserAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Akun Pengguna';

    protected static ?string $modelLabel = 'Akun Pengguna';

    protected static ?string $pluralModelLabel = 'Data Akun Pengguna';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->description('Data login dan identitas pengguna')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nama lengkap')
                                    ->prefixIcon('heroicon-o-user'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->required()
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('user@example.com')
                                    ->prefixIcon('heroicon-o-envelope'),
                            ]),
                    ]),

                Forms\Components\Section::make('Keamanan')
                    ->description('Pengaturan password dan keamanan akun')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->maxLength(255)
                                    ->dehydrated(fn($state) => filled($state))
                                    ->prefixIcon('heroicon-o-key')
                                    ->helperText('Minimal 8 karakter')
                                    ->placeholder(
                                        fn(string $context): string =>
                                        $context === 'edit' ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan password'
                                    ),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->maxLength(255)
                                    ->same('password')
                                    ->dehydrated(false)
                                    ->prefixIcon('heroicon-o-key')
                                    ->placeholder(
                                        fn(string $context): string =>
                                        $context === 'edit' ? 'Kosongkan jika tidak ingin mengubah' : 'Ulangi password'
                                    ),
                            ]),
                    ]),

                Forms\Components\Section::make('Status & Verifikasi')
                    ->description('Status akun dan verifikasi email')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Diverifikasi Pada')
                            ->displayFormat('d/m/Y H:i:s')
                            ->placeholder('Email belum diverifikasi')
                            ->helperText('Tanggal dan waktu verifikasi email')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => $record->email)
                    ->wrap(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email berhasil disalin!')
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->getStateUsing(fn($record) => !is_null($record->email_verified_at))
                    ->tooltip(fn($record) => $record->email_verified_at
                        ? 'Terverifikasi pada: ' . $record->email_verified_at->format('d M Y, H:i')
                        : 'Email belum terverifikasi'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\Action::make('verify_email')
                    ->label('Verifikasi Email')
                    ->icon('heroicon-o-shield-check')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update(['email_verified_at' => now()]);
                    })
                    ->visible(fn($record) => is_null($record->email_verified_at))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Email')
                    ->modalDescription('Apakah Anda yakin ingin memverifikasi email pengguna ini?'),
                Tables\Actions\Action::make('unverify_email')
                    ->label('Batalkan Verifikasi')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('warning')
                    ->action(function ($record) {
                        $record->update(['email_verified_at' => null]);
                    })
                    ->visible(fn($record) => !is_null($record->email_verified_at))
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Verifikasi Email')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan verifikasi email pengguna ini?'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify_emails')
                        ->label('Verifikasi Email Terpilih')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['email_verified_at' => now()]);
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unverify_emails')
                        ->label('Batalkan Verifikasi Terpilih')
                        ->icon('heroicon-o-shield-exclamation')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each->update(['email_verified_at' => null]);
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('Belum ada akun pengguna')
            ->emptyStateDescription('Mulai dengan menambahkan akun pengguna baru.')
            ->emptyStateIcon('heroicon-o-users')
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
            'index' => Pages\ListUserAccounts::route('/'),
            'create' => Pages\CreateUserAccount::route('/create'),
            // 'view' => Pages\ViewUserAccount::route('/{record}'),
            'edit' => Pages\EditUserAccount::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
