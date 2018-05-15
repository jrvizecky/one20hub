<?php


namespace LeadpagesMetrics;


class WordPressEventEmail
{

    public static function storeEventEmail($email)
    {
        update_option('lp-event-email', $email);
    }

    public static function getEventEmail()
    {
        return get_option('lp-event-email');
    }

}