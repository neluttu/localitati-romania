<?php
declare(strict_types=1);
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\County;
use App\Models\Locality;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportLocalityCoordinates extends Command
{
    protected $signature = 'localities:import-coordinates {file}';
    protected $description = 'Import lat/lng for localities from CSV or XLSX';

    public function handle()
    {
        $file = $this->argument('file');

        $this->info("Loading file: $file");

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $header = array_map(fn($v) => strtolower(trim($v)), $rows[1]);

        $colLocality = array_search('localitate', $header);
        $colCounty = array_search('judet', $header);
        $colLat = array_search('lat', $header);
        $colLng = array_search('lng', $header);

        $bar = $this->output->createProgressBar(count($rows));

        $unmatched = [];

        foreach ($rows as $i => $row) {
            if ($i == 1) {
                $bar->advance();
                continue;
            }

            $loc = $this->normalizeLocalityName($row[$colLocality] ?? '');
            $locAscii = $this->toAscii($loc);

            $countyName = $this->normalizeLocalityName($row[$colCounty] ?? '');
            $countyAscii = $this->toAscii($countyName);

            $lat = $row[$colLat] ?? null;
            $lng = $row[$colLng] ?? null;

            // Identify county
            $county = County::where('name_ascii', $countyAscii)->first();

            if (!$county) {
                $unmatched[] = "Judet negasit: $countyName";
                $bar->advance();
                continue;
            }

            // Identify locality
            $locality = Locality::where('county_id', $county->id)
                ->where('name_ascii', $locAscii)
                ->first();

            if (!$locality) {
                $unmatched[] = "Localitate negasita: $loc, $countyName";
                $bar->advance();
                continue;
            }

            $locality->update([
                'lat' => $lat,
                'lng' => $lng
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("IMPORT FINALIZAT!");
        $this->warn("Localități fără match: " . count($unmatched));

        foreach ($unmatched as $line) {
            $this->line(" - $line");
        }

        return Command::SUCCESS;
    }

    private function normalizeLocalityName(string $name): string
    {
        $name = trim($name);

        // Fix Ş & Ţ
        $name = strtr($name, [
            'Ş' => 'Ș',
            'ş' => 'ș',
            'Ţ' => 'Ț',
            'ţ' => 'ț',
        ]);

        return $name;
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
            'Ş' => 'S',
            'ş' => 's',
            'Ţ' => 'T',
            'ţ' => 't',
        ]);

        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        return strtolower($string);
    }

}
