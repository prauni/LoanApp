<?php
namespace App\Services;

class UHubService
{
    public function test(){
        return 'Rand Number : '.rand(99,9999).'<br>';
    }
}