<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Models\Card;
use amcsi\LyceeOverture\I18n\ManualTranslation\SuggestionResource;
use amcsi\LyceeOverture\Models\Suggestion;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterDataController
{
    public function index()
    {
        return new JsonResource([
            'newestCardLastImported' => Card::latest()->value('created_at'),
            'newestSuggestion' => SuggestionResource::make(Suggestion::latest()->with('creator')->first()),
        ]);
    }
}
