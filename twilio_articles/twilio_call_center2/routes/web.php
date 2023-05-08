<?php

use Illuminate\Support\Facades\Route;
use Twilio\TwiML\VoiceResponse;
use Twilio\Rest\Client;
use Twilio\Twiml;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/call-forward', function () {
    $token = csrf_token();
    $response = new VoiceResponse();
    $dial = $response->dial();
    $dial->queue('support');

    echo $response;
});

Route::any('/call', function () {
    try {
        $response = new VoiceResponse();
        $response->say('hello');
        $response->enqueue('support', ['url' => 'about_to_connect.xml']);

        //notify agent of call

        // make the call to your agent
        $client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));

        $call = $client->calls->create(
            +2348063146940, //agent line
            +16206440753, //twilio line
            ['url' => 'https://26bd-102-91-4-161.ngrok-free.app/call-forward'] //
        );

        return response($response)->header('Content-Type', 'text/xml');
    } catch (TwimlException $e) {
        return $e->getCode();
    }
});
