<?php

namespace Tests\Unit;

use App\Support\AddressNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AddressNormalizerTest extends TestCase
{
    public static function sameAddress(): array
    {
        return [
            'full vs short'        => ['3678 Sampaguita St., Barangay New Era, Quezon City', '3678 sampaguita street'],
            'hash + brgy + QC'     => ['#3678 Sampaguita Street, Brgy. New Era, QC', '3678 Sampaguita St'],
            'extra spaces/case'    => ['  3678   SAMPAGUITA   st. ', '3678 Sampaguita Street'],
            'block/lot spelling'   => ['Blk 5 Lot 3, Camia St.', 'Block 5, Lot 3, Camia Street'],
            'house letter suffix'  => ['12-A Rosal St.', '12A Rosal Street'],
            'no. filler'           => ['No. 45 Mabini St.', '45 Mabini Street'],
            'hyphenated street'    => ['8 Ilang-Ilang St.', '8 Ilang Ilang Street'],
        ];
    }

    #[DataProvider('sameAddress')]
    public function test_equivalent_addresses_share_a_key(string $a, string $b): void
    {
        $this->assertSame(AddressNormalizer::key($a), AddressNormalizer::key($b));
    }

    public function test_different_house_or_lot_numbers_stay_different(): void
    {
        $this->assertNotSame(AddressNormalizer::key('Blk 5 Lot 3 Camia St'), AddressNormalizer::key('Blk 5 Lot 4 Camia St'));
        $this->assertNotSame(AddressNormalizer::key('12 Rosal St'), AddressNormalizer::key('12A Rosal St'));
        $this->assertNotSame(AddressNormalizer::key('12 Rosal St'), AddressNormalizer::key('12 Camia St'));
    }

    public function test_key_is_readable(): void
    {
        $this->assertSame('3678 sampaguita street', AddressNormalizer::key('3678 Sampaguita St., Barangay New Era, Quezon City'));
    }

    public function test_empty_address_gives_empty_key(): void
    {
        $this->assertSame('', AddressNormalizer::key(null));
        $this->assertSame('', AddressNormalizer::key(' , Barangay New Era, Quezon City '));
    }
}
