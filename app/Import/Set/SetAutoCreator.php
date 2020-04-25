<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Import\Set;

use amcsi\LyceeOverture\Set;
use Illuminate\Support\Collection;

/**
 * Gets or creates the set ID by the full Japanese name of a set plus the version number.
 */
class SetAutoCreator
{
    /** @var Collection|Set[] */
    private $sets;
    private $setModel;

    public function __construct($sets, Set $setModel)
    {
        $this->setSetsAndKeyByFullName(collect($sets));
        $this->setModel = $setModel;
    }

    public function getOrCreateSetIdByJaFullName($fullNameJa): ?int
    {
        $return = null;
        if (isset($this->sets[$fullNameJa])) {
            // There is a matching set.
            $return = $this->sets[$fullNameJa]->id;
        } elseif (strlen($fullNameJa) >= 3) {
            // Create a new set.

            [$nameJa, $version] = SetNameExtracter::extract($fullNameJa);

            $attributes = [
                'name_ja' => $nameJa,
                'version' => $version,
            ];

            $newSet = $this->setModel->forceCreate($attributes);
            $this->setSetsAndKeyByFullName($this->sets->push($newSet));
            $return = $newSet->id;
        }

        return $return;
    }

    /**
     * Sets the sets (lol) in the instance, re-keying by Japanese full name.
     */
    private function setSetsAndKeyByFullName(Collection $sets): void
    {
        $this->sets = $sets->keyBy(function (Set $set) {
            return trim(sprintf('%s %s', $set->name_ja, $set->version));
        });
    }
}
