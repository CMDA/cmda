<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * CMDA Theme
 *
 * @package    theme_cmda
 * @copyright  2017 David de Vries, Justus Sturkenboom
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

/**
 * [theme_cmda_get_main_scss_content description]
 *
 * @param  [type] $theme [description]
 * @return [type]        [description]
 */
 function theme_cmda_get_main_scss_content($theme) {
     global $CFG;

     $scss = '';
     $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
     $fs = get_file_storage();

     $context = context_system::instance();
     if ($filename == 'default.scss') {
         // The default layout for the cmda theme
         $scss .= file_get_contents($CFG->dirroot . '/theme/cmda/scss/preset/default.scss');

     } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_cmda', 'preset', 0, '/', $filename))) {
         // This preset file was fetched from the file area for theme_cmda and not theme_boost (see the line above).
         $scss .= $presetfile->get_content();

     } else {
         // Safety fallback - maybe new installs etc. fallback to the default theme from boost
         $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
     }

     // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = file_get_contents($CFG->dirroot . '/theme/cmda/scss/pre.scss');

    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/cmda/scss/post.scss');

    // Combine them together.
    return $pre . "\n" . $scss . "\n" . $post;
 }

 function theme_cmda_update_settings_images($settingname) {
     global $CFG;

     // The setting name that was updated comes as a string like
     // 's_theme_cmda_headerbackgroundimage' split it on '_' characters.
     $parts = explode('_', $settingname);
     $settingname = end($parts); // The last part has the setting name

     $syscontext = context_system::instance(); // Admin settings are stored in system context.
     $component = 'theme_cmda'; // Component name the setting is stored in.

     // This is the value of the admin setting which is the filename of the uploaded file.
     // We extract the file extension because we want to preserve it.
     $filename = get_config($component, $settingname);
     $extension = substr($filename, strrpos($filename, '.') + 1);

     // This is the path in the moodle internal file system.
     $fullpath = "/{$syscontext->id}/{$component}/{$settingname}/0{$filename}";
     $fs = get_file_storage(); // Instance of the moodle file storage.

     // This is an efficient way to get a file if we know the exact path.
     if ($file = $fs->get_file_by_hash(sha1($fullpath))) {
         // We got the stored file - copy it to dataroot.
         // This location matches the searched for location in theme_config::resolve_image_location.
         $pathname = $CFG->dataroot . '/pix_plugins/theme/cmda/' . $settingname . '.' . $extension;

         // This pattern matches any previous files with maybe different file extensions.
         $pathpattern = $CFG->dataroot . '/pix_plugins/theme/cmda/' . $settingname . '.*';

         // Make sure this dir exists.
         @mkdir($CFG->dataroot . '/pix_plugins/theme/cmda/', $CFG->directorypermissions, true);

         // Delete any existing files for this setting.
         foreach (glob($pathpattern) as $filename) {
             @unlink($filename);
         }
         // Copy the current file to this location.
         $file->copy_content_to($pathname);
     }
     // Reset theme caches.
     theme_reset_all_caches();
 }
