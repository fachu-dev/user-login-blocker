<?php
/**
 * Plugin Name: User Login Blocker 
 * Description: Block selected users from logging in, with customizable message.
 * Version: 1.1.0
 * Author: fachu.dev
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

  private static function get_users_for_dropdown() {
    return get_users([
      'orderby' => 'display_name',
      'order'   => 'ASC',
      'fields'  => ['ID', 'display_name', 'user_login', 'user_email', 'roles'],
      'number'  => 2000,
    ]);
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

    $users = self::get_users_for_dropdown();
    ?>
    <div class="wrap">
      <h1>User Login Blocker</h1>

      <p>Block selected users from logging in. Works for any role (including administrators).</p>

      <form method="post" action="options.php">
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

        <?php submit_button('Save changes'); ?>
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
