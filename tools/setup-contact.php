<?php
/** Run with: wp eval-file wp-content/themes/<theme-directory>/tools/setup-contact.php */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) {
    exit;
}

use FluentForm\App\Models\Form;
use FluentForm\App\Models\FormMeta;
use FluentForm\App\Services\Form\FormService;

if (!class_exists(FormService::class)) {
    throw new RuntimeException('Activate Fluent Forms before running contact setup.');
}

$page = get_page_by_path('contact');
if ($page && !get_post_meta($page->ID, '_mv_contact_page', true)) {
    throw new RuntimeException('An existing contact page needs manual review; no changes were made.');
}
$form_id = (int) get_option('mv_contact_form_id');
$form = $form_id ? Form::find($form_id) : null;
if (!$form) {
    $form = (new FormService())->store(array('predefined' => 'basic_contact_form'));
    $definition = json_decode($form->form_fields, true);
    $name = $definition['fields'][0]['fields']['first_name'];
    $name['attributes']['name'] = 'contact_name';
    $name['attributes']['placeholder'] = '';
    $name['attributes']['autocomplete'] = 'name';
    $name['attributes']['maxlength'] = 120;
    $name['settings']['label'] = 'Your name';
    $name['settings']['validation_rules']['required']['value'] = true;
    $fields = array($name);
    foreach ($definition['fields'] as $field) {
        if (!in_array($field['element'], array('input_email', 'textarea'), true)) {
            continue;
        }
        $field['settings']['validation_rules']['required']['value'] = true;
        $field['attributes']['placeholder'] = '';
        if ($field['element'] === 'input_email') {
            $field['attributes']['name'] = 'email';
            $field['attributes']['autocomplete'] = 'email';
            $field['settings']['label'] = 'Your email';
        } else {
            $field['attributes']['name'] = 'message';
            $field['attributes']['rows'] = 6;
            $field['attributes']['maxlength'] = 5000;
            $field['settings']['label'] = 'Your message';
        }
        $fields[] = $field;
    }
    if (count($fields) !== 3) {
        throw new RuntimeException('Unexpected Fluent Forms contact template. Review the created form.');
    }
    $definition['fields'] = $fields;
    $definition['submitButton']['settings']['button_ui']['text'] = 'Send message';
    $form->title = 'Portfolio contact';
    $form->form_fields = wp_json_encode($definition);
    $form->save();
    $settings = Form::getFormsDefaultSettings();
    $settings['confirmation']['messageToShow'] = '<p>Thank you — your message has been submitted. I’ll get back to you at the email address you provided.</p>';
    $settings['delete_entry_on_submission'] = 'yes';
    FormMeta::persist($form->id, 'formSettings', $settings);
    FormMeta::persist($form->id, 'notifications', array(
        'name' => 'Portfolio contact notification',
        'enabled' => true,
        'sendTo' => array('type' => 'email', 'email' => 'mauricio@mvillada.com'),
        'fromName' => 'Mauricio Villada Portfolio',
        'fromEmail' => '',
        'replyTo' => '{inputs.email}',
        'bcc' => '',
        'subject' => 'New portfolio enquiry',
        'message' => '<p>{all_data}</p>',
        'conditionals' => array('status' => false, 'type' => 'all', 'conditions' => array()),
    ));
    update_option('mv_contact_form_id', (int) $form->id, false);
}

// Merge the honeypot setting without replacing other global plugin settings.
$global = get_option('_fluentform_global_form_settings', array());
$global['misc']['honeypotStatus'] = 'yes';
update_option('_fluentform_global_form_settings', $global);

if (!$page) {
    $page_id = wp_insert_post(array(
        'post_type' => 'page', 'post_status' => 'publish',
        'post_title' => 'Contact', 'post_name' => 'contact',
        'post_content' => '[fluentform id="' . (int) $form->id . '"]',
        'meta_input' => array('_wp_page_template' => 'page-contact.php', '_mv_contact_page' => 1),
    ), true);
    if (is_wp_error($page_id)) {
        throw new RuntimeException($page_id->get_error_message());
    }
} else {
    $page_id = $page->ID;
}
echo 'Contact page: ' . get_permalink($page_id) . PHP_EOL;
echo 'Fluent Forms ID: ' . (int) $form->id . PHP_EOL;
