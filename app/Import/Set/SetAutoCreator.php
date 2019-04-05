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
            $parts = explode(' ', $fullNameJa);
            $version = '';
            if (count($parts) > 1) {
                // The last component of the full name is the version.
                $version = array_pop($parts);
            }
            $nameJa = implode(' ', $parts);

            $attributes = [
                'name_ja' => $nameJa,
                'name_en' => $nameJa,
                'version' => $version,
                'brand' => '',
            ];

            if ($setMatchingNameJa = $this->sets->first(function (Set $set) use ($nameJa) {
                return $set->name_ja === $nameJa;
            })) {
                // There is an existing set with the same name, but different version.
                // Take the English translation and brand from there.
                $attributes['name_en'] = $setMatchingNameJa->name_en;
                $attributes['brand'] = $setMatchingNameJa->brand;
            }

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
            return sprintf('%s %s', $set->name_ja, $set->version);
        });
    }
}
