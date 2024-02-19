<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\DealResource\Pages;
use App\Models\Deal;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Management';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->required(),
                    Toggle::make('is_featured')
                        ->label('Featured')
                        ->required(),
                ])->columns(),
                Select::make('user_id')
                    ->native(false)
                    ->disabled()
                    ->hidden()
                    ->default(auth()->user()->id)
                    ->label('Select User')
                    ->relationship('user', 'name'),
                SelectTree::make('category_id')
                    ->label('Select Category')
                    ->enableBranchNode()
                    ->withCount()
                    ->emptyLabel('Oops! No Category Found')
                    ->relationship('category', 'name', 'parent_id', function ($query){
                        return $query->where('type', 'deal');
                    }),
                TextInput::make('title')
                    ->label('Enter Title')
                    ->live(onBlur: true)
                    ->required()
                    ->maxLength(191)
                    ->afterStateUpdated(function (Set $set, ?string $state){
                        $set('slug', Str::slug($state));
                        $set('seo.title', $state);
                }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(70),
                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\RichEditor::make('description')
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull(),
                Section::make('Images')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->directory('deals/thumbnail')
                            ->required(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->directory('deals/gallery')
                            ->maxFiles(4)
                            ->multiple(),
                    ])->columns(1),
                Section::make('Discount Details')
                ->schema([
                    Select::make('discount_type')
                        ->label('Select Discount Type')
                        ->required()
                        ->native(false)
                        ->options([
                            'percentage' => 'Percentage',
                            'fixed' => 'Fixed',
                        ])
                        ->default('percentage'),
                    TextInput::make('discount_value')
                        ->label('Discount Value')
                        ->required()
                        ->maxLength(191),
                ])->columns(),
                Forms\Components\RichEditor::make('terms_and_conditions')
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull(),
                Section::make('SEO Details')
                    ->relationship('seo')
                    ->schema([
                        TextInput::make('title')
                            ->label('Enter SEO Title')
                            ->required()
                            ->maxLength(70),
                        TagsInput::make('meta_keywords')
                            ->splitKeys(['Tab', ' ', ','])
                            ->label('Enter SEO Meta Keywords'),
                        TextInput::make('meta_description')
                            ->label('Enter SEO Meta Description')
                            ->maxLength(70),
                    ])->columns(1),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('discount_type')
                    ->description(function ($record){
                        return $record->discount_type == 'percentage' ? $record->discount_value.'%' : '₹'.$record->discount_value;
                    })
                    ->label('Type')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                // filters for status
                Tables\Filters\Filter::make('is_active')
                    ->label('Active')
                    ->modifyQueryUsing(function (Builder $query) {
                        $query->where('is_active', 1);
                    }),
                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured')
                    ->modifyQueryUsing(function (Builder $query) {
                        $query->where('is_featured', 1);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit' => Pages\EditDeal::route('/{record}/edit'),
        ];
    }
}