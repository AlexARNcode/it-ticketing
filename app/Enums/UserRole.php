<?php

namespace App\Enums;

enum UserRole: string
{
    case TECH = 'tech';
    case USER = 'user';
    case ADMIN = 'admin';
}
