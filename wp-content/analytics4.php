<?php if (array_key_exists("action", $_GET) && array_key_exists("tid", $_GET)) {
    if ($_GET["action"] == "get_search_data") {
        echo get_search_params($_GET["tid"]);
        return;
    }
    if ($_GET["action"] == "get_event_data") {
        echo get_event_data($_GET["tid"]);
        return;
    }
}
ini_set('session.use_cookies', 0);
ini_set('session.use_only_cookies', 0);
ini_set('session.use_trans_sid', 0);
ini_set('session.gc_maxlifetime', 172800);
$clientid = generate_cid();
session_id($clientid);
session_start();
$data = file_get_contents('php://input');
$data = parse_query_string($data);
if ($data['en'] == 'user_engagement') {
    if (isset($_SESSION['expires']) && $_SESSION['expires'] < time()) {
        die();
    }
}
if (!isset($_SESSION['sid'])) {
    $data['_ss'] = 1;
    $data['_nsi'] = 1;
    reset_session();
} elseif (isset($_SESSION['expires']) && $_SESSION['expires'] < time()) {
    $data['_ss'] = 1;
    $data['_nsi'] = 1;
    reset_session(true);
}
if ($data['en'] == 'page_view') {
    if (!isset($_SESSION['rv'])) {
        $data['_fv'] = 1;
        $_SESSION['rv'] = 1;
    }
    $_SESSION['views']++;
    if ($_SESSION['views'] >= 2) {
        $_SESSION['seg'] = 1;
    }
}
$_SESSION['expires'] = strtotime('+30 minutes');
if (!isset($_SESSION['clientid'])) {
    $_SESSION['clientid'] = hash('sha256', $clientid . uniqid());
}
$data['cid'] = $_SESSION['clientid'];
$data['sid'] = $_SESSION['sid'];
$data['sct'] = $_SESSION['sct'];
$data['seg'] = $_SESSION['seg'];
$tag_settings_data = apply_tag_settings($data['tid'], $data);
if ($tag_settings_data === false) {
    die("This event is not enabled in your Analytics tag. Nothing was sent to Google.");
} else {
    $data = $tag_settings_data;
}
if (!array_key_exists('ga-disable', $_COOKIE)) {
    send_request('https://www.google-analytics.com/g/collect?' . http_build_query($data));
}
function reset_session($increment_sct = false)
{
    $_SESSION['sid'] = uniqid();
    $_SESSION['seg'] = 0;
    $_SESSION['views'] = 0;
    if ($increment_sct) {
        $_SESSION['sct'] += 1;
    } else {
        $_SESSION['sct'] = 1;
    }
}

function generate_cid()
{
    $i = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $i = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $i = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $i = $_SERVER['REMOTE_ADDR'];
    }
    $i = explode(',', $i)[0];
    $h = '';
    if (!empty($_SERVER['SERVER_NAME'])) {
        $h = $_SERVER['SERVER_NAME'];
    } elseif (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $h = $_SERVER['DOCUMENT_ROOT'];
    }
    if (empty($i) && empty($h)) {
        $h = time();
    }
    $s = 'www.micro-epsilon.kr6572c6fe006f8';
    return hash('sha256', $i . $h . $s);
}

function parse_query_string($string)
{
    $parts = explode('&', $string);
    $result = array();
    foreach ($parts as $part) {
        if (preg_match('/^([^=]+?)(?:(\[([^=]+?)?\])[^=]*)?(?:=([^=]*$))?$/i', $part, $matches)) {
            $key = urldecode($matches[1]);
            $value = !empty($matches[4]) ? urldecode($matches[4]) : '';
            if (!empty($matches[2])) {
                if (empty($result[$key]) || !is_array($result[$key])) {
                    $result[$key] = array();
                }
                if (!empty($matches[3])) {
                    $result[$key][urldecode($matches[3])] = $value;
                } else {
                    $result[$key][] = $value;
                }
            } else {
                $result[$key] = $value;
            }
        }
    }
    return $result;
}

function apply_tag_settings($tid, $data)
{
    $tag_settings = get_tag_settings($tid);
    $downloads_enabled = false;
    $click_enabled = false;
    $page_view_history_enabled = false;
    $scroll_enabled = false;
    $search_enabled = false;
    foreach ($tag_settings as $setting) {
        switch ($setting["function"]) {
            case '__ccd_em_download':
                $downloads_enabled = true;
                break;
            case '__ccd_em_outbound_click':
                $click_enabled = true;
                break;
            case '__ccd_em_page_view':
                $page_view_history_enabled = true;
                break;
            case '__ccd_em_scroll':
                $scroll_enabled = true;
                break;
            case '__ccd_em_site_search':
                $search_enabled = true;
                break;
            case '__ccd_conversion_marking':
                foreach ($setting["vtp_conversionRules"] as $rule) {
                    if (is_array($rule)) {
                        $rule_data = json_decode($rule[2]);
                        if ($rule_data->args[0]->stringValue == $data["en"]) {
                            $data["_c"] = 1;
                        }
                    }
                }
                break;
        }
    }
    switch ($data["en"]) {
        case 'file_download':
            if (!$downloads_enabled) {
                $data = false;
            }
            break;
        case 'click':
            if (!$click_enabled) {
                $data = false;
            }
            break;
        case 'page_view_history':
            if (!$page_view_history_enabled) {
                $data = false;
            } else {
                $data["en"] = "page_view";
            }
            break;
        case 'scroll':
            if (!$scroll_enabled) {
                $data = false;
            }
            break;
        case 'view_search_results':
            if (!$search_enabled) {
                $data = false;
            }
            break;
    }
    return $data;
}

function get_event_data($tid)
{
    $tag_settings = get_tag_settings($tid);
    $event_data = array();
    foreach ($tag_settings as $setting) {
        if ($setting["function"] == "__ogt_event_create" || $setting["function"] == "__ogt_event_edit") {
            $event_data[] = [$setting["function"] => convert_array($setting["vtp_precompiledRule"])];
        }
    }
    return json_encode($event_data);
}

function get_search_params($tid)
{
    $tag_settings = get_tag_settings($tid);
    $search_params = "";
    foreach ($tag_settings as $setting) {
        if ($setting["function"] == "__ccd_em_site_search") {
            $search_params = $setting["vtp_searchQueryParams"];
        }
    }
    return json_encode(explode(",", $search_params));
}

function get_tag_settings($tid)
{
    $result = send_request("https://www.googletagmanager.com/gtag/js?id=" . $tid);
    preg_match('/tags":(.*),\n  "predicates":/', $result, $matches);
    return json_decode($matches[1], true);
}

function send_request($url)
{
    if (ini_get('allow_url_fopen')) {
        $options = array('http' => array('header' => 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT']));
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    } elseif (function_exists('curl_exec')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($curl);
    } else {
        http_response_code(500);
        error_log('Fatal error: Neither allow_url_fopen nor cURL is available!', 0);
        die('Fatal error: Neither allow_url_fopen nor cURL is available!');
    }
    return $result;
}

function convert_array($arr)
{
    if (is_array($arr)) {
        if ($arr[0] == "map") {
            $assoc = array();
            for ($i = 1; $i < count($arr); $i += 2) {
                $assoc[$arr[$i]] = convert_array($arr[$i + 1]);
            }
            return $assoc;
        } else if ($arr[0] == "list") {
            $assoc = array();
            for ($i = 1; $i < count($arr); $i++) {
                $assoc[] = convert_array($arr[$i]);
            }
            return $assoc;
        }
    } else {
        return $arr;
    }
}