<?php
/**
 * Plugin Name: User Login Blocker 
 * Description: Block selected users from logging in, with customizable message.
 * Version: 1.2
 * Author: fachu.dev
 * Author URI: https://fachu.dev
 * Website: https://fachu.dev 
 */

if (!defined('ABSPATH')) exit;

class ULB_User_Login_Blocker {
  const OPT_BLOCKED_USERS = 'ulb_blocked_user_ids';
  const OPT_MESSAGE       = 'ulb_block_message';

  public static function init() {
    add_filter('authenticate', [__CLASS__, 'maybe_block_login'], 30, 3);

    add_action('admin_menu', [__CLASS__, 'admin_menu']);
    add_action('admin_init', [__CLASS__, 'register_settings']);
  }

  public static function maybe_block_login($user, $username, $password) {
    if (is_wp_error($user)) return $user;
    if (!$user || !is_a($user, 'WP_User')) return $user;

    $blocked = get_option(self::OPT_BLOCKED_USERS, []);
    if (!is_array($blocked)) $blocked = [];

    $blocked = array_map('intval', $blocked);

    if (in_array((int)$user->ID, $blocked, true)) {
      $msg = get_option(self::OPT_MESSAGE, 'Incorrect user. Please contact administration.');
      $msg = is_string($msg) ? trim($msg) : 'Incorrect user. Please contact administration.';
      if ($msg === '') $msg = 'Incorrect user. Please contact administration.';

      return new WP_Error('ulb_user_blocked', $msg);
    }

    return $user;
  }

  public static function admin_menu() {
    add_users_page(
      'User Login Blocker',
      'User Login Blocker',
      'manage_options',
      'user-login-blocker',
      [__CLASS__, 'render_settings_page']
    );
  }

  public static function register_settings() {
    register_setting('ulb_settings_group', self::OPT_BLOCKED_USERS, [
      'type'              => 'array',
      'sanitize_callback' => [__CLASS__, 'sanitize_user_ids'],
      'default'           => [],
    ]);

    register_setting('ulb_settings_group', self::OPT_MESSAGE, [
      'type'              => 'string',
      'sanitize_callback' => [__CLASS__, 'sanitize_message'],
      'default'           => 'Incorrect user. Please contact administration.',
    ]);
  }

  public static function sanitize_user_ids($value) {
    if (!is_array($value)) return [];
    $value = array_map('intval', $value);
    $value = array_values(array_unique(array_filter($value, fn($v) => $v > 0)));
    return $value;
  }

  public static function sanitize_message($value) {
    $value = is_string($value) ? wp_strip_all_tags($value) : '';
    $value = trim($value);
    if ($value === '') {
      $value = 'Incorrect user. Please contact administration.';
    }
    return $value;
  }

  private static function get_users_for_dropdown($role_filter = '', $search_term = '') {
    $args = [
      'orderby' => 'display_name',
      'order'   => 'ASC',
      'fields'  => ['ID', 'display_name', 'user_login', 'user_email', 'roles'],
      'number'  => 2000,
    ];

    if (!empty($role_filter)) {
      $args['role'] = $role_filter;
    }

    if (!empty($search_term)) {
      $args['search'] = '*' . $search_term . '*';
    }

    return get_users($args);
  }

  private static function get_all_roles() {
    global $wp_roles;
    if (!isset($wp_roles)) {
      $wp_roles = new WP_Roles();
    }
    return $wp_roles->get_names();
  }

  private static function label_user($u) {
    $roles = is_array($u->roles) ? implode(',', $u->roles) : '';
    $parts = [];
    $parts[] = $u->display_name ?: $u->user_login;
    $parts[] = '@' . $u->user_login;
    if (!empty($u->user_email)) $parts[] = $u->user_email;
    if (!empty($roles)) $parts[] = 'roles: ' . $roles;
    $parts[] = 'ID: ' . $u->ID;
    return implode(' — ', $parts);
  }

