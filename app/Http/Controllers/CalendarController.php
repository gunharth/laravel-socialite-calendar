<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendar;
use App\Http\Requests;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('calendar');
    }

    /**
     * Get all Google events through json
     *
     * return json
     */
    public function events(GoogleCalendar $calendar, Request $request)
    {
        $timeMin = $request->input('start');
        $timeMax = $request->input('end');
        $items = $calendar->listEvents($timeMin, $timeMax);
        return json_encode($items);
    }

    public function store(GoogleCalendar $calendar)
    {
        //$calendarId = "p3ol3iomgjspr2nssld48jm9f8@group.calendar.google.com";
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => 'Guni Mitternacht bis mitternacht',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
                'dateTime' => '2016-03-28T00:00:00',
                'timeZone' => 'Europe/Vienna',
            ),
            'end' => array(
                'dateTime' => '2016-04-04T00:00:00',
                'timeZone' => 'Europe/Vienna',
            ) 
          /*,
          'recurrence' => array(
        'RRULE:FREQ=DAILY;COUNT=2'
        )*/
        ));

        $calendar->insertEvent($calendarId, $event);
//return 'Event created: %s\n' . $event->htmlLink;
    }
}
