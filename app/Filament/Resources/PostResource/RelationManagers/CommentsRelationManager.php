<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Filament\Resources\UserResource;
use App\Models\Comment;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Textarea::make('content')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('user.avatar_url')
                    ->label('User Avatar')
                    ->circular(),
                TextColumn::make('user.name')
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                TextColumn::make('content')
                    ->limit(100),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('content')
                    ->size(TextEntrySize::Large)
                    ->hiddenLabel(),
                Section::make('User')
                    ->columns(3)
                    ->relationship('user')
                    ->icon('heroicon-o-user')
                    ->headerActions([
                        Action::make('view')
                            ->url(fn (Comment $record): string => UserResource::getUrl('view', ['record' => $record->user_id])),
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

    public function isReadOnly(): bool
    {
        return false;
    }
}
