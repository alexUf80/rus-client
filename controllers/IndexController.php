<?php

class IndexController extends Controller
{
    public $modules_dir = 'controllers/';

    public function __construct()
    {
        parent::__construct();
    }

    function fetch()
    {

        // Страницы
        $pages = $this->pages->get_pages(array('visible' => 1));
        $this->design->assign('pages', $pages);

        // Текущий модуль (для отображения центрального блока)
        $module = $this->request->get('module', 'string');
        $module = preg_replace("/[^A-Za-z0-9]+/", "", $module);


        if (class_exists($module)) {
            $this->main = new $module($this);
        } else {
            return false;
        }

        if (!$content = $this->main->fetch()) {
            return false;
        }

        $this->design->assign('content', $content);
        $this->design->assign('module', $module);

        $utm_source = $this->request->get('utm_source');
        $cookie_inspiration = 60 * 60 * 24 * 30;
        $webmaster_id = $this->request->get('wmid');
        $click_hash   = $this->request->get('clickid');

        if (!isset($_COOKIE['wm_id']))
            setcookie("wm_id", $webmaster_id, time() + $cookie_inspiration, '/', $this->config->main_domain);

        if (!isset($_COOKIE['clickid']))
            setcookie("clickid", $click_hash, time() + $cookie_inspiration, '/', $this->config->main_domain);

        if (!isset($_COOKIE['utm_source']))
            setcookie("utm_source", trim($utm_source), time() + $cookie_inspiration, '/', $this->config->main_domain);

        $wrapper = $this->design->get_var('wrapper');
        if (false && isset($this->user->contract->status) && $this->user->contract->status != 3 && $this->user->contract->sold) // отключаю цессия
        {
            if (empty($this->user->contract->premier)) {
                if ($this->config->root_url != $this->config->cession_url) {
                    $redirect_token = md5(rand() . microtime());
                    $this->users->update_user($_SESSION['user_id'], array('redirect_token' => $redirect_token));

                    setcookie('redirect_token', $redirect_token, time() + 30, '/', '.ecozaym24.ru');
                    setcookie('user_id', $_SESSION['user_id'], time() + 30, '/', '.ecozaym24.ru');

                    if (!empty($_SESSION['looker']))
                        setcookie('looker', 1, time() + 30, '/', '.ecozaym24.ru');

                    header('Location: ' . $this->config->cession_url . $this->request->url());
                    exit;
                } else {
                    $wrapper = 'cession/index.tpl';
                }
            } else {

                if ($this->config->root_url != $this->config->premier_url) {
                    $redirect_token = md5(rand() . microtime());
                    $this->users->update_user($_SESSION['user_id'], array('redirect_token' => $redirect_token));

                    setcookie('redirect_token', $redirect_token, time() + 30, '/', '.ecozaym24.ru');
                    setcookie('user_id', $_SESSION['user_id'], time() + 30, '/', '.ecozaym24.ru');

                    if (!empty($_SESSION['looker']))
                        setcookie('looker', 1, time() + 30, '/', '.ecozaym24.ru');

                    header('Location: ' . $this->config->premier_url . $this->request->url());
                    exit;
                } else {
                    $wrapper = 'cession/premier_index.tpl';
                }
            }
        } elseif (is_null($wrapper)) {
            if ($module != 'C2oPaymentController' && $this->config->root_url != $this->config->front_url) {
                $redirect_token = md5(rand() . microtime());
                $this->users->update_user($_SESSION['user_id'], array('redirect_token' => $redirect_token));

                setcookie('redirect_token', $redirect_token, time() + 30, '/', '.ecozaym24.ru');
                setcookie('user_id', $_SESSION['user_id'], time() + 30, '/', '.ecozaym24.ru');

                if (!empty($_SESSION['looker']))
                    setcookie('looker', 1, time() + 30, '/', '.ecozaym24.ru');

                header('Location: ' . $this->config->front_url . $this->request->url());
                exit;
            } else {
                $wrapper = 'index.tpl';
            }

        }


        if (!empty($wrapper))
            return $this->body = $this->design->fetch($wrapper);
        else
            return $this->body = $content;

    }
}
