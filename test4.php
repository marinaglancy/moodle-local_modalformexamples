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
 * @package    local_modalformexamples
 * @copyright  2019 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('local_modalformexamples', '', [],
    new moodle_url('/local/modalformexamples/test4.php'));

echo $OUTPUT->header();
echo $OUTPUT->heading('Test4 - dynamically loaded form');
echo html_writer::tag('p', 'Press "Load form" to dynamically load the form. When submitted it will be removed. '.
    'This page has example of confirmation dialogue for the "Cancel" button.');
echo html_writer::div(html_writer::link('#', 'Load form', ['data-action' => 'loadform1']));
echo html_writer::div(html_writer::link('#', 'Load form with data', ['data-action' => 'loadform2']));
echo html_writer::div('', '', ['data-region' => 'form1']);
echo html_writer::div('', '', ['data-region' => 'form2']);

$PAGE->requires->js_call_amd(
    'local_modalformexamples/examples',
    'test4',
    ['[data-region=form1]', \local_modalformexamples\testform::class, '[data-action=loadform1]', false]
);

$PAGE->requires->js_call_amd(
    'local_modalformexamples/examples',
    'test4',
    ['[data-region=form2]', \local_modalformexamples\testform::class, '[data-action=loadform2]', true]
);

echo $OUTPUT->footer();