<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function statusContent() {
        $user = new User();
        $content = $user->followings->pluck('id')->toArray();
        print_r($content);
    }
}
