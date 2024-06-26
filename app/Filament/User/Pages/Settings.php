<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.user.pages.settings';

}
