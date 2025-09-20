<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Calculation Method
    |--------------------------------------------------------------------------
    |
    | This option controls the default calculation method for prayer times.
    | Available methods: MWL, ISNA, Egypt, Makkah, Karachi, Custom
    |
    */
    'method' => env('PRAYER_TIMES_METHOD', 'MWL'),

    /*
    |--------------------------------------------------------------------------
    | Asr Jurisdiction
    |--------------------------------------------------------------------------
    |
    | This option controls the Asr calculation method.
    | Available options: Standard, Hanafi
    |
    */
    'asr_jurisdiction' => env('PRAYER_TIMES_ASR_JURISDICTION', 'Standard'),

    /*
    |--------------------------------------------------------------------------
    | Default Timezone
    |--------------------------------------------------------------------------
    |
    | This option controls the default timezone offset in hours.
    | If null, the system will use the DateTime's timezone.
    |
    */
    'timezone' => env('PRAYER_TIMES_TIMEZONE', null),

    /*
    |--------------------------------------------------------------------------
    | Custom Angles (for Custom method only)
    |--------------------------------------------------------------------------
    |
    | These options are only used when method is set to 'Custom'.
    | Fajr and Isha angles in degrees.
    |
    */
    'fajr_angle' => env('PRAYER_TIMES_FAJR_ANGLE', null),
    'isha_angle' => env('PRAYER_TIMES_ISHA_ANGLE', null),
];