<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\PostResource;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return PostResource::form($form);
    }

    public function table(Table $table): Table
    {
        return PostResource::table($table);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return PostResource::infolist($infolist);
    }
}
