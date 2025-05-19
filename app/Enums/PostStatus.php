<?php

namespace App\Enums;

enum PostStatus
{
    case IN_REVIEW;
    case APPROVED;
    case DECLINED;
}
