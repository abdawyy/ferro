<?php

namespace Database\Seeders;

use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (ContactSetting::query()->exists()) {
            return;
        }

        ContactSetting::query()->create(ContactSetting::defaultAttributes());
    }
}
