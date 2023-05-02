<?php

namespace Database\Seeders;

use App\Enum\UserAbilityEnum;
use App\Enum\UserStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users_length=50;
        for( $i=$users_length;$i>0;$i--){
            $user_status=collect(UserStatusEnum::cases())->random();

            if ($user_status->canAny([
                UserAbilityEnum::CARD_OWNERSHIP,
                UserAbilityEnum::COUPON_OWNERSHIP])) {
                $academic = \App\Models\Academic::factory()->create([
                    'status' => $user_status->value
                ]);
                if ($user_status->can(UserAbilityEnum::CARD_OWNERSHIP))
                    \App\Models\CardApplicant::factory()->for($academic)->create();
                if ($user_status->can(UserAbilityEnum::COUPON_OWNERSHIP))
                    \App\Models\CouponOwner::factory()->for($academic)->create();
            } elseif ($user_status->can(UserAbilityEnum::COUPON_SELL))
                \App\Models\CouponStaff::factory()->create([
                    'status' => $user_status->value
                ]);
            elseif ($user_status->can(UserAbilityEnum::CARD_APPLICATION_CHECK))
                \App\Models\CardApplicationStaff::factory()->create([
                    'status' => $user_status->value
                ]);
            elseif ($user_status->can(UserAbilityEnum::ENTRY_CHECK))
                \App\Models\EntryStaff::factory()->create([
                    'status' => $user_status->value
                ]);
        };
        $this->call([
            TransferCouponSeeder::class,
            PurchaseCouponSeeder::class,
            UsageCardSeeder::class,
            UsageCouponSeeder::class,
            CardApplicantSeeder::class,
        ]);
    }
}
