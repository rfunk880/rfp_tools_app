<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Support\Helpers\Factory;
use Support\Helpers\Nav;

/**
 * @param $string
 * @return string
 */
function encryptIt($string)
{
    return urlencode(Crypt::encrypt($string));
}

/**
 * @param $encrypted
 * @return string
 */
function decryptIt($encrypted)
{
    return Crypt::decrypt(urldecode($encrypted));
}


function basicEncrypt($string)
{
    return urlencode(base64_encode($string));
}

function basicDecrypt($encrypted)
{
    return base64_decode(urldecode($encrypted));
}

/**
 * @param $route
 * @param array $params
 * @return string
 */
if (!function_exists('sysRoute')) :
    function sysRoute($route, $params = array())
    {
        return route('webpanel.' . $route, $params);
    }
endif;

/**
 * @param $url
 * @return string
 */
if (!function_exists('sysUrl')) :
    function sysUrl($url, $params = array())
    {
        return url('webpanel/' . $url, $params);
    }
endif;

/**
 * @param $view
 * @param array $data
 * @return \Illuminate\View\View
 */
if (!function_exists('sysView')) :
    function sysView($view, $data = array())
    {
        return view('webpanel.' . $view, $data);
    }
endif;


if (!function_exists('frontView')) :
    function frontView($view, $data = array())
    {
        return view('frontend.' . $view, $data);
    }
endif;


if (!function_exists('frontUrl')) :
    function frontUrl($urL)
    {
        return url('frontpanel/' . $urL);
    }
endif;

if (!function_exists('frontRoute')) :
    function frontRoute($route, $data = [])
    {
        return route('frontpanel.' . $route, $data);
    }
endif;


if (!function_exists('isAction')) :
    function isAction($action)
    {
        return \Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\\' . $action;
    }
endif;


if (!function_exists('isMenuActive')) :
    function isMenuActive($segments)
    {
        return Nav::isActiveMultiple(is_array($segments) ? $segments : [$segments]);
    }
endif;

if (!function_exists('pd')) :
    function pd($var)
    {
        print_r($var);
        die();
    }
endif;


if (!function_exists('ed')) :
    function ed($var)
    {
        print_r($var);
        die();
    }
endif;


if (!function_exists('extractPaginationInfo')) :
    function extractPaginationInfo(\Illuminate\Pagination\LengthAwarePaginator $pagination)
    {
        return [
            'total' => $pagination->total(),
            'per_page' => min($pagination->total(), $pagination->perPage()),
            'current_page' => $pagination->currentPage(),
            'last_page' => $pagination->lastPage(),
            'links' => [
                'first_url' => $pagination->url(1),
                'last_url' => $pagination->url($pagination->lastPage()),
                'next_url' => $pagination->nextPageUrl(),
                'previous_url' => $pagination->previousPageUrl(),
                'path_url' => url('api/v1')
            ],
            'from' => $pagination->firstItem(),
            'to' => $pagination->lastItem(),
        ];
    }
endif;

if (!function_exists('authUser')) :

    function authUser()
    {
        return auth()->user();
    }

endif;

function isImpersonating()
{
    return authUser() && session('ADMIN_IMPERSONATING');
}


function onlyIf($bool, $message = 'INVALID ACCESS', $exception = true)
{
    if ($bool) {
        return true;
    }

    if (!$exception) {
        return false;
    }

    abort(500, $message);
}

function cssString($ar)
{
    $css = [];
    foreach ($ar as $k => $v) {
        $css[] = $k . ' : ' . $v;
    }

    return implode(";", $css);
}

function dbColToTitle($title)
{
    return ucfirst(str_replace(["_", "-"], ' ', $title));
}

function prefixPagination()
{
    return '&_=_';
}


function custom_array_combine($headers, $values)
{
    $arr = [];
    $values = array_values($values);
    foreach ($headers as $k => $header) {
        $arr[$header] = @$values[$k];
    }

    return $arr;
}



function getValidDbColumns($cols = [])
{
    foreach ($cols as $k => $col) {
        $cols[$k] = strtolower(str_replace([" ", "-"], '_', trim($col)));
    }

    return $cols;
}

function getValidDbValues($cols = [])
{
    foreach ($cols as $k => $col) {
        $cols[$k] = trim($col);
    }

    return $cols;
}

function array_combine_($keys, $values)
{
    $result = array();
    foreach ($keys as $i => $k) {
        @$result[$k] = @$values[$i];
    }
    return $result;
}

