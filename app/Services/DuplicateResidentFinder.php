<?php

namespace App\Services;

use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Finds residents who are probably the same person as the one being registered,
 * so staff can open the existing record instead of creating a duplicate.
 *
 * A duplicate record would let one person claim a benefit twice (two records,
 * two claims), so this is the other half of Task 1.1's double-claim protection.
 *
 * Likely duplicate =
 *   - same birthdate and a similar name ("Ma. Santos" / "Maria Santos",
 *     one-letter typos), or
 *   - the same first and last name born within a year (catches a mistyped birthdate).
 * Differing middle initials rule a match out (e.g. twins, namesakes).
 */
class DuplicateResidentFinder
{
    /** @return Collection<int, Resident> */
    public function find(array $data, ?int $exceptId = null): Collection
    {
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['birthdate'])) {
            return collect();
        }

        try {
            $birthdate = Carbon::parse($data['birthdate']);
        } catch (\Throwable) {
            return collect();
        }

        $sameDay = Resident::with('purok')
            ->whereDate('birthdate', $birthdate->toDateString())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))
            ->limit(200)
            ->get();

        $sameNameNearYear = Resident::with('purok')
            ->where('last_name', $data['last_name'])
            ->where('first_name', $data['first_name'])
            ->whereBetween('birthdate', [$birthdate->copy()->subYear()->toDateString(), $birthdate->copy()->addYear()->toDateString()])
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId))
            ->limit(50)
            ->get();

        return $sameDay
            ->filter(fn (Resident $r) => $this->similarNames($data, $r))
            ->merge($sameNameNearYear)
            ->unique('id')
            ->reject(fn (Resident $r) => $this->middleInitialsConflict($data['middle_name'] ?? null, $r->middle_name))
            ->values();
    }

    private function similarNames(array $data, Resident $r): bool
    {
        return $this->similarLast($data['last_name'], $r->last_name)
            && $this->similarFirst($data['first_name'], $r->first_name);
    }

    private function similarLast(string $a, string $b): bool
    {
        $a = self::norm($a);
        $b = self::norm($b);

        return $a === $b || levenshtein($a, $b) <= 1;       // "Dela Cruz" = "Delacruz"; one typo
    }

    private function similarFirst(string $a, string $b): bool
    {
        $a = self::norm(self::expand($a));
        $b = self::norm(self::expand($b));

        if ($a === $b) {
            return true;
        }
        // "Jun" vs "Junior", "Liza" vs "Lizabeth" — a short name that starts the longer one
        if (strlen(min($a, $b)) >= 2 && (str_starts_with($a, $b) || str_starts_with($b, $a))) {
            return true;
        }

        return levenshtein($a, $b) <= (min(strlen($a), strlen($b)) >= 5 ? 2 : 1)
            || metaphone($a) === metaphone($b);
    }

    private function middleInitialsConflict(?string $a, ?string $b): bool
    {
        $a = self::norm((string) $a);
        $b = self::norm((string) $b);

        return $a !== '' && $b !== '' && $a[0] !== $b[0];
    }

    /** "Ma." / "Ma" → "Maria" (common abbreviation in Filipino names) */
    private static function expand(string $name): string
    {
        return preg_replace('/^\s*ma\.?(\s|$)/i', 'Maria$1', $name);
    }

    private static function norm(string $s): string
    {
        return preg_replace('/[^a-z]/', '', mb_strtolower($s));
    }
}
