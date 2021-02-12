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
    new moodle_url('/local/modalformexamples/test3.php'));

$form = new \local_modalformexamples\testform();
$form->set_data_for_dynamic_submission();

echo $OUTPUT->header();
echo $OUTPUT->heading('Test3 - prerendered form submitted with AJAX');
echo html_writer::tag('p', 'This form is always submitted in AJAX request and the page is never reloaded. '.
    'It has event listeners for all possible events (remember that "toast" notifications display the first notification '.
    'in the bottom and the last one is on the top).');

echo html_writer::div($form->render(), '', ['data-region' => 'form']);

$PAGE->requires->js_call_amd(
    'local_modalformexamples/examples',
    'test3',
    ['[data-region=form]', \local_modalformexamples\testform::class]
);

echo $OUTPUT->footer();