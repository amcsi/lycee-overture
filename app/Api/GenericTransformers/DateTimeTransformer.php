<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Api\GenericTransformers;

class DateTimeTransformer
{
    public function transform(\DateTimeInterface $dateTime): string
    {
        if ($dateTime instanceof \DateTime) {
            $dateTime = \DateTimeImmutable::createFromMutable($dateTime);
        }
        return $dateTime->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }
}
