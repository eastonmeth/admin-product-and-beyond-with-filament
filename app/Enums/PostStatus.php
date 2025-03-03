<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PostStatus implements HasColor, HasIcon, HasLabel
{
    case IN_REVIEW;
    case APPROVED;
    case DECLINED;

    public function getColor(): string
    {
        return match ($this) {
            self::IN_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::DECLINED => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::IN_REVIEW => 'heroicon-o-magnifying-glass',
            self::APPROVED => 'heroicon-o-check-circle',
            self::DECLINED => 'heroicon-o-x-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::IN_REVIEW => 'In Review',
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
        };
    }
}
