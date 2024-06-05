<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'service_create',
            ],
            [
                'id'    => 18,
                'title' => 'service_edit',
            ],
            [
                'id'    => 19,
                'title' => 'service_show',
            ],
            [
                'id'    => 20,
                'title' => 'service_delete',
            ],
            [
                'id'    => 21,
                'title' => 'service_access',
            ],
            [
                'id'    => 22,
                'title' => 'animal_create',
            ],
            [
                'id'    => 23,
                'title' => 'animal_edit',
            ],
            [
                'id'    => 24,
                'title' => 'animal_show',
            ],
            [
                'id'    => 25,
                'title' => 'animal_delete',
            ],
            [
                'id'    => 26,
                'title' => 'animal_access',
            ],
            [
                'id'    => 27,
                'title' => 'pet_create',
            ],
            [
                'id'    => 28,
                'title' => 'pet_edit',
            ],
            [
                'id'    => 29,
                'title' => 'pet_show',
            ],
            [
                'id'    => 30,
                'title' => 'pet_delete',
            ],
            [
                'id'    => 31,
                'title' => 'pet_access',
            ],
            [
                'id'    => 32,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 33,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 34,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 35,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 36,
                'title' => 'credit_create',
            ],
            [
                'id'    => 37,
                'title' => 'credit_edit',
            ],
            [
                'id'    => 38,
                'title' => 'credit_show',
            ],
            [
                'id'    => 39,
                'title' => 'credit_delete',
            ],
            [
                'id'    => 40,
                'title' => 'credit_access',
            ],
            [
                'id'    => 41,
                'title' => 'service_request_create',
            ],
            [
                'id'    => 42,
                'title' => 'service_request_edit',
            ],
            [
                'id'    => 43,
                'title' => 'service_request_show',
            ],
            [
                'id'    => 44,
                'title' => 'service_request_delete',
            ],
            [
                'id'    => 45,
                'title' => 'service_request_access',
            ],
            [
                'id'    => 46,
                'title' => 'booking_create',
            ],
            [
                'id'    => 47,
                'title' => 'booking_edit',
            ],
            [
                'id'    => 48,
                'title' => 'booking_show',
            ],
            [
                'id'    => 49,
                'title' => 'booking_delete',
            ],
            [
                'id'    => 50,
                'title' => 'booking_access',
            ],
            [
                'id'    => 51,
                'title' => 'review_create',
            ],
            [
                'id'    => 52,
                'title' => 'review_edit',
            ],
            [
                'id'    => 53,
                'title' => 'review_show',
            ],
            [
                'id'    => 54,
                'title' => 'review_delete',
            ],
            [
                'id'    => 55,
                'title' => 'review_access',
            ],
            [
                'id'    => 56,
                'title' => 'availability_create',
            ],
            [
                'id'    => 57,
                'title' => 'availability_edit',
            ],
            [
                'id'    => 58,
                'title' => 'availability_show',
            ],
            [
                'id'    => 59,
                'title' => 'availability_delete',
            ],
            [
                'id'    => 60,
                'title' => 'availability_access',
            ],
            [
                'id'    => 61,
                'title' => 'photo_update_create',
            ],
            [
                'id'    => 62,
                'title' => 'photo_update_edit',
            ],
            [
                'id'    => 63,
                'title' => 'photo_update_show',
            ],
            [
                'id'    => 64,
                'title' => 'photo_update_delete',
            ],
            [
                'id'    => 65,
                'title' => 'photo_update_access',
            ],
            [
                'id'    => 66,
                'title' => 'cashout_create',
            ],
            [
                'id'    => 67,
                'title' => 'cashout_edit',
            ],
            [
                'id'    => 68,
                'title' => 'cashout_show',
            ],
            [
                'id'    => 69,
                'title' => 'cashout_delete',
            ],
            [
                'id'    => 70,
                'title' => 'cashout_access',
            ],
            [
                'id'    => 71,
                'title' => 'pet_review_create',
            ],
            [
                'id'    => 72,
                'title' => 'pet_review_edit',
            ],
            [
                'id'    => 73,
                'title' => 'pet_review_show',
            ],
            [
                'id'    => 74,
                'title' => 'pet_review_delete',
            ],
            [
                'id'    => 75,
                'title' => 'pet_review_access',
            ],
            [
                'id'    => 76,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
