<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card\CardBuilderFactory;
use amcsi\LyceeOverture\Card\CardTransformer;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(CardTransformer $cardTransformer, Request $request, CardBuilderFactory $builderFactory)
    {
        $locale = \App::getLocale();

        $builder = $builderFactory->createBuilderWithQuery($locale, $request->query());

        if ($locale !== Locale::JAPANESE) {
            // Bring forward cards with fewer kanjis (i.e. fewer untranslated bits).
            // Of course this is only necessary if the locale is non-Japanese.
            $builder->orderBy('t.kanji_count', 'asc');
        }
        $builder->orderBy('id', 'asc');
        $cards = $builder->paginate(10);

        return $this->response->paginator($cards, $cardTransformer);
    }
}
