<?php

namespace App\Enums;

enum TaskStatus: int
{
    case TODO             = 0;
    case IN_PROGRESS      = 1;
    case READY_FOR_TEST   = 2;
    case PO_REVIEW        = 3;
    case DONE             = 4;
    case REJECTED         = 5;


    public function isTodo(): bool
    {
        return $this === self::TODO;
    }

    public function isInProgress(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    public function isReadyForTest(): bool
    {
        return $this === self::READY_FOR_TEST;
    }

    public function isPoReview(): bool
    {
        return $this === self::PO_REVIEW;
    }

    public function isDone(): bool
    {
        return $this === self::DONE;
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::TODO             => "Todo",
            self::IN_PROGRESS      => "In Progress",
            self::READY_FOR_TEST   => "Ready For Test",
            self::PO_REVIEW        => "PO Review",
            self::DONE             => "Done",
            self::REJECTED         => "Rejected",
        };
    }
}
