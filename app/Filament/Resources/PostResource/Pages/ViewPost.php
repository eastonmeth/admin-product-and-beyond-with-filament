<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('like')
                ->color('danger')
                ->icon('heroicon-c-heart')
                ->action(function (Post $record) {
                    $record->likes++;
                    $record->save();
                }),
            Actions\EditAction::make(),
        ];
    }
}
