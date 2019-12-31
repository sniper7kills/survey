<?php

return [
    /**
     * Base path for all survey routes
     *
     * I.E.
     * survey => http://localhost/survey
     * forms  => http://localhost/forms
     *
     */
    'root_path' => 'survey',

    /**
     * The column identifier to use in the url schemes
     *
     * slug => http://localhost/survey/sample-survey
     * id   => http://localhost/survey/f4b21a53-c19e-4aa6-bdb8-9393619d5ebf
     *
     * Valid options: slug, id
     */
    'identifier' => 'slug'
];