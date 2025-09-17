<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    protected function authenticate($user)
{
    $token = JWTAuth::fromUser($user);
    return $this->withHeader('Authorization', "Bearer $token");
}
}