<?php
namespace App\Services;

use Exception;
use Google\Client;
use App\Models\Project;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Http;
use Support\Exceptions\ApplicationException;

class GoogleCalendarService
{
    public static $accessToken;

    public function __construct(public Project $project) {}

    public function initialize()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/calendar.json'));
        $client->useApplicationDefaultCredentials();
        $client->setApplicationName("CAL");
        $scope = array();
        $scope[] = config('services.google_calendar.scope');
        $client->setSubject(config('services.google_calendar.subject'));
        $client->setScopes($scope);
        $service = new Calendar($client);
        return $service;
    }

    public function update()
    {
      
        $service = $this->initialize();
        try {
            if ($this->project->site_visit || $this->project->bid_due) {
                $this->addEvent($service, 'site_visit');
                $this->addEvent($service, 'bid_due');
            }
           
            $this->removeIfEmpty($service, 'site_visit');
            $this->removeIfEmpty($service, 'bid_due');
        } catch (\Exception $e) {
            throw new ApplicationException($e->getMessage());
        }
        try {
        } catch (\Exception $e) {
        }
    }

    public function addEvent(Calendar $service, $key = 'site_visit')
    {
        if (@$this->project->{$key} && strtotime($this->project->{$key})) {
            if ($this->deleteEventIfExists($service, $key) !== false) {
                $event = new Event([
                    'summary' => ($key == 'site_visit' ? 'Site Visit' : 'Bid Due') . '-' . $this->project->pn . ':' . $this->project->name .', ' . @$this->project->facility->location,
                    'description' => @$this->project->facility->name . '; ' . @$this->project->facility->location,
                    'start' => array(
                        'dateTime' => toTimezoneDate($this->project->{$key}, 'c', 'US/Eastern'),
                        'timezone' => 'US/Eastern',
                    ),
                    'end' => array(
                        'dateTime' =>  toTimezoneDate($this->project->{$key}, 'c', 'US/Eastern'),
                        'timezone' => 'US/Eastern',
                    ),
                    'colorId' => $key == 'site_visit' ? '1' : '7'
                ]);
                $event = $service->events->insert(config('services.google_calendar.calendar_id'), $event);
                $this->saveEvent($event, $key);
            }
        }
    }

    public function saveEvent($event, $key = 'site_visit')
    {
        $this->project->updateJsonField([
            $key => $event
        ], 'sync_data');

        $this->project = $this->project->fresh();
    }

    public function removeIfEmpty(Calendar $service, $key = 'site_visit')
    {
        if (!$this->project->{$key} && @$this->project->sync_data[$key]['id']) {
            $this->deleteEventIfExists($service, $key, true);
        }
    }

    public function deleteEventIfExists(Calendar $service, $key = 'site_visit', $force = false)
    {
        try {
            if ($id = @$this->project->sync_data[$key]['id']) {
                /* remove event only if date doesnot match. */
                if (!$force && date("Y-m-d", strtotime($this->project->{$key})) == date("Y-m-d", strtotime(@$this->project->sync_data[$key]['start']['dateTime']))) {
                    return false;
                }
                $service->events->delete(config('services.google_calendar.calendar_id'), $id);
                $this->saveEvent([], $key);
                return true;
            }
            return true;
        } catch (\Exception $e) {
            return true;
        }
    }
}
