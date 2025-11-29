<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ImportOldUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = database_path('migrations/old_users.csv');
        
        if (!File::exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        // First, get all countries and create a mapping
        $countries = DB::table('countries')->get()->keyBy('code');
        
        // Get default country ID (UAE)
        $defaultCountryId = $countries['971']->id ?? null;
        
        if (!$defaultCountryId) {
            $this->command->error("UAE country not found in countries table! Please run CountriesSeeder first.");
            return;
        }
        
        // Create country name to country ID mapping
        $countryMapping = $this->createCountryMapping($countries, $defaultCountryId);

        $handle = fopen($csvFile, 'r');
        if ($handle === false) {
            $this->command->error("Could not open CSV file");
            return;
        }

        // Read header
        $header = fgetcsv($handle);
        // Clean header: remove BOM, quotes, backticks and trim
        $header = array_map(function($col) {
            // Remove BOM (UTF-8 BOM is \xEF\xBB\xBF)
            $col = preg_replace('/^\xEF\xBB\xBF/', '', $col);
            // Remove quotes and backticks
            $col = trim($col, ' "\'`');
            return $col;
        }, $header);
        
        // Debug: show header if needed
        // $this->command->info("CSV Header: " . implode(', ', $header));
        
        // Find column indices (after cleaning, columns should be without backticks)
        $idIndex = $this->findColumnIndex($header, ['ID']);
        $emailIndex = $this->findColumnIndex($header, ['user_email']);
        $countryIndex = $this->findColumnIndex($header, ['country']);
        $phoneIndex = $this->findColumnIndex($header, ['phone']);
        $displayNameIndex = $this->findColumnIndex($header, ['display_name']);

        if ($idIndex === false || $emailIndex === false || $phoneIndex === false || $displayNameIndex === false) {
            $this->command->error("Required columns not found in CSV");
            $this->command->error("Found columns: " . implode(', ', $header));
            fclose($handle);
            return;
        }

        $now = Carbon::now();
        $users = [];
        $skipped = 0;
        $processed = 0;
        $batchEmails = []; // Track emails in current batch
        $batchPhones = []; // Track phones in current batch

        $this->command->info("Starting import...");
        $this->command->info("Default country (UAE) ID: {$defaultCountryId}");

        // Read and process rows
        while (($row = fgetcsv($handle)) !== false) {
            $email = trim($row[$emailIndex] ?? '');
            $phone = trim($row[$phoneIndex] ?? '');
            $displayName = trim($row[$displayNameIndex] ?? '');
            $countryValue = trim($row[$countryIndex] ?? '');

            // Skip if essential data is missing
            if (empty($email) || empty($phone) || empty($displayName)) {
                $skipped++;
                continue;
            }

            // Normalize email and phone for comparison
            $emailLower = strtolower($email);

            // Check if user already exists in database (by email or phone)
            $exists = DB::table('users')
                ->where('email', $email)
                ->orWhere('phone', $phone)
                ->exists();

            // Check if duplicate in current batch
            if ($exists || isset($batchEmails[$emailLower]) || isset($batchPhones[$phone])) {
                $skipped++;
                continue;
            }

            // Map country value to country ID
            $countryId = $this->mapCountryToId($countryValue, $countryMapping, $defaultCountryId);

            $users[] = [
                'full_name' => $displayName,
                'email' => $email,
                'phone' => $phone,
                'country_id' => $countryId,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Track in batch
            $batchEmails[$emailLower] = true;
            $batchPhones[$phone] = true;

            $processed++;

            // Insert in chunks of 100
            if (count($users) >= 100) {
                try {
                    DB::table('users')->insert($users);
                    $this->command->info("Inserted 100 users... (Total processed: {$processed})");
                } catch (\Illuminate\Database\QueryException $e) {
                    // If duplicate error, try inserting one by one
                    if ($e->getCode() == 23000) {
                        $this->command->warn("Duplicate detected in batch, inserting individually...");
                        foreach ($users as $user) {
                            try {
                                DB::table('users')->insert($user);
                            } catch (\Illuminate\Database\QueryException $e2) {
                                if ($e2->getCode() == 23000) {
                                    $skipped++;
                                } else {
                                    throw $e2;
                                }
                            }
                        }
                    } else {
                        throw $e;
                    }
                }
                $users = [];
                $batchEmails = [];
                $batchPhones = [];
            }
        }

        fclose($handle);

        // Insert remaining users
        if (!empty($users)) {
            try {
                DB::table('users')->insert($users);
            } catch (\Illuminate\Database\QueryException $e) {
                // If duplicate error, try inserting one by one
                if ($e->getCode() == 23000) {
                    foreach ($users as $user) {
                        try {
                            DB::table('users')->insert($user);
                        } catch (\Illuminate\Database\QueryException $e2) {
                            if ($e2->getCode() == 23000) {
                                $skipped++;
                            } else {
                                throw $e2;
                            }
                        }
                    }
                } else {
                    throw $e;
                }
            }
        }

        $this->command->info("\nImport completed!");
        $this->command->info("Processed: {$processed} users");
        $this->command->info("Skipped: {$skipped} users");
    }

    /**
     * Create a mapping of country names/variations to country IDs
     */
    private function createCountryMapping($countries, $defaultCountryId): array
    {
        $mapping = [];

        // UAE variations
        $uaeId = $countries['971']->id ?? null;
        if ($uaeId) {
            $mapping['united arab emirates'] = $uaeId;
            $mapping['الإمارات العربية المتحدة'] = $uaeId;
            $mapping['uae'] = $uaeId;
            $mapping['دولة الأمارات العربية المتحدة'] = $uaeId;
            $mapping['دولة الامارات العربية المتحدة'] = $uaeId;
        }

        // Saudi Arabia variations
        $saudiId = $countries['966']->id ?? null;
        if ($saudiId) {
            $mapping['saudi arabia'] = $saudiId;
            $mapping['السعودية'] = $saudiId;
            $mapping['السعوديه'] = $saudiId;
            $mapping['المملكة العربية السعودية'] = $saudiId;
        }

        // Kuwait variations
        $kuwaitId = $countries['965']->id ?? null;
        if ($kuwaitId) {
            $mapping['kuwait'] = $kuwaitId;
            $mapping['الكويت'] = $kuwaitId;
        }

        // Qatar
        $qatarId = $countries['974']->id ?? null;
        if ($qatarId) {
            $mapping['qatar'] = $qatarId;
        }

        // Oman variations
        $omanId = $countries['968']->id ?? null;
        if ($omanId) {
            $mapping['oman'] = $omanId;
            $mapping['عمان'] = $omanId;
        }

        // Bahrain
        $bahrainId = $countries['973']->id ?? null;
        if ($bahrainId) {
            $mapping['bahrain'] = $bahrainId;
        }

        // Other countries
        $mapping['united kingdom'] = $countries['44']->id ?? null;
        $mapping['جمهورية أيرلندا'] = $countries['353']->id ?? null;
        $mapping['morocco'] = $countries['212']->id ?? null;
        $mapping['المغرب'] = $countries['212']->id ?? null;
        $mapping['مصر'] = $countries['20']->id ?? null;
        $mapping['canada'] = $countries['1']->id ?? null;
        $mapping['india'] = $countries['91']->id ?? null;
        $mapping['الجزائر'] = $countries['213']->id ?? null;
        $mapping['algeria'] = $countries['213']->id ?? null;
        $mapping['اليمن'] = $countries['967']->id ?? null;
        $mapping['الاردن'] = $countries['962']->id ?? null;
        $mapping['jordan'] = $countries['962']->id ?? null;
        $mapping['العراق'] = $countries['964']->id ?? null;
        $mapping['سوريا'] = $countries['963']->id ?? null;
        $mapping['لبنان'] = $countries['961']->id ?? null;
        $mapping['تونس'] = $countries['216']->id ?? null;
        $mapping['france'] = $countries['33']->id ?? null;
        $mapping['belgium'] = $countries['32']->id ?? null;
        $mapping['venezuela'] = $countries['58']->id ?? null;
        $mapping['palestinian territory'] = $countries['970']->id ?? null;
        $mapping['libyan arab jamahiriya'] = $countries['218']->id ?? null;
        $mapping['libya'] = $countries['218']->id ?? null;
        $mapping['hajar rabih'] = $defaultCountryId; // Unknown location, default to UAE
        $mapping['cayman islands'] = $countries['1']->id ?? null; // Uses +1 like US/Canada
        $mapping['united states minor outlying islands'] = $countries['1']->id ?? null;

        return $mapping;
    }

    /**
     * Map country value from CSV to country ID
     */
    private function mapCountryToId(string $countryValue, array $countryMapping, int $defaultCountryId): int
    {
        // If empty, return default (UAE)
        if (empty($countryValue) || strtolower($countryValue) === 'null') {
            return $defaultCountryId;
        }

        // Check if it's a phone number (starts with digits)
        if (preg_match('/^\d+/', $countryValue)) {
            return $defaultCountryId;
        }

        // Normalize the country value
        $normalized = strtolower(trim($countryValue));
        
        // Remove invisible characters
        $normalized = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $normalized);
        $normalized = trim($normalized);

        // Try exact match first
        if (isset($countryMapping[$normalized])) {
            return $countryMapping[$normalized];
        }

        // Try partial match for Arabic text
        foreach ($countryMapping as $key => $id) {
            if (mb_strpos($normalized, $key) !== false || mb_strpos($key, $normalized) !== false) {
                return $id;
            }
        }

        // Default to UAE if no match found
        return $defaultCountryId;
    }

    /**
     * Find column index by trying multiple possible column names
     */
    private function findColumnIndex(array $header, array $possibleNames): int|false
    {
        foreach ($possibleNames as $name) {
            $index = array_search($name, $header);
            if ($index !== false) {
                return $index;
            }
        }
        return false;
    }
}

