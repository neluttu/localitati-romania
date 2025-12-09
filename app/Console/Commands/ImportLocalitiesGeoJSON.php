<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Locality;

class ImportLocalitiesGeoJSON extends Command
{
    protected $signature = 'localities:import-geojson {file}';
    protected $description = 'Import lat/lng from GeoJSON using natCode = siruta_code';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return;
        }

        $this->info("Loading GEOJSON...");
        $json = json_decode(file_get_contents($file), true);

        if (!isset($json['features'])) {
            $this->error("Invalid GeoJSON file.");
            return;
        }

        $count = 0;

        foreach ($json['features'] as $feature) {

            $props = $feature['properties'];
            $geom = $feature['geometry'];

            if (!$geom || !isset($geom['coordinates']))
                continue;

            $siruta = (int) $props['natCode'];
            $lng = $geom['coordinates'][0];
            $lat = $geom['coordinates'][1];

            $updated = Locality::where('siruta_code', $siruta)->update([
                'lat' => $lat,
                'lng' => $lng,
            ]);

            if ($updated)
                $count++;
        }

        $this->info("DONE! Updated coordinates for $count localities.");
    }
}
