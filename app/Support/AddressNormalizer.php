<?php

namespace App\Support;

/**
 * Turns a typed address into a comparison key, so that
 *   "#3678 Sampaguita St., Barangay New Era, Quezon City"
 *   "3678 sampaguita street"
 * produce the same key ("3678 sampaguita street") and are grouped into one
 * household, while "Blk 5 Lot 3" and "Blk 5 Lot 4" stay different.
 *
 * The key is stored as readable text (not a hash) so staff can see why two
 * addresses matched.
 */
class AddressNormalizer
{
    /** Parts every address in the barangay shares — they carry no information. */
    private const LOCALITY = [
        'barangay new era', 'brgy new era', 'bgy new era', 'brgy. new era',
        'quezon city', 'q.c.', 'metro manila', 'philippines', 'district vi', 'district 6',
    ];

    /** Common abbreviations → one spelling. */
    private const WORDS = [
        'st' => 'street', 'str' => 'street', 'ave' => 'avenue', 'av' => 'avenue',
        'rd' => 'road', 'blk' => 'block', 'bk' => 'block', 'lt' => 'lot',
        'ext' => 'extension', 'subd' => 'subdivision', 'cor' => 'corner',
        'ph' => 'phase', 'bldg' => 'building',
    ];

    /** Filler words that don't identify a place. */
    private const DROP = ['no', 'num', 'number'];

    public static function key(?string $address): string
    {
        $a = mb_strtolower(trim((string) $address));

        foreach (self::LOCALITY as $part) {
            $a = str_replace($part, ' ', $a);
        }
        $a = preg_replace('/\bqc\b/', ' ', $a);

        // Punctuation (#, commas, periods, hyphens, slashes) → spaces
        $a = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $a);

        // "12 a" / "12-a" → "12a" (house-number suffix)
        $a = preg_replace('/\b(\d+) ([a-z])\b/', '$1$2', $a);

        $words = [];
        foreach (preg_split('/\s+/', trim($a)) as $w) {
            if ($w === '' || in_array($w, self::DROP, true)) {
                continue;
            }
            $words[] = self::WORDS[$w] ?? $w;
        }

        return implode(' ', $words);
    }
}
