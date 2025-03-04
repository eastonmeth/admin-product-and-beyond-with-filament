<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image_url')
                    ->label('Image')
                    ->image()
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
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
                    ->weight(FontWeight::Bold)
                    ->sortable(),
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
                    ->sortable(),
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
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (Post $record): bool => $record->user_id === auth()->id()),
                DeleteAction::make()
                    ->visible(fn (Post $record): bool => $record->user_id === auth()->id()),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title')
                    ->hiddenLabel()
                    ->size(TextEntrySize::Large)
                    ->weight(FontWeight::SemiBold),
                ImageEntry::make('image_url')
                    ->alignCenter()
                    ->hiddenLabel()
                    ->height(300)
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->hiddenLabel()
                    ->columnSpanFull(),
                Section::make('Details')
                    ->columns(3)
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('likes')
                            ->badge()
                            ->color('danger')
                            ->icon('heroicon-c-heart')
                            ->numeric(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
                Section::make('User')
                    ->columns(3)
                    ->relationship('user')
                    ->icon('heroicon-o-user')
                    ->headerActions([
                        Action::make('view')
                            ->url(fn (Post $record): string => UserResource::getUrl('view', ['record' => $record->user_id])),
                    ])
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->label('Avatar')
                            ->size(50)
                            ->circular(),
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
