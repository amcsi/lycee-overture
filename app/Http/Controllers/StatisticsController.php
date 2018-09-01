<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Etc\StatisticsTransformer;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(
        TranslationCoverageChecker $translationCoverageChecker,
        StatisticsTransformer $statisticsTransformer,
        Request $request
    ) {
        return $this->response->item($translationCoverageChecker->calculateStatistics($request->query()), $statisticsTransformer);
    }
}
