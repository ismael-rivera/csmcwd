<?php

/**
 * Test the auth cookie failure functionality
 *
 * @package login-security-solution
 * @author Daniel Convissor <danielc@analysisandsolutions.com>
 * @copyright The Analysis and Solutions Company, 2012
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 */

/**
 * Get the class we will use for testing
 */
require_once dirname(__FILE__) .  '/TestCase.php';

/**
 * Test the auth cookie failure functionality
 *
 * @package login-security-solution
 * @author Daniel Convissor <danielc@analysisandsolutions.com>
 * @copyright The Analysis and Solutions Company, 2012
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 */
class AuthCookieBadTest extends TestCase {
	protected $ip;
	protected $network_ip;
	protected $user_name;
	protected $pass_md5;


	public static function setUpBeforeClass() {
		parent::$db_needed = true;
		parent::set_up_before_class();
	}

	public function setUp() {
		parent::setUp();

		if (!$this->is_fail_table_configured()) {
			$this->markTestSkipped("The " . self::$lss->table_fail . " table doesn't exist or isn't using the InnoDB engine. Probably the plugin hasn't been activated.");
		}

		$this->ip = '1.2.3.4';
		$_SERVER['REMOTE_ADDR'] = $this->ip;
		$this->network_ip = '1.2.3';

		$this->user_name = 'test';
		$this->pass_md5 = 'ababab';

		$options = self::$lss->options;
		$options['login_fail_minutes'] = 60;
		$options['login_fail_notify'] = 4;
		$options['login_fail_tier_2'] = 3;
		$options['login_fail_tier_3'] = 4;
		$options['login_fail_breach_notify'] = 4;
		$options['login_fail_breach_pw_force_change'] = 4;
		self::$lss->options = $options;
	}


	public function test_direct() {
		$input = array(
			'username' => $this->user_name,
			'hmac' => $this->pass_md5,
		);
		self::$lss->auth_cookie_bad($input);
		$pass = self::$lss->md5($this->pass_md5);
		$this->check_fail_record($this->ip, $this->user_name, $pass);
	}

	/**
	 * @depends test_direct
	 */
	public function test_bad_user() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_COOKIE[AUTH_COOKIE] = wp_generate_auth_cookie(1, time() + 10);
		$parts = explode('|', $_COOKIE[AUTH_COOKIE]);
		$parts[0] = 'thisusercannotpossiblyexist';
		$_COOKIE[AUTH_COOKIE] = implode('|', $parts);

		$result = wp_validate_auth_cookie();
		$this->assertFalse($result);

		$pass = self::$lss->md5($parts[2]);
		$this->check_fail_record($this->ip, $parts[0], $pass);
	}

	/**
	 * @depends test_bad_user
	 */
	public function test_bad_pass() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_COOKIE[AUTH_COOKIE] = wp_generate_auth_cookie(1, time() + 10);
		$parts = explode('|', $_COOKIE[AUTH_COOKIE]);
		$parts[2] = 'badpassword';
		$_COOKIE[AUTH_COOKIE] = implode('|', $parts);

		$result = wp_validate_auth_cookie();
		$this->assertFalse($result);

		$pass = self::$lss->md5($parts[2]);
		$this->check_fail_record($this->ip, $parts[0], $pass);
	}
}
