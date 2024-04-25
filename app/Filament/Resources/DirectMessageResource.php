<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DirectMessageResource\Pages;
use App\Filament\Resources\DirectMessageResource\RelationManagers;
use App\Models\DirectMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DirectMessageResource extends Resource
{
    protected static ?string $model = DirectMessage::class;
    protected static ?string $navigationGroup = 'Support & Communication';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->native(false)
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->disabled()
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->disabled()
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->disabled()
                    ->maxLength(191),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->disabled()
                    ->maxLength(191),
                Forms\Components\Select::make('status')
                    ->default('Pending')
                    ->native(false)
                    ->options(DirectMessage::$adminStatusList)
                    ->required(),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => $state == 'adminApproved' ? 'Approved' : $state)
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'adminApproved' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListDirectMessages::route('/'),
            //'create' => Pages\CreateDirectMessage::route('/create'),
            'edit' => Pages\EditDirectMessage::route('/{record}/edit'),
        ];
    }
}