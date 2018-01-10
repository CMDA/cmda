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

// This is used for performance, we don't need to know about these settings on every
// page in Moodle, only when we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Use the boost settings page tool to split settings into multiple tabs
    $settings = new theme_boost_admin_settingspage_tabs('themesettingcmda', get_string('configtitle', 'theme_cmda'));

    // -------------------------------------------------------------------------------
    // General settings tab
    // -------------------------------------------------------------------------------
    $page = new admin_settingpage('theme_cmda_general', get_string('generalsettings', 'theme_cmda'));

    // Replicate the preset setting from boost.
    $name = 'theme_cmda/preset';
    $title = get_string('preset', 'theme_cmda');
    $description = get_string('preset_desc', 'theme_cmda');
    $default = 'default.scss';

    // We list files in our own file area to add to the drop down. We will provide our
    // own function to load all the presets from the correct paths.
    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_cmda', 'preset', 0, 'itemid, filepath, filename', false);
    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets, why is this hardcoded, we've got a function in lib.php
    $choices['default.scss'] = 'default.scss';  // need two? can we remove one?
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_cmda/presetfiles';
    $title = get_string('presetfiles','theme_cmda');
    $description = get_string('presetfiles_desc', 'theme_cmda');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Header background setting
    $name = 'theme_cmda/headerbackgroundimage';
    $title = get_string('headerbackgroundimage', 'theme_cmda');
    $description = get_string('headerbackgroundimage_desc', 'theme_cmda');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerbackgroundimage');
    $setting->set_updatedcallback('theme_cmda_update_settings_images');
    $page->add($setting);
    
    // Variable $brand-color. default is #FFE538 which is the main CMD color
    $name = 'theme_cmda/brandcolor';
    $title = get_string('brandcolor', 'theme_cmda');
    $description = get_string('brandcolor_desc', 'theme_cmda');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#FFE538');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
    // -------------------------------------------------------------------------------
    // Advanced settings tab
    // -------------------------------------------------------------------------------
    $page = new admin_settingpage('theme_cmda_advanced', get_string('advancedsettings', 'theme_cmda'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_configtextarea('theme_cmda/scsspre',
        get_string('rawscsspre', 'theme_cmda'), get_string('rawscsspre_desc', 'theme_cmda'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_configtextarea('theme_cmda/scss', get_string('rawscss', 'theme_cmda'),
        get_string('rawscss_desc', 'theme_cmda'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);
}
