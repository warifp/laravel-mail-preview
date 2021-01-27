<?php

return [
    /*
     * All mails will be stored here
     */
    'storage_path' => storage_path('email-previews'),

    /*
     * This option determines how long generated preview files will be kept.
     */
    'maximum_lifetime_in_seconds' => 60,

    /*
     * This option determines if you would like to show a HTML link at the top
     * left corner of your screen every time and email is sent from your
     * system, the link will point the browser to the preview file.
     *
     */
    'show_link_to_preview' => true,

    /*
     * Determines how long the preview pop up should remain visible.
     *
     * You can set this to `false` if the popup should stay visible.
     */
    'popup_timeout_in_seconds' => 8,

    /*
     * Most likely you don't have to touch this value, in this array all
     * middleware groups that you want to use this package with should
     * be included.
     */

    'middleware_groups' => [
        'web'
    ],

    /*
     * This option allows for setting middleware for the route that shows a
     * preview to the mail that was just sent.
     */
    'middleware' => [
        //
    ],
];
