<?php

namespace App\Http\Livewire;

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

use Livewire\Component;

class TwiliVideoLivewire extends Component
{

    public string $token;

    public function mount()
    {
        $this->generate_token();
    }

    public function generate_token()
    {
        // Required for all Twilio access tokens
        // To set up environmental variables, see http://twil.io/secure
        $twilioAccountSid = getenv('TWILIO_ACCOUNT_SID');
        $twilioApiKey = getenv('TWILIO_API_KEY');
        $twilioApiSecret = getenv('TWILIO_API_KEY_SECRET');

        // Required for Video grant
        $roomName = 'cool room';
        // An identifier for your app - can be anything you'd like
        $identity = 'alloyking1';

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            6600,
            $identity
        );

        // Create Video grant
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);

        // Add grant to token
        $token->addGrant($videoGrant);

        // render token to string
        return $this->token =  $token->toJWT();
    }

    public function render()
    {
        return view('livewire.twili-video-livewire');
    }
}
