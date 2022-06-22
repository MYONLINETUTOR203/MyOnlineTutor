<?php

class CustomRouter
{

    static function setRoute(&$controller, &$action, &$params)
    {
        if (
                in_array($controller, SeoUrl::staticControllers()) ||
                !defined('SYSTEM_FRONT') || FatUtility::isAjaxCall()
        ) {
            return;
        }
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $uriQuery = urldecode($requestUri['query'] ?? '');
        $uriPath = urldecode($requestUri['path'] ?? '');
        $uriPath = array_values(array_filter(explode("/", $uriPath)));
        $langCodes = Language::getCodes();
        $langCode = strtolower($uriPath[0] ?? '');
        $langId = $_COOKIE['CONF_SITE_LANGUAGE'] ?? 1;
        $urlLangId = array_search($langCode, $langCodes);

        if (CONF_LANGCODE_URL == AppConstant::YES) {
            if ($urlLangId !== false) {
                $langId = $urlLangId;
                array_shift($uriPath);
                define('CONF_SITE_LANGUAGE', $urlLangId);
                if (CONF_DEFAULT_LANG == $langId) {
                    MyUtility::setSiteLanguage(Language::getData($langId), true);
                    $params = array_slice($uriPath, 2);
                    $controller = $uriPath[0] ?? '';
                    $action = $uriPath[1] ?? '';
                    if (strtolower($controller) == 'dashboard') {
                        array_shift($uriPath);
                        $params = array_slice($uriPath, 2);
                        $controller = $uriPath[0] ?? '';
                        $action = $uriPath[1] ?? '';
                    }
                    $redirect = FatUtility::generateUrl($controller, $action, $params);
                    $redirect = empty($uriQuery) ? $redirect : $redirect . '?' . $uriQuery;
                    header("Location:" . $redirect, true, 301);
                    header("Connection: close");
                    exit;
                }
            } elseif (CONF_DEFAULT_LANG != $langId) {
                $params = array_slice($uriPath, 2);
                $controller = $uriPath[0] ?? '';
                $action = $uriPath[1] ?? '';
                if (strtolower($controller) == 'dashboard') {
                    array_shift($uriPath);
                    $params = array_slice($uriPath, 2);
                    $controller = $uriPath[0] ?? '';
                    $action = $uriPath[1] ?? '';
                }
                $langCode = '/' . $langCodes[$langId];
                $redirect = $langCode . FatUtility::generateUrl($controller, $action, $params);
                $redirect = empty($uriQuery) ? $redirect : $redirect . '?' . $uriQuery;
                header("Location:" . $redirect, true, 301);
                header("Connection: close");
                exit;
            }
        } else {
            if ($urlLangId !== false) {
                array_shift($uriPath);
                $params = array_slice($uriPath, 2);
                $controller = $uriPath[0] ?? '';
                $action = $uriPath[1] ?? '';
                if (strtolower($controller) == 'dashboard') {
                    array_shift($uriPath);
                    $params = array_slice($uriPath, 2);
                    $controller = $uriPath[0] ?? '';
                    $action = $uriPath[1] ?? '';
                }
                $redirect = FatUtility::generateUrl($controller, $action, $params);
                $redirect = empty($uriQuery) ? $redirect : $redirect . '?' . $uriQuery;
                header("Location:" . $redirect, true, 301);
                header("Connection: close");
                exit;
            }
        }
        $url = SeoUrl::getCustomUrl($langId, implode("/", $uriPath));
        if (!empty($url)) {
            $langCode = CONF_LANGCODE_URL ? '/' . $langCodes[$langId] : '';
            $uriPath = explode("/", $url['seourl_custom']);
            $params = array_slice($uriPath, 2);
            $controller = $uriPath[0] ?? '';
            $action = $uriPath[1] ?? '';
            if (strtolower($controller) == 'dashboard') {
                array_shift($uriPath);
                $params = array_slice($uriPath, 2);
                $controller = $uriPath[0] ?? '';
                $action = $uriPath[1] ?? '';
            }
            $redirect = $langCode . FatUtility::generateUrl($controller, $action, $params);
            $redirect = empty($uriQuery) ? $redirect : $redirect . '?' . $uriQuery;
            header("Location:" . $redirect, true, intval($url['seourl_httpcode']));
            header("Connection: close");
            exit;
        }

        $url = SeoUrl::getOriginalUrl(implode("/", $uriPath));
        if (!empty($url)) {
            if (!defined('CONF_SITE_LANGUAGE')) {
                define('CONF_SITE_LANGUAGE', $url['seourl_lang_id']);
            }
            $uriPath = explode("/", $url['seourl_original']);
            $uriPath = array_values(array_filter($uriPath));
            $params = array_slice($uriPath, 2);
            $controller = $uriPath[0] ?? 'Home';
            $action = $uriPath[1] ?? 'index';
            if (strtolower($controller) == 'dashboard') {
                array_shift($uriPath);
                $params = array_slice($uriPath, 2);
                $controller = $uriPath[0] ?? 'Home';
                $action = $uriPath[1] ?? 'index';
            }
            return;
        }

        if (CONF_LANGCODE_URL == AppConstant::YES) {
            $params = array_slice($uriPath, 2);
            $controller = $uriPath[0] ?? 'Home';
            $action = $uriPath[1] ?? 'index';
            if (strtolower($controller) == 'dashboard') {
                array_shift($uriPath);
                $params = array_slice($uriPath, 2);
                $controller = $uriPath[0] ?? 'Home';
                $action = $uriPath[1] ?? 'index';
            }
        } else {
            $controller = empty($controller) ? 'Home' : $controller;
            $action = empty($action) ? 'index' : $action;
            if (strtolower($controller) == 'dashboard') {
                $controller = empty($action) ? 'Home' : $action;
                $action = $params[0] ?? 'index';
            }
        }
    }

}
