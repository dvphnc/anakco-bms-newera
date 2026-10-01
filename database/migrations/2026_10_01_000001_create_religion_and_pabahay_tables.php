<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Part 2 — religion classification and Pabahay (minister housing).
 *
 *  - religions: a fixed list instead of free typing. INC is flagged (is_inc) so the
 *    "INC / Non-INC" split never depends on how a name is spelled.
 *  - pabahays + pabahay_units: the housing blocks for ministers' families and the
 *    units inside them.
 *  - residents: religion_id replaces the old free-text religion, plus the
 *    "Family of Ministers" flag and the Pabahay unit they live in.
 *
 * Existing free-text religions are matched to the list (spelling variants are merged,
 * anything unknown is added as its own entry) before the old column is dropped.
 */
return new class extends Migration
{
    private const DEFAULTS = [
        // [name, is_inc]
        ['Roman Catholic', false],
        ['Iglesia ni Cristo', true],
        ['Born Again Christian', false],
        ['Protestant', false],
        ['Islam', false],
        ['Seventh-day Adventist', false],
        ["Jehovah's Witnesses", false],
        ['Aglipayan', false],
        ['Buddhism', false],
        ['Other', false],
    ];

    public function up(): void
    {
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_inc')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pabahays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('location')->nullable();
            $table->foreignId('purok_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pabahay_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pabahay_id')->constrained()->cascadeOnDelete();
            $table->string('unit_no', 30);
            $table->string('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['pabahay_id', 'unit_no']);
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->foreignId('religion_id')->nullable()->after('nationality')->constrained()->nullOnDelete();
            $table->boolean('is_minister_family')->default(false)->after('religion_id');
            $table->foreignId('pabahay_unit_id')->nullable()->after('is_minister_family')->constrained('pabahay_units')->nullOnDelete();

            $table->index('is_minister_family');
        });

        $this->moveOldReligionsIntoTheList();

        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('religion');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('religion', 100)->nullable()->after('nationality');
        });

        DB::statement('UPDATE residents r JOIN religions g ON g.id = r.religion_id SET r.religion = g.name');

        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['pabahay_unit_id']);
            $table->dropForeign(['religion_id']);
            $table->dropIndex(['is_minister_family']);
            $table->dropColumn(['religion_id', 'is_minister_family', 'pabahay_unit_id']);
        });

        Schema::dropIfExists('pabahay_units');
        Schema::dropIfExists('pabahays');
        Schema::dropIfExists('religions');
    }

    private function moveOldReligionsIntoTheList(): void
    {
        $now = now();
        foreach (self::DEFAULTS as [$name, $isInc]) {
            DB::table('religions')->insert(['name' => $name, 'is_inc' => $isInc, 'created_at' => $now, 'updated_at' => $now]);
        }

        // Spelling variants people actually type → the name in the list. null = "no religion".
        $variants = [
            'inc' => 'Iglesia ni Cristo', 'iglesianicristo' => 'Iglesia ni Cristo', 'iglesianikristo' => 'Iglesia ni Cristo',
            'catholic' => 'Roman Catholic', 'romancatholic' => 'Roman Catholic', 'rc' => 'Roman Catholic',
            'bornagain' => 'Born Again Christian', 'bornagainchristian' => 'Born Again Christian', 'bornagainchristians' => 'Born Again Christian',
            'protestant' => 'Protestant',
            'islam' => 'Islam', 'muslim' => 'Islam', 'islamic' => 'Islam',
            'sda' => 'Seventh-day Adventist', 'seventhdayadventist' => 'Seventh-day Adventist', 'adventist' => 'Seventh-day Adventist',
            'jw' => "Jehovah's Witnesses", 'jehovahswitness' => "Jehovah's Witnesses", 'jehovahswitnesses' => "Jehovah's Witnesses",
            'aglipay' => 'Aglipayan', 'aglipayan' => 'Aglipayan', 'philippineindependentchurch' => 'Aglipayan',
            'buddhism' => 'Buddhism', 'buddhist' => 'Buddhism',
            'other' => 'Other', 'others' => 'Other',
            'none' => null, 'noreligion' => null, 'na' => null,
        ];

        $raw = DB::table('residents')->whereNotNull('religion')->where('religion', '!=', '')->distinct()->pluck('religion');
        foreach ($raw as $typed) {
            $key = preg_replace('/[^a-z0-9]/', '', strtolower($typed));
            if ($key === '') {
                continue;
            }

            // Unknown spellings are kept as their own entry (tidied up) so no information is lost
            $name = array_key_exists($key, $variants) ? $variants[$key] : mb_convert_case(trim($typed), MB_CASE_TITLE);
            if ($name === null) {
                continue;
            }

            $id = DB::table('religions')->where('name', $name)->value('id')
                ?? DB::table('religions')->insertGetId(['name' => $name, 'is_inc' => false, 'created_at' => $now, 'updated_at' => $now]);

            DB::table('residents')->where('religion', $typed)->update(['religion_id' => $id]);
        }
    }
};
