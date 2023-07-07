<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffImportBulkTemporary extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id","staff_no", "role", "department", "designation", "first_name", "last_name", "fathers_name", "mothers_name", "date_of_birth", "date_of_joining", "email", "gender_id", "mobile", "emergency_mobile",
        "marital_status", "current_address", "permanent_address", "qualification", "experience", "epf_no",
        "basic_salary", "contract_type", "location", "bank_account_name", "bank_account_no", "bank_name", "bank_branch", "facebook_url", "twitter_url", "instagram_url", "driving_license",
    ];
}
