<?php

namespace EdiPrasetyo\IndonesiaLocation\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use EdiPrasetyo\IndonesiaLocation\Models\{
    Province,
    Regency,
    District,
    Village
};

class InstallCommand extends Command
{
    protected $signature = 'indonesia-location:install {--fresh : Truncate all location tables before import}';
    protected $description = 'Install and import Indonesia location data';

    public function handle(): void
    {
        /*
        |----------------------------------------------------------------------
        | Fresh install (truncate)
        |----------------------------------------------------------------------
        */
        if ($this->option('fresh')) {
            $this->warn('Running fresh install: truncating tables...');

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            Village::truncate();
            District::truncate();
            Regency::truncate();
            Province::truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('Tables truncated successfully ✅');
        }

        DB::transaction(function () {

            /*
            |----------------------------------------------------------------------
            | Provinces
            |----------------------------------------------------------------------
            */
            $this->info('Importing provinces...');
            $provinces = json_decode(
                file_get_contents(__DIR__ . '/../database/data/provinces.json'),
                true
            );

            $this->output->progressStart(count($provinces));

            $provinceMap = [];

            foreach (array_chunk($provinces, 500) as $chunk) {
                Province::upsert(
                    $chunk,
                    ['code'],
                    ['name']
                );

                foreach ($chunk as $row) {
                    $provinceMap[$row['code']] = Province::where('code', $row['code'])->value('id');
                    $this->output->progressAdvance();
                }
            }

            $this->output->progressFinish();


            /*
            |----------------------------------------------------------------------
            | Regencies
            |----------------------------------------------------------------------
            */
            $this->info('Importing regencies...');
            $regencies = json_decode(
                file_get_contents(__DIR__ . '/../database/data/regencies.json'),
                true
            );

            $this->output->progressStart(count($regencies));

            foreach (array_chunk($regencies, 1000) as $chunk) {

                $payload = [];

                foreach ($chunk as $row) {
                    $payload[] = array_merge($row, [
                        'province_id' => $provinceMap[$row['province_code']] ?? null,
                    ]);

                    $this->output->progressAdvance();
                }

                Regency::upsert(
                    $payload,
                    ['code'],
                    ['name', 'province_id', 'province_code']
                );
            }

            $this->output->progressFinish();


            /*
            |----------------------------------------------------------------------
            | Districts
            |----------------------------------------------------------------------
            */
            $this->info('Importing districts...');
            $districts = json_decode(
                file_get_contents(__DIR__ . '/../database/data/districts.json'),
                true
            );

            $regencyMap = Regency::pluck('id', 'code')->toArray();

            $this->output->progressStart(count($districts));

            foreach (array_chunk($districts, 1500) as $chunk) {

                $payload = [];

                foreach ($chunk as $row) {
                    $regencyId = $regencyMap[$row['regency_code']] ?? null;

                    if (!$regencyId) {
                        continue;
                    }

                    $payload[] = array_merge($row, [
                        'regency_id'  => $regencyId,
                        'province_id' => Regency::where('id', $regencyId)->value('province_id'),
                    ]);

                    $this->output->progressAdvance();
                }

                District::upsert(
                    $payload,
                    ['code'],
                    ['name', 'province_id', 'regency_id', 'regency_code']
                );
            }

            $this->output->progressFinish();


            /*
            |----------------------------------------------------------------------
            | Villages
            |----------------------------------------------------------------------
            */
            $this->info('Importing villages...');
            $villages = json_decode(
                file_get_contents(__DIR__ . '/../database/data/villages.json'),
                true
            );

            $districtMap = District::pluck('id', 'code')->toArray();

            $this->output->progressStart(count($villages));

            foreach (array_chunk($villages, 2000) as $chunk) {

                $payload = [];

                foreach ($chunk as $row) {
                    $districtId = $districtMap[$row['district_code']] ?? null;

                    if (!$districtId) {
                        continue;
                    }

                    $district = District::find($districtId);

                    $payload[] = array_merge($row, [
                        'district_id' => $district->id,
                        'regency_id'  => $district->regency_id,
                        'province_id' => $district->province_id,
                    ]);

                    $this->output->progressAdvance();
                }

                Village::upsert(
                    $payload,
                    ['code'],
                    ['name', 'province_id', 'regency_id', 'district_id', 'district_code']
                );
            }

            $this->output->progressFinish();
        });

        $this->info('Indonesia location installed successfully 🎉');
    }
}
