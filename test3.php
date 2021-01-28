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

echo $OUTPUT->header();
echo $OUTPUT->heading('Test3 - prerendered form submitted with AJAX');
echo html_writer::div($form->render(), '', ['data-region' => 'form']);

$PAGE->requires->js_amd_inline("
require(['core_form/ajaxform', 'core/notification'], function(AjaxForm, Notification) {
    const form = new AjaxForm(document.querySelector('[data-region=form]'), 'local_modalformexamples\\\\testform');
    const formargs = {arg1: 'val1'};
    form.onSubmitSuccess = (response) => {
        form.load({...formargs, name: response.name});
        Notification.addNotification({message: 'Form submitted: '+JSON.stringify(response), type: 'success'});
    }
    
    // Cancel button does not make much sense in such forms but since it's there we'll just redirect back to index.
    form.onCancel = () => {
        form.load(formargs);
        Notification.addNotification({message: 'Form cancelled', type: 'error'});
    };
});");

echo $OUTPUT->footer();