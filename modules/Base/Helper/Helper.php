<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Modules\Base\Model\Status;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stichoza\GoogleTranslate\GoogleTranslate;

if (!function_exists('gg_trans')) {
    /**
     * @param $string
     * @return string|null
     * @throws ErrorException
     */
    function gg_trans($string, $locale = null): ?string{
        if (!empty($locale)) {
            $tr = new GoogleTranslate($locale);
            return $tr->translate($string);
        }
        $target = (App::getLocale() === 'cn') ? 'zh-TW' : App::getLocale();
        if (!empty($target) && $target !== 'en') {
            $tr = new GoogleTranslate($target);
            return $tr->translate($string);
        }

        return $string;
    }
}
if (!function_exists('formatDate')) {
    /**
     * @param $timestamp
     * @param null $format
     * @return string|null
     */
    function formatDate($timestamp, $format = null): ?string{
        if (!isTimestamp($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        if (!empty($format)) {
            return Carbon::createFromTimestamp($timestamp)->format($format);
        }
        return Carbon::createFromTimestamp($timestamp)->format("d-m-Y");
    }
}

if (!function_exists('generateQRCode')) {
    /**
     * @param $data
     * @param null $format
     * @return string|null
     */
    function generateQRCode($data, $format = 'svg'): ?string{
        return QrCode::format($format)->generate($data);
    }
}

if (!function_exists('calculateTimeNotification')) {
    /**
     * @param $data
     * @param null $format
     * @return string|null
     */
    function calculateTimeNotification($data){
        $time            = time() - strtotime($data); // to get the time since that moment
        $time            = ($time < 1) ? 1 : $time;
        $tokens          = array(
            31536000 => 'year', 2592000 => 'month', 604800 => 'week', 86400 => 'day',
            3600     => 'hour', 60 => 'minute', 1 => 'second'
        );
        $number_of_units = "";
        foreach($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $number_of_units = floor($time / $unit);
            break;
        }
        return $number_of_units . ' ' . $text . (($number_of_units > 1) ? 's' : '');
    }
}

if (!function_exists('summaryListing')) {
    /**
     * @param $data
     * @return string|null
     */
    function summaryListing($data): ?string{
        $html = '';
        $html .= '<span class="listing-information">';
        $html .= trans('Showing');
        $html .= '<b> ';
        $html .= (count($data) > 0) ? ($data->currentpage() - 1) * $data->perpage() + 1 : 0;
        $html .= '</b> ';
        $html .= trans(' to ');
        $html .= '<b> ';
        $html .= ($data->currentpage() - 1) * $data->perpage() + $data->count();
        $html .= ' </b>';
        $html .= trans(' of ');
        $html .= '<b>' . $data->total() . '</b> ' . trans('entries') . '</span>';

        return $html;
    }
}

if (!function_exists('notificationList')) {
    /**
     * @param $url
     * @param $notifications
     * @param false $is_new
     * @return string
     */
    function notificationList($notifications, $is_new = FALSE): string{
        if ($is_new) {
            $notifications = array_slice($notifications, 0, 3);
        } else {
            $notifications = array_slice($notifications, 3);
        }
        $html = '';
        foreach($notifications as $notification) {
            $data = $notification['data'];
            if ($data['status'] == Status::STATUS_ACTIVE) {
                $url = route("read_notification", $notification['id']);
                /** Notify content */
                $new = empty($notification['read_at']) ? 'new' : '';

                /** Notify item */
                $html .= '<li class="notification-item ' . $new . '">
                            <a href="' . $url . '" class="media">
                                <div class="mr-3 img-notification rounded-circle bg-info p-2">
                                    <img class="w-100"
                                         src="https://cdn1.iconfinder.com/data/icons/youtuber/256/bell-notifications-notice-notify-alert-512.png">
                                </div>
                                <div class="media-body">
                                    <div class="notification-info">
                                        <b class="text-dark">' . $data["member"] . '</b><span>' .
                         trans(" will be at the store in a few minutes.") . '</span>
                                    </div>
                                    <small class="timestamp">' . trans("About ") .
                         calculateTimeNotification($data['time_show']) . trans(" ago.") . '</small>
                                </div>
                            </a>
                        </li>';
            }
        }


        return $html;
    }
}

if (!function_exists('isTimestamp')) {
    /**
     * @param $date
     * @return bool
     */
    function isTimestamp($date){
        try {
            return ((int)$date === $date)
                   && ($date <= PHP_INT_MAX)
                   && ($date >= ~PHP_INT_MAX);
        } catch(Exception $e) {
            return $date;
        }
    }
}


if (!function_exists('moneyFormat')) {
    /**
     * @param $number
     * @param bool $has_unit
     * @return string
     */
    function moneyFormat($number, $has_unit = true){
        $unit = "HK$";
        if (is_numeric($number)) {
            return ($has_unit) ? $unit . number_format($number) : number_format($number);
        }

        return "N/A";
    }
}

if (!function_exists('utf8_word_count')) {
    function utf8_word_count($string, $mode = 0){
        static $it = NULL;

        if (is_null($it)) {
            $it = IntlBreakIterator::createWordInstance(ini_get('intl.default_locale'));
        }

        $l = 0;
        $it->setText($string);
        $ret = $mode == 0 ? 0 : array();
        if (IntlBreakIterator::DONE != ($u = $it->first())) {
            do {
                if (IntlBreakIterator::WORD_NONE != $it->getRuleStatus()) {
                    $mode == 0 ? ++$ret : $ret[] = substr($string, $l, $u - $l);
                }
                $l = $u;
            } while(IntlBreakIterator::DONE != ($u = $it->next()));
        }

        return $ret;
    }
}
