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

namespace local_modalformexamples;

/**
 * Class testform
 *
 * See PHPdocs in the parent class to understand the purpose of each method
 *
 * @package     local_modalformexamples
 * @copyright   2019 Marina Glancy
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testform extends \core_form\modal {

    protected function get_context_for_dynamic_submission(): \context {
        return \context_system::instance();
    }

    protected function check_access_for_dynamic_submission() {
        require_capability('moodle/site:config', \context_system::instance());
    }

    public function set_data_for_dynamic_submission() {
        $this->set_data([
            'hidebuttons' => $this->optional_param('hidebuttons', false, PARAM_BOOL),
            'name' => $this->optional_param('name', '', PARAM_TEXT),
        ]);
    }

    public function process_dynamic_submission() {
        return $this->get_data();
    }

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('static', 'aboutform', '', 'This form has client-side validation that Name must be present '.
            ' and server-side validation that Name must have at least three characters');

        // Required field (client-side validation test).

        $mform->addElement('text', 'name', get_string('fieldname', 'core_customfield'), 'size="50"');
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        // Repeated elements.

        $repeatarray = array();
        $repeatarray[] = $mform->createElement('text', 'option', get_string('optionno', 'choice'));
        $mform->setType('option', PARAM_CLEANHTML);
        $mform->setType('optionid', PARAM_INT);

        $this->repeat_elements($repeatarray, 1,
            [], 'option_repeats', 'option_add_fields', 1, null, true);

        // Editor.

        $desceditoroptions = $this->get_description_text_options();
        $mform->addElement('editor', 'description_editor', get_string('description', 'core_customfield'),
            ['rows' => 2], $desceditoroptions);
        $mform->addHelpButton('description_editor', 'description', 'core_customfield');

        // Buttons.

        $mform->addElement('hidden', 'hidebuttons');
        $mform->setType('hidebuttons', PARAM_BOOL);
        if (empty($this->_ajaxformdata['hidebuttons'])) {
            $this->add_action_buttons();
        }
    }

    public function validation($data, $files) {
        $errors = [];
        if (strlen($data['name']) < 3) {
            $errors['name'] = 'Name must be at least three characters long';
        }
        return $errors;
    }

    public function get_description_text_options() : array {
        global $CFG;
        require_once($CFG->libdir.'/formslib.php');
        return [
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => $CFG->maxbytes,
            'context' => \context_system::instance()
        ];
    }

    protected function get_page_url_for_dynamic_submission(): \moodle_url {
        return new \moodle_url('/local/modalformexamples/test.php');
    }

}
