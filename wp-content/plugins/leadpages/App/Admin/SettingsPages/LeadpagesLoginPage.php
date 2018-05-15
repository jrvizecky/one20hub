<?php


namespace LeadpagesWP\Admin\SettingsPages;

use TheLoop\Contracts\SettingsPage;
use Twig_Environment;
use Twig_Loader_Filesystem;

class LeadpagesLoginPage implements SettingsPage
{
	public $twig;

	public function __construct()
	{
		$loader = new Twig_Loader_Filesystem(__DIR__ . '/../../templates');
		$this->twig = new Twig_Environment($loader, []);
	}

    public static function getName()
    {
        return get_called_class();
    }

    public function definePage()
    {
        global $leadpagesConfig;
        add_menu_page('leadpages', 'Leadpages', 'manage_options', 'Leadpages', array($this, 'displayCallback'), 'none');
    }


    public function displayCallback()
    {
        if (isset($_GET['code'])) {
            $code = sanitize_text_field($_GET['code']);
            echo '<div class="notice notice-error is-dismissible"><p>Login Failed Error Code: ' . esc_html($code) . '</p></div>';
        }
        global $leadpagesConfig;
        $html = $this->loginPageHtml();

        echo $html;
    }

    public function registerPage()
    {
        add_action('admin_menu', [$this, 'definePage']);
    }

    public function loginPageHtml()
    {
		echo $this->twig->render('login.html', []);
    }

}
