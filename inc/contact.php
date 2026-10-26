<?php
/** Remove contact entries after Fluent Forms has processed its notifications. */
function mvillada_contact_cleanup($entry_id, $form)
{
    if ((int) $form->id !== (int) get_option('mv_contact_form_id') ||
        !\FluentForm\App\Helpers\Helper::isEntryAutoDeleteEnabled($form->id)) {
        return;
    }

    // Wait until the confirmation has been built; the plugin still reads the entry.
    add_action('shutdown', static function () use ($entry_id, $form) {
        (new \FluentForm\App\Services\Submission\SubmissionService())->deleteEntries(
            array((int) $entry_id),
            (int) $form->id
        );
    });
}
add_action('fluentform/global_notify_completed', 'mvillada_contact_cleanup', 20, 2);
