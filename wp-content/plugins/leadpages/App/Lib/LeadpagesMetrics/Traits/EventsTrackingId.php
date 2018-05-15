<?php


namespace LeadpagesMetrics\Traits;

trait EventsTrackingId
{

    public $eventsTrackingId;

    /**
     * Generate and Store UUID for Tracking Purposes
     */
    public function generateEventsTrackingId()
    {
        //generate tracking id
        $this->eventsTrackingId = uniqid();

        //store tracking id
        $this->storeTrackingIdWordPress();
    }


    public function storeTrackingIdWordPress()
    {
        update_option('lp-events-tracking-id', $this->eventsTrackingId);
    }

    public function getEventsTrackingId()
    {
        if (!$this->eventsTrackingId = get_option('lp-events-tracking-id')) {
            $this->generateEventsTrackingId();
        }
        return $this->eventsTrackingId;
    }

}