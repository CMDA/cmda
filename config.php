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

$THEME->doctype = 'html5';
$THEME->yuicssmodules = array();
$THEME->name = 'cmda';
$THEME->parents = ['bootstrapbase']; // other option: boost
$THEME->sheets = []; // no static stylesheets, SCSS
$THEME->enable_dock = false;
$THEME->editor_sheets = [];

// override the theme renderer
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
//$THEME->requiredblocks = ''; // can we add the menu to all pages?

// forces a block region into the page when editing is enabled and it takes up too much room.
// $THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
