<?php

namespace App\Services;

use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Http;
use Support\Exceptions\ApplicationException;

class CalendarService
{
    public static $accessToken;

    public function __construct(public Project $project)
    {
    }

    public function update()
    {
        // dd([
        //     "Location" => [
        //         "DisplayName" => @$this->project->facility->name,
        //         "Address" => @$this->project->facility->location,
        //     ]
        //     ]);

        if ($this->project->site_visit && $this->project->bid_due) {

            if (strtotime($this->project->site_visit) > strtotime($this->project->bid_due)) {
                return;
            }

            try {

                self::GetAccessToken();
                $eventId = $this->getEventId();

                if ($eventId) {
                    $this->updateLocation($eventId);
                }
            } catch (\Exception $e) {
                throw new ApplicationException($e->getMessage());
            }
        }
    }


    public function updateLocation($eventId)
    {


        $params = [
            "Location" => [
                "DisplayName" => @$this->project->facility->name . '; ' . @$this->project->facility->location,
                // "Address" => @$this->project->facility->location,
            ],
            "start" => [
                "dateTime" => date("Y-m-d", strtotime($this->project->site_visit)) . 'T' . date("H:i:00.0000000", strtotime($this->project->site_visit)),
                "timeZone" => "UTC"
            ],
            "end" => [
                "dateTime" => date("Y-m-d", strtotime($this->project->bid_due)) . 'T' . date("H:i:00.0000000", strtotime($this->project->bid_due)),
                "timeZone" => "UTC"
            ],
        ];

        // dd($params);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$accessToken,
        ])
            ->acceptJson()
            ->patch("https://graph.microsoft.com/v1.0/users/api@swfunk.com/calendar/events/{$eventId}", $params);

        $result = $response->json();
        // dd($result);
        if (@$result['error']) {
            throw new Exception("Cannot update location");
        }

        if (@$result['id']) {
            $this->project->updateJsonField([
                'event' => $result
            ], 'sync_data');
        }
    }


    public function getEventId()
    {
        // dd( [
        //     'startDateTime' => now()->subMonths(2)->toIso8601String(),
        //     'endDateTime' => now()->addMonths(1)->toIso8601String(),
        //     '$filter' => "startswith(subject,'Site Visit - Project Test Project ({$this->project->pn})') or startswith(subject,'Bid Due - Project Test Project ({$this->project->pn})')"
        // ]);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . self::$accessToken,
        ])
            ->acceptJson()
            ->get("https://graph.microsoft.com/v1.0/users/api@swfunk.com/calendarView", [
                'startDateTime' => now()->subMonths(2)->toIso8601String(),
                'endDateTime' => now()->addMonths(1)->toIso8601String(),
                '$filter' => "startswith(subject,'Site Visit - Project Test Project ({$this->project->pn})') or startswith(subject,'Bid Due - Project Test Project ({$this->project->pn})')"
            ]);

        $result = $response->json();
        // dd($result);

        if (@$result['error']) {
            throw new Exception("Cannot get events");
        }
        if (isset($result['value']) && is_array($result['value'])) {
            return @$result['value'][0]['id'];
        }


        return null;
    }



    public static function GetAccessToken()
    {

        $tenantId = config('services.microsoft.tenant_id');
        $response = Http::asForm()
            ->acceptJson()
            ->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                'client_id' => config('services.microsoft.client_id'),
                'client_secret' => config('services.microsoft.client_secret'),
                'scope' => config('services.microsoft.scope'),
                'grant_type' => 'client_credentials'
            ]);

        $result = $response->json();
        // dd($result);

        if (@$result['token_type'] == 'Bearer') {
            self::$accessToken = $result['access_token'];
            return $result['access_token'];
        }

        throw new Exception("Failed to get access token ");
    }
}
