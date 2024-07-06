<?php
class TrcSettingsPage
{
  private $options;
  private $logger;

  public function __construct()
  {
    add_action('admin_menu', [$this, 'add_plugin_page']);
    add_action('admin_init', [$this, 'page_init']);

    $this->logger = new ErrorLogLogger();
  }

  public function add_plugin_page()
  {
    add_options_page('Настройки TRecent Posts', 'Настройки TRecent Posts', 'manage_options', 'trc-setting-admin', [$this, 'create_admin_page']);
  }

  public function create_admin_page()
  {
    $this->options = get_option('trecent_posts'); ?>
        <div class="wrap">
            <h1>Настройки TRecent Posts</h1>
            <form method="post" action="options.php">
            <?php
            settings_fields('trecent_posts_group');
            do_settings_sections('trc-setting-admin');
            submit_button();?>
            </form>
        </div>
        <?php
  }

  public function page_init()
  {
    register_setting(
      'trecent_posts_group', // Option group
      'trecent_posts', // Option name
      [$this, 'sanitize'] // Sanitize
    );

    add_settings_section(
      'setting_section1', // ID
      'Настройки шорткода', // Title
      null, // Callback
      'trc-setting-admin'
      // Page
    );

    add_settings_field(
      'posts_limit', // ID
      'Количество записей', // Title
      [$this, 'posts_limit_callback'], // Callback
      'trc-setting-admin', // Page
      'setting_section1'
      // Section
    );
  }

  public function sanitize($input)
  {
    $new_input = [];
    if (isset($input['posts_limit'])) {
      $new_input['posts_limit'] = absint($input['posts_limit']);
    }

    if (!$new_input['posts_limit']) {
      $this->logger->log('warning', '`posts_limit` value cannot be 0');
    }

    return $new_input;
  }

  public function posts_limit_callback()
  {
    printf('<input type="text" id="posts_limit" name="trecent_posts[posts_limit]" value="%s" placeholder="10" />', isset($this->options['posts_limit']) ? esc_attr($this->options['posts_limit']) : '');
  }
}

if (is_admin()) {
  $trc_settings_page = new TrcSettingsPage();
}