function size_recursive($path)
{
    $size = 0;
    if (is_dir($path)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            $size += $file->getSize();
        }
    } else {
        $size = filesize($path);
    }

    if ($size / 1048576 > 1) {
        return round($size / 1048576, 1) . ' MB';
    } elseif ($size / 1024 > 1) {
        return round($size / 1024, 1) . ' KB';
    } else {
        return round($size, 1) . ' bytes';
    }

    return $size;
}



function byteFormat($bytes, $unit = "", $decimals = 0)
{
    $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3);
    $value = 0;
    if ($bytes > 0) {
        if (!array_key_exists($unit, $units)) {
            $pow = floor(log($bytes) / log(1024));
            $unit = array_search($pow, $units);
        }
        $value = ($bytes / pow(1024, floor($units[$unit])));
    }
    if (!is_numeric($decimals) || $decimals < 0) {
        $decimals = 2;
    }
    return sprintf('%.' . $decimals . 'f ' . $unit, $value);
}


function recursive_directory_size($directory, $format = FALSE)
{
    $size = 0;

    // if the path has a slash at the end we remove it here
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }

    // if the path is not valid or is not a directory ...
    if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) {
        // ... we return -1 and exit the function
        return -1;
    }
    // we open the directory
    if ($handle = opendir($directory)) {
        // and scan through the items inside
        while (($file = readdir($handle)) !== false) {
            // we build the new path
            $path = $directory . '/' . $file;

            // if the filepointer is not the current directory
            // or the parent directory
            if ($file != '.' && $file != '..') {
                // if the new path is a file
                if (is_file($path)) {
                    // we add the filesize to the total size
                    $size += filesize($path);

                    // if the new path is a directory
                } elseif (is_dir($path)) {
                    // we call this function with the new path
                    $handlesize = recursive_directory_size($path);

                    // if the function returns more than zero
                    if ($handlesize >= 0) {
                        // we add the result to the total size
                        $size += $handlesize;

                        // else we return -1 and exit the function
                    } else {
                        return -1;
                    }
                }
            }
        }
        // close the directory
        closedir($handle);
    }
    // if the format is set to human readable
    if ($format == TRUE) {
        // if the total size is bigger than 1 MB
        if ($size / 1048576 > 1) {
            return round($size / 1048576, 1) . ' MB';

            // if the total size is bigger than 1 KB
        } elseif ($size / 1024 > 1) {
            return round($size / 1024, 1) . ' KB';

            // else return the filesize in bytes
        } else {
            return round($size, 1) . ' bytes';
        }
    } else {
        // return the total filesize in bytes
        return $size;
    }
}

function admin_asset_url($url)
{
    return \Storage::disk('admin')->url($url);
}

function auth_user()
{

    if (auth()->user()) {
        return auth()->user();
    }

    /* if (auth('admin') && auth('admin')->user()) {
        return auth('admin')->user();
    } */


    null;
}

function query_string($except = [], $prepend = '?')
{
    return $prepend . http_build_query(request()->except($except));
}


function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 7; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

if (!function_exists('jsVar')) :

    function jsVar($data)
    {
        echo '<script type="text/javascript">
	    /* <![CDATA[ */';
        foreach ($data as $k => $v) {
            echo 'window.' . $k . '= ' . json_encode($v) . ';';
        }
        echo '/* ]]> */
	    </script>';
    }
endif;

function u_get_chart_colors($index = 0)
{
    $colors = [
        'orange' =>  'rgb(255, 81, 24)',
        'blue' => 'rgb(42, 121, 184)',
        'yellow' => 'rgb(255, 205, 86)',
        'green' =>  'rgb(75, 192, 192)',
        'red'   =>  'rgb(255, 99, 132)',
        'purple' => 'rgb(153, 102, 255)',
        'grey' => 'rgb(201, 203, 207)'
    ];
    $colors = array_values($colors);
    if (!isset($colors[$index])) {
        $index = abs($index);
        $c = count($colors);
        $index = $index - ((int) abs($index / $c) * $c);
    }
    return $colors[$index];
}

function u_get_pn_filters()
{
    return [
        'size'        => __('Size', 'size'),
        'voltage'     => __('Voltage', 'size'),
        'tolerance'   => __('Tolerance', 'size'),
        'dielectric'  => __('Dielectric', 'size'),
        'capacitance' => __('Capacitance', 'size')
    ];
}

function sanitizeArray($ar)
{
    return collect($ar)->filter(function ($val) {
        return $val;
    })->toArray();
}

if (!function_exists('ajaxSuccess')) :
    function ajaxSuccess($data = [])
    {
        $response = array_merge($data, ['success' => 1]);
        return response()->json($response);
    }
endif;
if (!function_exists('authId')) :
    function authId()
    {
        return auth()->id();
    }
