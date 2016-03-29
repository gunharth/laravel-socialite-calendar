<?php 

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class GoogleCalendar
{

    protected $client;

    protected $service;

    protected $calendarId;

    public function __construct()
    {
        /* Get config variables */
        //$client_id = Config::get('google.client_id');
        //$service_account_name = Config::get('google.service_account_name');
        //$key_file_location = base_path() . Config::get('google.key_file_location');
        $this->calendarId = env('GOOGLE_CALENDAR_ID', '');

        // init google api
        $this->client = new \Google_Client();
        $this->client->setAuthConfigFile(storage_path() . '/keys/client_secrets.json');
        $this->client->setScopes('https://www.googleapis.com/auth/calendar',
                'https://www.googleapis.com/auth/plus.me',
                'https://www.googleapis.com/auth/plus.login',
                'https://www.googleapis.com/auth/plus.profile.emails.read');
        $this->client->setRedirectUri('http://localhost:8000/social/handle/google');
        $access_token = session('access_token');

        $this->client->setAccessToken(json_encode($access_token));
        $this->client->setApplicationName("Your Application Name");
        $this->service = new \Google_Service_Calendar($this->client);
    }

    
    /**
     * Carbon helper function 
     * convert fullcalendar date into iso string for Google calendar
     * MOVE TO HELPER
     */
    public function convertDateToISO($date)
    {
        $carbon = new Carbon($date, 'Europe/Vienna');
        $date = $carbon->toIso8601String();
        return $date;
    }

    /**
     * Get all events for a certain start & end date
     * return new array optimised for fullcalendar
     */
    public function listEvents($timeMin, $timeMax)
    {
        $timeMin = $this->convertDateToISO($timeMin);
        $timeMax = $this->convertDateToISO($timeMax);
        $optParams = array(
          'maxResults' => 999,
          'orderBy' => 'startTime',
          'timeMin' => $timeMin,
          'timeMax' => $timeMax,
          'singleEvents' => true
        );
        $allevents = $this->service->events->listEvents($this->calendarId);
        $events = $allevents->items;
        foreach ($events as $event) {
            $items[] = array(
                'id' => $event->id,
                'title' => $event->summary,
                'start' => $event->start->dateTime,
                'end' => $event->end->dateTime,
                'description' => $event->description
            );
        }
        return $items;
    }


    public function insertEvent($event)
    {
        $events = $this->service->events->insert($this->calendarId, $event);
        return $events;
    }
}