  public static function render_settings_page() {
    if (!current_user_can('manage_options')) {
      wp_die('You do not have permission to access this page.');
    }

    // Handle unblock action
    if (isset($_POST['unblock_user']) && isset($_POST['user_id'])) {
      check_admin_referer('ulb_unblock_user', 'ulb_unblock_nonce');
      $user_id = intval($_POST['user_id']);
      $blocked = get_option(self::OPT_BLOCKED_USERS, []);
      if (is_array($blocked)) {
        $blocked = array_diff($blocked, [$user_id]);
        update_option(self::OPT_BLOCKED_USERS, array_values($blocked));
        echo '<div class="notice notice-success is-dismissible"><p>User unblocked successfully.</p></div>';
      }
    }

    $blocked = get_option(self::OPT_BLOCKED_USERS, []);
    if (!is_array($blocked)) $blocked = [];
    $blocked = array_map('intval', $blocked);

    $msg = get_option(self::OPT_MESSAGE, 'Incorrect user. Please contact administration.');

    // Get filter parameters
    $role_filter = isset($_GET['role_filter']) ? sanitize_text_field($_GET['role_filter']) : '';
    $search_term = isset($_GET['search_users']) ? sanitize_text_field($_GET['search_users']) : '';
    
    $users = self::get_users_for_dropdown($role_filter, $search_term);
    $all_roles = self::get_all_roles();
    ?>
    <div class="wrap">
      <h1>User Login Blocker</h1>

      <p>Block selected users from logging in. Works for any role (including administrators).</p>

      <div style="background: #f0f0f1; padding: 15px; margin: 20px 0; border-left: 4px solid #0073aa;">
        <h3 style="margin-top: 0;">Filter Users</h3>
        <form method="get" style="margin: 0;">
          <input type="hidden" name="page" value="user-login-blocker" />
          <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <div>
              <label for="role_filter" style="font-weight: bold; margin-right: 5px;">By Role:</label>
              <select name="role_filter" id="role_filter" onchange="this.form.submit()">
                <option value="">All Users</option>
                <?php foreach ($all_roles as $role_key => $role_name): ?>
                  <option value="<?php echo esc_attr($role_key); ?>" <?php selected($role_filter, $role_key); ?>>
                    <?php echo esc_html($role_name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="search_users" style="font-weight: bold; margin-right: 5px;">Search:</label>
              <input 
                type="text" 
                name="search_users" 
                id="search_users"
                value="<?php echo esc_attr($search_term); ?>" 
                placeholder="Name, email or username..."
                style="width: 200px;"
              />
              <input type="submit" value="Search" class="button" style="margin-left: 5px;" />
            </div>
            <?php if ($role_filter || $search_term): ?>
              <div>
                <a href="?page=user-login-blocker" class="button button-secondary">Clear Filters</a>
              </div>
            <?php endif; ?>
          </div>
          <noscript><input type="submit" value="Apply Filter" class="button" /></noscript>
        </form>
        <p style="margin-bottom: 0; font-style: italic; color: #666;">
          Use these filters to easily find users when you have many users on your site.
        </p>
      </div>

      <form method="post" action="options.php"><?php echo ($role_filter || $search_term) ? '<!-- Filtered by: ' . esc_html($role_filter ?: 'role') . ($search_term ? ', search: ' . esc_html($search_term) : '') . ' -->' : ''; ?>
        <?php settings_fields('ulb_settings_group'); ?>

        <table class="form-table" role="presentation">
          <tr>
            <th scope="row"><label for="ulb_blocked_user_ids">Blocked users</label></th>
            <td>
              <select
                id="ulb_blocked_user_ids"
                name="<?php echo esc_attr(self::OPT_BLOCKED_USERS); ?>[]"
                multiple
                size="12"
                style="min-width: 520px; max-width: 100%;"
              >
                <?php foreach ($users as $u): ?>
                  <?php
                    $selected = in_array((int)$u->ID, $blocked, true) ? 'selected' : '';
                  ?>
                  <option value="<?php echo (int)$u->ID; ?>" <?php echo $selected; ?>>
                    <?php echo esc_html(self::label_user($u)); ?>
                  </option>
                <?php endforeach; ?>
              </select>

              <p class="description">
                Tip: on Mac/Windows hold <strong>Cmd/Ctrl</strong> to select multiple users.
                <?php if ($role_filter || $search_term): ?>
                <br>Currently showing: 
                <?php if ($role_filter): ?>
                  users with role <strong><?php echo esc_html($all_roles[$role_filter] ?? $role_filter); ?></strong>
                <?php endif; ?>
                <?php if ($search_term): ?>
                  <?php echo $role_filter ? ' and' : ''; ?> matching "<strong><?php echo esc_html($search_term); ?></strong>"
                <?php endif; ?>
                <?php endif; ?>
              </p>
            </td>
          </tr>

          <tr>
            <th scope="row"><label for="ulb_block_message">Login message</label></th>
            <td>
              <input
                type="text"
                id="ulb_block_message"
                name="<?php echo esc_attr(self::OPT_MESSAGE); ?>"
                value="<?php echo esc_attr($msg); ?>"
                class="regular-text"
                style="min-width: 520px; max-width: 100%;"
              />
              <p class="description">
                This message will appear on wp-login when a blocked user tries to sign in.
              </p>
            </td>
          </tr>
        </table>

        <?php submit_button('Block Selected Users', 'primary', 'submit', true, ['id' => 'ulb-submit-btn']); ?>
      </form>

      <hr />

      <h2>Currently blocked users</h2>
      <?php
        if (empty($blocked)) {
          echo '<p><em>No blocked users.</em></p>';
        } else {
          echo '<div style="background: #f8f8f8; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">';
          foreach ($blocked as $id) {
            $u = get_user_by('id', $id);
            if ($u) {
              echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">';
              echo '<span>' . esc_html(self::label_user($u)) . '</span>';
              echo '<form method="post" style="margin: 0; display: inline-block;">';
              wp_nonce_field('ulb_unblock_user', 'ulb_unblock_nonce');
              echo '<input type="hidden" name="user_id" value="' . intval($id) . '" />';
              echo '<input type="submit" name="unblock_user" value="Unblock" class="button button-secondary button-small" onclick="return confirm(\'Are you sure you want to unblock this user?\');" />';
              echo '</form>';
              echo '</div>';
            } else {
              echo '<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">';
              echo '<span style="color: #999;">' . esc_html('User ID: ' . $id . ' (not found)') . '</span>';
              echo '<form method="post" style="margin: 0; display: inline-block;">';
              wp_nonce_field('ulb_unblock_user', 'ulb_unblock_nonce');
              echo '<input type="hidden" name="user_id" value="' . intval($id) . '" />';
              echo '<input type="submit" name="unblock_user" value="Remove" class="button button-secondary button-small" onclick="return confirm(\'Remove this invalid user ID from blocked list?\');" />';
              echo '</form>';
              echo '</div>';
            }
          }
          echo '</div>';
        }
      ?>
    </div>
    <?php
  }
}

ULB_User_Login_Blocker::init();
