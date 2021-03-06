<?php

namespace App\AppHelpers;

use App\AppHelpers\Mail\SendMail;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Modules\Setting\Model\Setting;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class Helper {

    /**
     * @param $path
     *
     * @return array
     */
    public static function get_directories($path) {
        $directories = [];
        $items       = scandir($path);
        foreach ($items as $item) {
            if ($item == '..' || $item == '.') {
                continue;
            }
            if (is_dir($path . '/' . $item)) {
                $directories[] = $item;
            }
        }

        return $directories;
    }

    /**
     * @return array
     */
    public static function config_menu_merge() {
        $modules    = self::get_directories(base_path('modules'));
        $activeMenu = [];
        foreach ($modules as $key => $value) {
            $urlPath = $value . '/Config/menu.php';
            if (file_exists(base_path('modules') . '/' . $urlPath)) {
                $activeMenu[] = require(base_path('modules') . '/' . $urlPath);
            }
        }
        $activeMenu = collect($activeMenu)->sortBy('sort')->toArray();

        return $activeMenu;
    }

    /**
     * @return array
     */
    public static function config_permission_merge() {
        $modules = self::get_directories(base_path('modules'));
        $files   = [];
        $i       = 0;
        foreach ($modules as $key => $value) {
            $urlPath = $value . '/Config/permission.php';
            $file    = base_path('modules') . '/' . $urlPath;
            if (file_exists($file)) {
                $files[(int)filemtime($file) + $i] = $file;
                $i++;
            }
        }
        ksort($files);
        $permissions = [];
        foreach ($files as $file) {
            $permissions[] = require($file);
        }

        return $permissions;
    }


    /**
     * @param array $array
     *
     * @return string
     */
    public static function getModal($array = []) {
        if (!empty($array)) {
            $class    = $array['class'] ?? null;
            $id       = $array['id'] ?? 'form-modal';
            $tabindex = $array['tabindex'] ?? '-1';
            $size     = $array['size'] ?? null;
            $title    = $array['title'] ?? 'Title';
            if ($tabindex !== false) {
                $html = '<div class="modal fade ' . $class . '" id="' . $id . '" tabindex="' . $tabindex
                        . '" role="dialog" aria-hidden="true">';
            }
            else {
                $html = '<div class="modal fade ' . $class . '" id="' . $id . '" role="dialog" aria-hidden="true">';
            }
            $html .= '<div class="modal-dialog ' . $size . ' ">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header"><h5>' . $title . '</h5></div>';
            $html .= '<div class="modal-body">';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="datetime-modal position-relative"></div>';
        }
        else {
            $html = '<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal" aria-hidden="true">';
            $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';
            $html .= '<div class="modal-header">';
            $html .= '<h5>Create</h5>';
            $html .= '</div>';
            $html .= '<div class="modal-body">';

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="datetime-modal position-relative"></div>';
        }
        return $html;
    }

    /**
     * @param $string
     * @param array $options
     *
     * @return bool|false|string|string[]|null
     */
    public static function slug($string, $options = []) {
        //B???n ????? chuy???n ng???
        $slugTransliterationMap = [
            '??' => 'a', '??' => 'a', '???' => 'a', '??' => 'a', '???' => 'a', '??' => 'a', '??' => 'a', '??' => 'A',
            '??' => 'A',
            '???' => 'A',
            '??' => 'A',
            '???' => 'A',
            '??' => 'A',
            '??' => 'A',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'a',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '???' => 'A',
            '??' => 'd',
            '??' => 'D',
            '??' => 'e',
            '??' => 'e',
            '???' => 'e',
            '???' => 'e',
            '???' => 'e',
            '??' => 'e',
            '??' => 'E',
            '??' => 'E',
            '???' => 'E',
            '???' => 'E',
            '???' => 'E',
            '??' => 'E',
            '???' => 'e',
            '???' => 'e',
            '???' => 'e',
            '???' => 'e',
            '???' => 'e',
            '???' => 'E',
            '???' => 'E',
            '???' => 'E',
            '???' => 'E',
            '???' => 'E',
            '??' => 'i',
            '??' => 'i',
            '???' => 'i',
            '??' => 'i',
            '???' => 'i',
            '??' => 'I',
            '??' => 'I',
            '???' => 'I',
            '??' => 'I',
            '???' => 'I',
            '??' => 'o',
            '??' => 'o',
            '???' => 'o',
            '??' => 'o',
            '???' => 'o',
            '??' => 'o',
            '??' => 'o',
            '??' => 'O',
            '??' => 'O',
            '???' => 'O',
            '??' => 'O',
            '???' => 'O',
            '??' => 'O',
            '??' => 'O',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'o',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '???' => 'O',
            '??' => 'u',
            '??' => 'u',
            '???' => 'u',
            '??' => 'u',
            '???' => 'u',
            '??' => 'u',
            '??' => 'U',
            '??' => 'U',
            '???' => 'U',
            '??' => 'U',
            '???' => 'U',
            '??' => 'U',
            '???' => 'u',
            '???' => 'u',
            '???' => 'u',
            '???' => 'u',
            '???' => 'u',
            '???' => 'U',
            '???' => 'U',
            '???' => 'U',
            '???' => 'U',
            '???' => 'U',
            '??' => 'y',
            '???' => 'y',
            '???' => 'y',
            '???' => 'y',
            '???' => 'y',
            '??' => 'Y',
            '???' => 'Y',
            '???' => 'Y',
            '???' => 'Y',
            '???' => 'Y'
        ];

        //Gh??p c??i ?????t do ng?????i d??ng y??u c???u v???i c??i ?????t m???c ?????nh c???a h??m
        $options = array_merge([
            'delimiter'     => '-',
            'transliterate' => true,
            'replacements'  => [],
            'lowercase'     => true,
            'encoding'      => 'UTF-8'
        ], $options);

        //Chuy???n ng??? c??c k?? t??? theo b???n ????? chuy???n ng???
        if ($options['transliterate']) {
            $string = str_replace(array_keys($slugTransliterationMap), $slugTransliterationMap, $string);
        }

        //N???u c?? b???n ????? chuy???n ng??? do ng?????i d??ng cung c???p th?? th???c hi???n chuy???n ng???
        if (is_array($options['replacements']) && !empty($options['replacements'])) {
            $string = str_replace(array_keys($options['replacements']), $options['replacements'], $string);
        }

        //Thay th??? c??c k?? t??? kh??ng ph???i k?? t??? latin
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $string);

        //Ch??? gi??? l???i m???t k?? t??? ph??n c??ch gi???a 2 t???
        $string = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1',
            trim($string, $options['delimiter']));

        //Chuy???n sang ch??? th?????ng n???u c?? y??u c???u
        if ($options['lowercase']) {
            $string = mb_strtolower($string, $options['encoding']);
        }

        //Tr??? k???t qu???
        return $string;
    }

    /**
     * @param $index
     * @return mixed
     */
    public static function segment($index) {
        return request()->segments()[$index] ?? '/';
    }

    /**
     * @return mixed
     */
    public static function getRoutePrevious() {
        return app('router')->getRoutes(url()->previous())->match(app('request')->create(url()->previous()))->getName();
    }

    /**
     * @param $mail_to
     * @param $subject
     * @param $title
     * @param $body
     * @param null $template
     * @return bool
     */
    public static function sendMail($mail_to, $subject, $title, $body, $template = null) {
        /** Send email */
        if (empty($template)) {
            $template = 'Base::mail.send_test_mail';
        }
        $mail = new SendMail;
        $mail->to($mail_to)->subject($subject)->title($title)->body($body)->view($template);

        try {
            Mail::send($mail);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $string
     * @param false $associative
     * @return false|mixed
     */
    public static function isJson($string, $associative = false) {
        try {
            $string = json_decode($string, $associative);
            return $string;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $key
     * @return null
     */
    public static function getSetting($key) {
        $data = Setting::where('key', $key)->first();
        return !empty($data) ? $data->value : null;
    }

    /**
     * @param $data
     * @return bool
     * @throws ApiErrorException
     * @throws GuzzleException
     * @throws PusherException
     */
    public static function dataPusher($data) {
        $request = new Request;
        $options = [
            'cluster' => 'ap1', 'encrypted' => true
        ];
        try{
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), $options);
        }catch(PusherException $e){
            $request->session()->flash('error', $e->getMessage());
        }
        $pusher->trigger('NotificationEvent', 'send-message', $data);

        return true;
    }

    /**
     * Response api
     *
     * @param $request
     */
    public static function apiResponseByLanguage($request){
        $locale = $request->header('lang') ?? "en";
        App::setLocale($locale);
    }
}
