<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Etc\StatisticsResource;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(TranslationCoverageChecker $translationCoverageChecker, Request $request)
    {
        return new StatisticsResource($translationCoverageChecker->calculateStatistics($request->query()));
    }
}
