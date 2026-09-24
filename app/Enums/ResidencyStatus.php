<?php

namespace App\Enums;

/**
 * Life / residency status of a resident: Alive (Buhay), Deceased (Namatay),
 * Moved Out (Umalis).
 *
 * The stored values are the original ones ('Active', 'Deceased', 'Transferred').
 * Dozens of queries and views compare against those strings, so only the labels
 * shown to staff changed.
 *
 * Do NOT add this enum as an Eloquent cast on residents.residency_status: the
 * attribute would become an object and every `=== 'Active'` comparison in the
 * codebase would silently turn false. Use the static helpers below instead.
 */
enum ResidencyStatus: string
{
    case Alive    = 'Active';
    case Deceased = 'Deceased';
    case MovedOut = 'Transferred';

    public function label(): string
    {
        return match ($this) {
            self::Alive    => 'Alive',
            self::Deceased => 'Deceased',
            self::MovedOut => 'Moved Out',
        };
    }

    public function filipino(): string
    {
        return match ($this) {
            self::Alive    => 'Buhay',
            self::Deceased => 'Namatay',
            self::MovedOut => 'Umalis',
        };
    }

    public function fullLabel(): string
    {
        return $this->label().' ('.$this->filipino().')';
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Alive    => 'badge-green',
            self::Deceased => 'badge-gray',
            self::MovedOut => 'badge-yellow',
        };
    }

    /** Display label for a raw stored value; tolerates null/unknown values. */
    public static function labelFor(?string $value): string
    {
        return self::tryFrom((string) $value)?->label() ?? ($value ?: '—');
    }

    public static function badgeFor(?string $value): string
    {
        return self::tryFrom((string) $value)?->badgeClass() ?? 'badge-gray';
    }

    /** Stored value => "Alive (Buhay)" — for <select> options. */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->fullLabel();
        }

        return $options;
    }

    /** @return string[] the stored values, for validation rules */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
