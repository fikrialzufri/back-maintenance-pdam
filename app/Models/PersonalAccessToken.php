<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use UsesUuid;
    public $incrementing = true;

    // protected $primaryKey = "id";
    protected $keyType = "string";

    public function tokenable()
    {
        return $this->morphTo('tokenable', "tokenable_type", "tokenable_uuid");
    }
}
