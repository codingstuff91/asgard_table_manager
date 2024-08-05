<?php

namespace App\Handlers;

use App\Commands\UpdateTableCommand;
use App\DataObjects\TableData;
use Exception;
use Illuminate\Support\Facades\Log;

class UpdateTableHandler
{
    public function handle(UpdateTableCommand $command)
    {
        try {
            $tableAttributes = TableData::fromRequest($command->table->day, $command->request);

            return $command->table->update($tableAttributes->toArray());
        } catch (Exception $e) {
            Log::error('Error updating table: '.$e->getMessage());

            return redirect()->route('days.show', $command->table->day)->with(['error' => 'Une erreur est survenue lors de la mise à jour de la table.']);
        }
    }
}
