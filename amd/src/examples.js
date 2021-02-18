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
 * Dynamic forms examples
 *
 * @module     local_modalformexamples/examples
 * @copyright  2021 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import DynamicForm from 'core_form/dynamicform';
import Notification from 'core/notification';
import ModalForm from 'core_form/modalform';
import {add as addToast} from 'core/toast';

const addNotification = msg => {
    addToast(msg);
    // eslint-disable-next-line no-console
    console.log(msg);
};


export const test2 = (linkSelector, formClass, resultSelector) => {
    document.querySelector(linkSelector).addEventListener('click', (e) => {
        e.preventDefault();
        const form = new ModalForm({
            formClass,
            args: {hidebuttons: 1},
            modalConfig: {title: 'Test2'},
            returnFocus: e.currentTarget
        });
        // If necessary extend functionality by overriding class methods, for example:
        form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
            const response = e.detail;
            addNotification('Form submitted...');
            document.querySelector(resultSelector).innerHTML = '<pre>' + JSON.stringify(response) + '</pre>';
        });

        // Demo of different events.
        form.addEventListener(form.events.LOADED, () => addNotification('Form loaded'));
        form.addEventListener(form.events.NOSUBMIT_BUTTON_PRESSED,
            (e) => addNotification('No submit button pressed ' + e.detail.getAttribute('name')));
        form.addEventListener(form.events.CLIENT_VALIDATION_ERROR, () => addNotification('Client-side validation error'));
        form.addEventListener(form.events.SERVER_VALIDATION_ERROR, () => addNotification('Server-side validation error'));
        form.addEventListener(form.events.ERROR, () => addNotification('Oopsie'));
        form.addEventListener(form.events.SUBMIT_BUTTON_PRESSED, () => addNotification('Submit button pressed'));
        form.addEventListener(form.events.CANCEL_BUTTON_PRESSED, () => addNotification('Cancel button pressed'));

        form.show();
    });

};

export const test3 = (selector, formClass) => {
    const form = new DynamicForm(document.querySelector(selector), formClass);
    const formargs = {arg1: 'val1'};
    form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
        e.preventDefault();
        const response = e.detail;
        form.load({...formargs, name: response.name});
        addNotification('Form submitted');
        Notification.addNotification({message: 'Form submitted: ' + JSON.stringify(response), type: 'success'});
    });

    // Cancel button does not make much sense in such forms but since it's there we'll just reload.
    form.addEventListener(form.events.FORM_CANCELLED, (e) => {
        e.preventDefault();
        form.notifyResetFormChanges()
        .then(() => form.load(formargs));
        addNotification('Form cancelled');
    });

    // Demo of different events.
    form.addEventListener(form.events.NOSUBMIT_BUTTON_PRESSED, () => addNotification('No submit button pressed'));
    form.addEventListener(form.events.CLIENT_VALIDATION_ERROR, () => addNotification('Client-side validation error'));
    form.addEventListener(form.events.SERVER_VALIDATION_ERROR, () => addNotification('Server-side validation error'));
    form.addEventListener(form.events.ERROR, () => addNotification('Oopsie'));
    form.addEventListener(form.events.SUBMIT_BUTTON_PRESSED, () => addNotification('Submit button pressed'));
    form.addEventListener(form.events.CANCEL_BUTTON_PRESSED, () => addNotification('Cancel button pressed'));
};

export const test4 = (selector, formClass, linkSelector) => {
    const form = new DynamicForm(document.querySelector(selector), formClass);
    form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
        e.preventDefault();
        const response = e.detail;
        form.container.innerHTML = JSON.stringify(response);
    });

    document.querySelector(linkSelector).addEventListener('click', (e) => {
        e.preventDefault();
        form.load({arg1: 'val1'});
    });

    // Add confirmation for the Cancel button.
    form.addEventListener(form.events.CANCEL_BUTTON_PRESSED, (e) => {
        e.preventDefault();
        Notification.confirm(
            'Confirm',
            'Are you sure you want to cancel?',
            'Yes', // Delete.
            'No', // Cancel.
            form.processCancelButton.bind(form)
        );
    });
};