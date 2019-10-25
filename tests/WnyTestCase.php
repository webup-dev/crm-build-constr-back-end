<?php

namespace App;

use App\TestCase;
use Illuminate\Support\Facades\Schema;

abstract class WnyTestCase extends TestCase
{
    public function tearDown() : void
    {
        Schema::dropIfExists('books');
        Schema::dropIfExists('method_roles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('methods');
        Schema::dropIfExists('controllers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('users');
    }
}
