<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Enums\PostStatus;
use App\Filament\Resources\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All'),
            ...collect(PostStatus::cases())
                ->map(
                    fn (PostStatus $status) => Tab::make($status->getLabel())
                        ->icon($status->getIcon())
                        ->query(fn (Builder $query) => $query->where('status', $status))
                ),
        ];
    }
}
