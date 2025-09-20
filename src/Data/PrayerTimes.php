<?php

namespace MuslimsCommunity\PrayerTimes\Data;

class PrayerTimes
{
    public function __construct(
        public string $fajr,
        public string $sunrise,
        public string $dhuhr,
        public string $asr,
        public string $maghrib,
        public string $isha
    ) {}

    public function toArray(): array
    {
        return [
            'fajr' => $this->fajr,
            'sunrise' => $this->sunrise,
            'dhuhr' => $this->dhuhr,
            'asr' => $this->asr,
            'maghrib' => $this->maghrib,
            'isha' => $this->isha,
        ];
    }
}