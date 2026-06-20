<?php

namespace App\Enums;

enum AuthMessage: string
{
    case Unauthorized = 'Identifiants invalides';
    case LogoutSuccess = 'Déconnexion réussie';
    case TokenExpired = 'Token expiré';
    case TokenInvalid = 'Token invalide';
    case TokenMissing = 'Token absent';
}
