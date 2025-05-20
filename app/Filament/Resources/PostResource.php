<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image_url')
                    ->image()
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('likes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('IN_REVIEW'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.avatar_url')
                    ->label('User Avatar')
                    ->circular(),
                TextColumn::make('user.name')
                    ->weight(FontWeight::Bold),
                ImageColumn::make('image_url')
                    ->label('Image'),
                TextColumn::make('title')
                    ->label('Content')
                    ->description(fn (Post $record): string => Str::limit($record->description, 60))
                    ->limit(60)
                    ->searchable(['title', 'description']),
                TextColumn::make('likes')
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-c-heart')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('Like')
                    ->action(function (Post $record): void {
                        $record->likes++;
                        $record->save();
                    })
                    ->color('danger')
                    ->icon('heroicon-c-heart'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('title')
                    ->weight(FontWeight::Bold)
                    ->hiddenLabel(),
                ImageEntry::make('image_url')
                    ->hiddenLabel(),
                TextEntry::make('description')
                    ->hiddenLabel(),
                Section::make('Details')
                    ->columns(4)
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('likes')
                            ->badge()
                            ->color('danger')
                            ->icon('heroicon-c-heart')
                            ->numeric(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
                Section::make('User Details')
                    ->relationship('user')
                    ->columns(3)
                    ->icon('heroicon-o-user')
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->label('User Avatar')
                            ->circular(),
                        TextEntry::make('name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('email'),
                    ])
                    ->headerActions([
                        Action::make('View')
                            ->url(fn (Post $record): string => UserResource::getUrl('view', ['record' => $record->user_id])),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
