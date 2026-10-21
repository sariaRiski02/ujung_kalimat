<?php

return [
    // Rupiah yang didapat penulis untuk setiap view artikel premium.
    'rate_per_view' => (int) env('MONETIZATION_RATE_PER_VIEW', 35),

    // Minimal penarikan dalam Rupiah.
    'min_withdrawal' => (int) env('MONETIZATION_MIN_WITHDRAWAL', 10000),
];
