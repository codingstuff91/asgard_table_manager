<?php

namespace App\Enums;

enum GameCategory: int
{
    case CARD_GAME = 1;
    case BOARD_GAME = 2;
    case ROLE_PLAYING_GAME = 3;
    case WARGAME = 4;
}
