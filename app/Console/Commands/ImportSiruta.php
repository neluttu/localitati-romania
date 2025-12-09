<?php

namespace App\Console\Commands;

use App\Models\County;
use App\Models\Locality;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportSiruta extends Command
{
    protected $signature = 'siruta:import {file}';
    protected $description = 'Import SIRUTA 2025 (counties + localities)';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return Command::FAILURE;
        }

        $this->info("Loading file: $file ...");

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) < 2) {
            $this->error("Invalid file or no data.");
            return Command::FAILURE;
        }

        // --------------------------------------------------
        // 1. Read header and locate columns
        // --------------------------------------------------
        $header = array_map(fn($v) => strtolower(trim($v)), $rows[1]);

        $col = [
            'siruta' => array_search('siruta', $header),
            'denloc' => array_search('denloc', $header),
            'jud' => array_search('jud', $header),
            'sirsup' => array_search('sirsup', $header),
            'tip' => array_search('tip', $header),
            'codp' => array_search('codp', $header),
            'reg' => array_search('regiune', $header),
        ];

        foreach ($col as $key => $index) {
            if ($index === false) {
                $this->error("Missing required column: $key");
                return Command::FAILURE;
            }
        }

        $this->info("Columns detected:");
        foreach ($col as $key => $index) {
            $this->line("- $key → column index $index");
        }

        // --------------------------------------------------
        // 2. Hard reset tables
        // --------------------------------------------------
        $this->info("\nResetting tables...");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('localities')->truncate();
        DB::table('counties')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --------------------------------------------------
        // 3. Disable events for speed
        // --------------------------------------------------
        County::unsetEventDispatcher();
        Locality::unsetEventDispatcher();

        // --------------------------------------------------
        // 4. Import counties (TIP = 40)
        // --------------------------------------------------
        $this->info("\nImporting counties...");

        $countyIdByJud = [];

        foreach ($rows as $i => $row) {

            if ($i == 1)
                continue; // header

            $tip = (int) $row[$col['tip']];
            if ($tip !== 40)
                continue; // only counties

            $judCode = (int) $row[$col['jud']];

            $name = $this->normalizeCountyName($row[$col['denloc']] ?? '');

            $county = County::create([
                'siruta_code' => $row[$col['siruta']],
                'name' => ucfirst($name),
                'name_ascii' => $this->toAscii($name),
                'slug' => Str::slug($name),
                'code' => $judCode,
                'abbr' => $this->getCountyAbbr($name),
                'region' => $row[$col['reg']] ?? null,
            ]);

            $countyIdByJud[$judCode] = $county->id;
        }

        $this->info("Imported counties: " . count($countyIdByJud));

        // --------------------------------------------------
        // 5. Import localities (batch insert)
        // --------------------------------------------------
        $this->info("\nImporting localities (batch insert)...");

        $buffer = [];
        $batchSize = 1000;

        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $i => $row) {
            if ($i == 1) {
                $bar->advance();
                continue;
            }

            $tip = (int) $row[$col['tip']];
            if ($tip === 40) {
                $bar->advance();
                continue; // skip counties
            }

            $judCode = (int) $row[$col['jud']];

            if (!isset($countyIdByJud[$judCode])) {
                $bar->advance();
                continue;
            }

            $name = $this->normalizeLocalityName($row[$col['denloc']] ?? '');
            $postal = $this->normalizePostalCode($row[$col['codp']] ?? null);

            $buffer[] = [
                'siruta_code' => $row[$col['siruta']],
                'siruta_parent' => $row[$col['sirsup']] ?? null,
                'county_id' => $countyIdByJud[$judCode],
                'name' => $name,
                'name_ascii' => $this->toAscii($name),
                'type' => $row[$col['tip']],
                'postal_code' => str_pad($postal, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($buffer) >= $batchSize) {
                Locality::insert($buffer);
                $buffer = [];
            }

            $bar->advance();
        }

        if (!empty($buffer)) {
            Locality::insert($buffer);
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Localities imported successfully!");
        return Command::SUCCESS;
    }


    private function normalizeCountyName(string $name): string
    {
        if (!$name)
            return '';

        // Fix Romanian diacritics
        $name = strtr($name, [
            'Ş' => 'Ș',
            'ş' => 'ș',
            'Ţ' => 'Ț',
            'ţ' => 'ț',
        ]);

        // Remove "Județul / Judeţul / Judetul"
        $name = preg_replace('/^Jude(ț|ţ|t)ul\s+/iu', '', $name);

        // Remove Municipiul (only București)
        $name = preg_replace('/^Municipiul\s+/iu', '', $name);



        return mb_convert_case(trim($name), MB_CASE_TITLE, "UTF-8");
    }

    private function normalizeLocalityName(string $name): string
    {
        if (!$name)
            return '';

        // Fix Romanian diacritics (sedilla → comma)
        $name = strtr($name, [
            'Ş' => 'Ș',
            'ş' => 'ș',
            'Ţ' => 'Ț',
            'ţ' => 'ț',
        ]);

        // Make everything lowercase first (important when CSV is all uppercase)
        $name = mb_strtolower($name, 'UTF-8');

        // Convert to Title Case
        $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');

        return ucfirst(trim($name));
    }


    private function toAscii(string $string): string
    {
        $string = strtr($string, [
            'Ă' => 'A',
            'Â' => 'A',
            'Î' => 'I',
            'Ș' => 'S',
            'Ț' => 'T',
            'ă' => 'a',
            'â' => 'a',
            'î' => 'i',
            'ș' => 's',
            'ț' => 't',
            // în caz că mai scapi ceva sedilă:
            'Ş' => 'S',
            'Ţ' => 'T',
            'ş' => 's',
            'ţ' => 't',
        ]);

        // Removes any other accents / normalize unicode
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

        return strtolower($string);
    }

    private function getCountyAbbr(string $name): string
    {
        $abbrs = [
            'Alba' => 'AB',
            'Arad' => 'AR',
            'Argeș' => 'AG',
            'Bacău' => 'BC',
            'Bihor' => 'BH',
            'Bistrița-Năsăud' => 'BN',
            'Botoșani' => 'BT',
            'Brașov' => 'BV',
            'Brăila' => 'BR',
            'Buzău' => 'BZ',
            'Caraș-Severin' => 'CS',
            'Călărași' => 'CL',
            'Cluj' => 'CJ',
            'Constanța' => 'CT',
            'Covasna' => 'CV',
            'Dâmbovița' => 'DB',
            'Dolj' => 'DJ',
            'Galați' => 'GL',
            'Giurgiu' => 'GR',
            'Gorj' => 'GJ',
            'Harghita' => 'HR',
            'Hunedoara' => 'HD',
            'Ialomița' => 'IL',
            'Iași' => 'IS',
            'Ilfov' => 'IF',
            'Maramureș' => 'MM',
            'Mehedinți' => 'MH',
            'Mureș' => 'MS',
            'Neamț' => 'NT',
            'Olt' => 'OT',
            'Prahova' => 'PH',
            'Satu Mare' => 'SM',
            'Sălaj' => 'SJ',
            'Sibiu' => 'SB',
            'Suceava' => 'SV',
            'Teleorman' => 'TR',
            'Timiș' => 'TM',
            'Tulcea' => 'TL',
            'Vaslui' => 'VS',
            'Vâlcea' => 'VL',
            'Vrancea' => 'VN',
            'București' => 'B',
        ];

        return $abbrs[$name] ?? '';
    }

    private function normalizePostalCode($postal)
    {
        if ($postal === null || $postal === '' || $postal == 0) {
            return null; // în SIRUTA multe localități nu au cod poștal
        }

        // elimină spații, tratează ca string
        $postal = trim((string) $postal);

        // păstrează DOAR cifre
        $postal = preg_replace('/\D/', '', $postal);

        // zero-padding la 6 cifre
        return str_pad($postal, 6, '0', STR_PAD_LEFT);
    }



}
