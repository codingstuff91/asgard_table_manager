<?php

namespace App\Enums;

enum EmbedColor: int
{
    case CREATED_OR_UPDATED = 65280;
    case DELETED = 16711680;
    case USER_SUBSCRIBED = 65280;
    case USER_UNSUBSCRIBED = 16711680;
}
