<?php

namespace App\Enums;

enum MatchStatus: string
{
    case Ongoing = 'ongoing';
    case Upcoming = 'upcoming';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Tied = 'Tied';
}
