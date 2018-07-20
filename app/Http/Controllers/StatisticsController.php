<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Http\Controllers;

use amcsi\LyceeOverture\Etc\StatisticsTransformer;
use amcsi\LyceeOverture\I18n\Statistics\TranslationCoverageChecker;

class StatisticsController extends Controller
{
    public function index(
        TranslationCoverageChecker $translationCoverageChecker,
        StatisticsTransformer $statisticsTransformer
    ) {
        return $this->response->item($translationCoverageChecker, $statisticsTransformer);
    }
}
