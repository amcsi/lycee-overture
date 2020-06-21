<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Card;
use amcsi\LyceeOverture\Card\CardBuilderFactory;
use amcsi\LyceeOverture\Card\CardResource;
use amcsi\LyceeOverture\I18n\Locale;
use Illuminate\Http\Request;

class CardController extends Controller
{
    private static $relations = ['set'];

    public function index(Request $request, CardBuilderFactory $builderFactory)
    {
        $locale = \App::getLocale();

        $limit = min(100, $request->get('limit', 10));

        $builder = $builderFactory->createBuilderWithQuery($locale, $request->query());
        $builder->with(self::$relations);

        if ($locale !== Locale::JAPANESE && $request->query('translatedFirst')) {
            // Bring forward cards with fewer kanjis (i.e. fewer untranslated bits).
            // Of course this is only necessary if the locale is non-Japanese.
            $builder->orderBy('t.kanji_count', 'asc');
        }
        $builder->orderBy($request->get('sort', 'created_at'), 'desc');
        $cards = $builder->paginate($limit);

        return CardResource::collection($cards);
    }

    public function show(Card $card)
    {
        $card->load(self::$relations);

        return new CardResource($card);
    }
}
