<?php

namespace MuslimsCommunity\PrayerTimes\Enums;

enum CalculationMethod: string
{
    case MWL = 'MWL';
    case ISNA = 'ISNA';
    case EGYPT = 'Egypt';
    case MAKKAH = 'Makkah';
    case KARACHI = 'Karachi';
    case CUSTOM = 'Custom';
}