endif;
if (!function_exists('money')) :
    function money($amount)
    {
        return '$' . number_format($amount, 2);
    }
endif;

if (!function_exists('fromMemory')) {
    /* Add value in memory throught out the request response cycle, similar to cache but in memory 
    for each request.
    eg: fromMemory('setting_forms', function(){
        return SettingForm::all();
    });
    
    */
    function fromMemory()
    {
        $args = func_get_args();
        if (count($args) < 2) {
            throw new \Support\Exceptions\ApplicationException("Invalid");
        }
        $item = $args[0];
        $callable = $args[1];
        $finalArgs = array_slice($args, 2);
        if (!isset(Factory::$MEMORY_ITEMS[$item])) {
            Factory::$MEMORY_ITEMS[$item] = call_user_func_array($callable, (is_array($finalArgs) ? $finalArgs : []));
        }

        // print_r(Factory::$MEMORY_ITEMS);

        return Factory::$MEMORY_ITEMS[$item];
    }
}

function stringToDecimal($number)
{
    $number = (float) str_replace([",", "$", " ", "%", '(', ')', '-'], '', trim($number));
    if (!is_numeric($number)) {
        return 0;
    }
    if ($number == '' || $number == null) {
        $number = 0;
    }
    return $number;
}

function sanitizeRequestNumbers($ar)
{
    foreach ($ar as $key => $val) {
        $ar[$key] = stringToDecimal($val);
    }
    return $ar;
}

function onlyNumbersInString($str)
{
    return preg_replace("/[^0-9.]/", "", $str);
}


function mergeIfMatches($mergeAr, $source)
{
    foreach ($mergeAr as $k => $v) {
        if (isset($source[$k])) {
            $mergeAr[$k] = $source[$k];
        }
    }

    return $mergeAr;
}

function nest_dir($ref, $dirs)
{
    $dirs = array_filter($dirs);
    foreach ($dirs as $index => $dir) {
        $parent = @$dirs[$index - 1];
        if ($parent && isset($ref[$parent])) {
            $ref[$parent][$dir] = nest_dir([], array_slice($dirs, $index + 1));
            continue;
        }
        if (!$parent || ($parent && array_search($parent, $dirs) === 0))
            $ref[$dir] = [];
    }
    return $ref;
}

function decompress($file_name)
{
    // $file_name = '3768c08f-69c1-435a-aab0-4d08fda19b32.csv.gz';

    $buffer_size = 4096; // read 4kb at a time
    $out_file_name = str_replace('.gz', '', $file_name);

    $file = gzopen($file_name, 'rb');
    $out_file = fopen($out_file_name, 'wb');

    while (!gzeof($file)) {
        fwrite($out_file, gzread($file, $buffer_size));
    }
    fclose($out_file);
    gzclose($file);
}

function isMimeImage($mime)
{
    return strpos($mime, 'image') !== false;
}

function dummyImagePath()
{
    return url('images/avatar/1.png');
}

function initialLetters($sentence)
{
    $words = explode(" ", $sentence);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= mb_substr($w, 0, 1);
    }
    return $acronym;
}


function getQueryParamsFromUrl($url)
{
    $parts = parse_url($url);

    if (!$parts) {
        return null;
    }
    parse_str($parts['query'], $query);

    return $query;
}


function formatPhoneNumber($number)
{
    return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $number);
}

if (!function_exists('isViewer')):
    function isViewer()
    {
        return authUser() && authUser()->isViewer();
    }
endif;
if (!function_exists('isAdmin')):
    function isAdmin()
    {
        return authUser() && authUser()->isAdmin();
    }
endif;
if (!function_exists('isSuperAdmin')):
    function isSuperAdmin()
    {
        return authUser() && authUser()->isSuperAdmin();
    }
endif;
if (!function_exists('isEstimator')):
    function isEstimator()
    {
        return authUser() && authUser()->isEstimator();
    }
endif;
if (!function_exists('isSalesPerson')):
    function isSalesPerson()
    {
        return authUser() && authUser()->isSalesPerson();
    }
endif;
if (!function_exists('isManagement')):
    function isManagement()
    {
        return isAdmin() || isSuperAdmin();
    }
endif;
if (!function_exists('canAdd')):
    function canAdd()
    {
        return isAdmin() || isSuperAdmin() || isEstimator() || isSalesPerson();
    }
endif;
if (!function_exists('canEdit')):
    function canEdit()
    {
        return canAdd();
    }
endif;
if (!function_exists('canDelete')):
    function canDelete()
    {
        return isAdmin() || isSuperAdmin();
    }
endif;
if (!function_exists('canBulkDelete')):
    function canBulkDelete()
    {
        return isSuperAdmin();
    }
endif;
