<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        if (blank($data['password']) || blank($data['password_confirmation'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        return $data;
    }
}
