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
echo html_writer::div(html_writer::link('#', 'Load form', ['data-action' => 'loadform']));
echo html_writer::div('', '', ['data-region' => 'form']);

$PAGE->requires->js_amd_inline("
require(['core_form/ajaxform'], function(AjaxForm) {
    const form = new AjaxForm(document.querySelector('[data-region=form]'), 'local_modalformexamples\\\\testform');
    form.onSubmitSuccess = (response) => {
        console.log(response);
        form.container.innerHTML = '';
    }
    
    document.querySelector('[data-action=loadform]').addEventListener('click', (e) => {
        e.preventDefault();
        form.load({arg1: 'val1'});
    });
});");

echo $OUTPUT->footer();