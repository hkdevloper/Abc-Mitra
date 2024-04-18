<?php

namespace App\Filament\User\Resources\ForumResource\Pages;

use App\Filament\User\Resources\ForumResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use Str;

class CreateForum extends CreateRecord
{
    protected static string $resource = ForumResource::class;
    protected static bool $canCreateAnother = false;
    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Send Notification to Admin when a new forum is created
        Notification::make()
            ->title('New Forum Created.')
            ->body(Str::limit($data['title'], 70, '...'))
            ->actions([
                Actions\Action::make('view')
                    ->url('/user/forums'),
            ])
            ->sendToDatabase(User::find(1));
        return array_merge($data, [
            'company_id' => auth()->user()->company->id,
        ]);
    }

}
