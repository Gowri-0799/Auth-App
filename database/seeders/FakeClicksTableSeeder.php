<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class FakeClicksTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $channels = [
            'fake_clicks_paid_search',
            'fake_clicks_organic',
            'fake_clicks_direct',
            'fake_clicks_pmax',
        ];

        $partner = DB::table('partners')
        ->select('zohocust_id as id')
        ->where('company_name', 'Test org1')
        ->first();

        if ($partner) {
            $partnersAffiliateIds = DB::table('partner_affiliates')
                ->where('partner_id', $partner->id)
                ->pluck('id')
                ->toArray();

            $startDate = Carbon::createFromDate(2024, 1, 1);
            $endDate = Carbon::createFromDate(2025, 1, 20);

            while ($startDate->lte($endDate)) {

                $recordCount = rand(10, 40);
 
                for ($i = 0; $i < $recordCount; $i++) {

                    $partners_affiliates_id = $partnersAffiliateIds[array_rand($partnersAffiliateIds)];

                    $channel = $channels[array_rand($channels)];

                    $randomTime = Carbon::createFromFormat('H:i:s', sprintf('%02d:%02d:%02d', rand(0, 23), rand(0, 59), rand(0, 59)));

                    $randomUrl = 'https://www.highspeedinternet.com/fake-clicks/' . $faker->slug;

                    $encodedUrl = urlencode($randomUrl);

                    DB::table('clicks')->insert([
                        'click_id' => $faker->unique()->uuid,
                        'click_source' => 'tune',
                        'click_ts' => $startDate->copy()->setTime($randomTime->hour, $randomTime->minute, $randomTime->second)->format('Y-m-d H:i:s'),
                        'partners_affiliates_id' => $partners_affiliates_id,
                        'zip' => $faker->postcode,
                        'state' => $faker->state,
                        'city' => $faker->city,
                        'intended_zip' => $faker->postcode,
                        'intended_state' => $faker->state,
                        'intended_city' => $faker->city,
                        'channel' => $channel,
                        'affiliate_source_url' => $encodedUrl, 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $startDate->addDay();
            }
        } else {
            echo "Partner 'Socxo Local' not found.";
        }
    }
}
