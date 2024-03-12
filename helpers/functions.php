<?php
use Mvc\Core\View;
use Mvc\Core\Request;
use Mvc\Core\Response;

function view($file_path,$data = array())
{   
    $view = new View($file_path);
    $view->setData($data);
    $view->render();
}


function public_url($path = null)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . ROOT_DIR . '/';
    return $baseUrl . ($path ? $path : "");
}


function url($path)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . ROOT_DIR . "/";
    return $baseUrl . ($path === "/" ? "" : $path);
}



function redirect($path,array $data = [])
{
    if(!empty($data)){
        $_SESSION['flash_data'] = $data;
    }
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . ROOT_DIR . "/";
    $redirected_url = $baseUrl . ($path === "/" ? "" : $path);
    header("Location: {$redirected_url}");
    exit;
}

function redirect_to($url)
{
    header("Location: $url");
    exit;
}

function redirect_back()
{
    $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    if($previousPage){
        header('Location: ' . $previousPage);
        exit;
    }
}

function back_url()
{
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
}

function current_route()
{
    $currentUrl = $_SERVER['REQUEST_URI'];
    $prefix = ROOT_DIR . "/";
    if (substr($currentUrl, 0, strlen($prefix)) === $prefix) {
        $result = substr($currentUrl, strlen($prefix));
    } else {
        $result = $currentUrl; 
    }
    $route = $result;
    $route = rtrim($route, '/');
    return explode("?",$route)[0];
}

function current_url()
{
    $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $currentURL;
}

function request()
{
    return new Request;
}

function response()
{
    return new Response;
}


function slug($string)
{
    $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);

    $string = strtolower(str_replace(' ', '-', $string));

    $string = preg_replace('/-+/', '-', $string);

    return $string;
}

function array_print($data)
{
    if(is_array($data) || is_object($data)){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}


function delete_file($file_path)
{
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

function rename_file($old_name,$new_name)
{
    if(file_exists($old_name)){
        rename($old_name,$new_name);
    }
}

function remove_dir($dir)
{
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
                 RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }
    rmdir($dir);
}

function query($name)
{
    return isset($_GET[$name]) ? $_GET[$name] : null;
}

function get_query($key)
{
    return isset($_GET[$key]) ? $_GET[$key] : null;
}

function flash_session($key,$value = null){
    if($value){
        $_SESSION['flash_session'][$key] = $value;
        return;
    }
    return isset($_SESSION['flash_session']) && isset($_SESSION['flash_session'][$key]) ? $_SESSION['flash_session'][$key] : null;
}

function session($key,$value = null){
    if($value){
        $_SESSION[$key] = $value;
        return;
    }
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

function generateToken($length = 32)
{
    $randomBytes = random_bytes($length);
    $token = bin2hex($randomBytes);
    return $token;
}

function now()
{
    return (new DateTime())->format('Y-m-d H:i:s');
}


function getValidationErrors()
{
    if(isset($_SESSION['flash_session']) && isset($_SESSION['flash_session']['validation_errors'])){
        $errors = $_SESSION['flash_session']['validation_errors'];
        return $errors;
    }  
   return null;
}

function getOldInputs()
{
    if(isset($_SESSION['flash_session']) && isset($_SESSION['flash_session']['old_inputs'])){
        $old_inputs = $_SESSION['flash_session']['old_inputs'];
        return $old_inputs;
    }  
   return null;
}

function message(array $messages,$flush = true)
{
    if(!is_array($messages) || empty($messages)){
        return;
    }
    $_SESSION['flash_session']['messages'] = $messages;
    if($flush){
        $_SESSION['flash_session']['flush'] = true; 
    }
}

function getMessage()
{
    if(isset($_SESSION['flash_session']) && isset($_SESSION['flash_session']['messages'])){
        return $_SESSION['flash_session']['messages'];
    }
    return null;
}

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function getUser($guard = 'user')
{
    if($guard === 'user'){
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }else if($guard === 'admin'){
        return isset($_SESSION['admin']['user']) ? $_SESSION['admin']['user'] : null;
    }
    return null;
}

function clean_up_session()
{
    if(isset($_SESSION["flash_session"])){
        if(isset($_SESSION["flash_session"]["flush"])){
            unset($_SESSION['flash_session']);
        }else{
            $_SESSION['flash_session']['flush'] = true; 
        }
    }

}

function array_find(array $arr,callable $func)
{
    for($i=0; $i < count($arr); $i++)
    {
        if($func($arr[$i],$i))
        {
            return $arr[$i];
        }
    }
    return null;
}

function csrf_token()
{
    return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null;
}

function isMobileDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $mobileKeywords = array('Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone');
    foreach ($mobileKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

function pagination($totalPages,$currentPage,$maxPagesToShow = 5)
{
    $queryParameters = $_GET;
    unset($queryParameters['page']); // Remove existing 'page' parameter from query parameters
    
    $pagination = '<nav aria-label="Page navigation example">';
    $pagination .= '<ul class="pagination">';

    // Previous button
    if ($currentPage > 1) {
        $queryParameters['page'] = $currentPage - 1;
        $pagination .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParameters) . '">Previous</a></li>';
    }

    $halfMaxPagesToShow = floor($maxPagesToShow / 2);
    $startPage = max(1, $currentPage - $halfMaxPagesToShow);
    $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

    // Display ellipsis if necessary
    if ($startPage > 1) {
        $queryParameters['page'] = 1;
        $pagination .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParameters) . '">1</a></li>';
        if ($startPage > 2) {
            $pagination .= '<li class="page-item"><span>...</span></li>';
        }
    }

    // Page numbers
    for ($i = $startPage; $i <= $endPage; $i++) {
        $queryParameters['page'] = $i;
        $activeClass = ($i == $currentPage) ? 'active' : '';
        $pagination .= '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?' . http_build_query($queryParameters) . '">' . $i . '</a></li>';
    }

    // Display ellipsis if necessary
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $pagination .= '<li><span>...</span></li>';
        }
        $queryParameters['page'] = $totalPages;
        $pagination .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParameters) . '">' . $totalPages . '</a></li>';
    }

    // Next button
    if ($currentPage < $totalPages) {
        $queryParameters['page'] = $currentPage + 1;
        $pagination .= '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParameters) . '">Next</a></li>';
    }

    $pagination .= '</ul>';

    return $pagination;
}