<?php
/**
 * @author         Pierre-Henry Soria <hello@lifyzer.com>
 * @copyright      (c) 2018-2020, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 * @link           https://lifyzer.com
 */

/**
 * Avoid  undefined constants when running tests.
 */
define('SITE_URL', 'https://lifyzer.com/app/');
define('SITE_NAME', 'Lifyzer App');
define('DEBUG_MODE', true);

require dirname(__DIR__) . '/Server/vendor/autoload.php';
