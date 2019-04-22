<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CodeGenerator config overrides
    |--------------------------------------------------------------------------
    |
    | It is a good idea to sperate your configuration form the code-generator's
    | own configuration. This way you won't lose any settings/preference
    | you have when upgrading to a new version of the package.
    |
    | Additionally, you will always know any the configuration difference between
    | the default config than your own.
    |
    | To override the setting that is found in the codegenerator.php file, you'll
    | need to create identical key here with a different value
    |
    | IMPORTANT: When overriding an option that is an array, the configurations
    | are merged together using php's array_merge() function. This means that
    | any option that you list here will take presence during a conflict in keys.
    |
    | EXAMPLE: The following addition to this file, will add another entry in
    | the common_definitions collection
    |
    |   'common_definitions' =>
    |   [
    |       [
    |           'match' => '*_at',
    |           'set' => [
    |               'css-class' => 'datetime-picker',
    |           ],
    |       ],
    |   ],
    |
     */

    /*
    |--------------------------------------------------------------------------
    | The default template to use.
    |--------------------------------------------------------------------------
    |
    | Here you change the stub templates to use when generating code.
    | You can duplicate the 'default' template folder and call it whatever
    | template name you like 'ex. skyblue'. Now, you can change the stubs to
    | have your own templates generated.
    |
    |
    | IMPORTANT: It is not recommended to modify the default template, rather
    | create a new template. If you modify the default template and then
    | executed 'php artisan vendor:publish' command, will override your changes!
    |
     */
    'template' => 'nova',

    /*
    |--------------------------------------------------------------------------
    | The default path of where the uploaded files live.
    |--------------------------------------------------------------------------
    |
    | You can use Laravel Storage filesystem. By default, the code-generator
    | uses the default file system.
    | For more info about Laravel's file system visit
    | https://laravel.com/docs/5.5/filesystem
    |
     */
    'files_upload_path' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Patterns to use to pre-set field's properties.
    |--------------------------------------------------------------------------
    |
    | To make constructing fields easy, the code-generator scans the field's name
    | for a matching pattern. If the name matches any of these patterns, the
    | field's properties will be set accordingly. Defining pattern will save
    | you from having to re-define the properties for common fields.
    |
     */
    'common_definitions' => [
        [
            'match' => 'id',
            'set' => [
                'is-on-form' => false,
                'is-on-index' => true,
                'is-on-show' => true,
                'is-api-visible' => true,
                'html-type' => 'number',
                'nova-type' => 'id',
                'data-type' => 'biginteger',
                'is-primary' => true,
                'is-auto-increment' => true,
                'is-nullable' => false,
                'is-unsigned' => true,
            ],
        ],
        [
            'match' => ['name'],
            'set' => [
                'is-nullable' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'translatable-single',
            ],
        ],
        [
            'match' => ['description*',],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'translatable-trix'
            ],
        ],
        [
            'match' => ['addresses'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'addresses',
            ],
        ],
        [
            'match' => ['people'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'people',
            ],
        ],
        [
            'match' => ['phones'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'phones',
            ],
        ],
        [
            'match' => ['socials'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'socials',
            ],
        ],
        [
            'match' => ['images'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'ebess-images',
            ],
        ],
        [
            'match' => ['invoicing', 'accounting', 'gateways', 'settings', 'meta'],
            'set' => [
                'is-on-index' => false,
                'data-type' => 'json',
                'html-type' => 'textarea',
                'nova-type' => 'code-json',
            ],
        ],
        [
            'match' => ['*count*', 'total*', '*number*', '_no', '*age*'],
            'set' => [
                'html-type' => 'number',
                'nova-type' => 'number',
            ],
        ],
        [
            'match' => ['*_tot', '*_net', '*_vat', '*_sum'],
            'set' => [
                'data-type' => 'decimal',
                'data-type-params' => [8,2],
                'html-type' => 'number',
                'nova-type' => 'number',
            ],
        ],
        [
            'match' => ['*_percent'],
            'set' => [
                'data-type' => 'decimal',
                'data-type-params' => [8, 4],
                'html-type' => 'number',
                'nova-type' => 'percent',
            ],
        ],
        [
            'match' => ['picture', 'file', 'photo', 'avatar'],
            'set' => [
                'is-on-index' => false,
                'html-type' => 'file',
            ],
        ],
        [
            'match' => ['*password*'],
            'set' => [
                'html-type' => 'password',
            ],
        ],
        [
            'match' => ['*email*'],
            'set' => [
                'html-type' => 'email',
            ],
        ],
        [
            'match' => ['*_id', '*_by'],
            'set' => [
                'data-type' => 'biginteger',
                'html-type' => 'select',
                'is-nullable' => false,
                'is-unsigned' => true,
                'is-index' => true,
            ],
        ],
        [
            'match' => ['*_at'],
            'set' => [
                'data-type' => 'datetime',
            ],
        ],
        [
            'match' => ['created_at', 'updated_at', 'deleted_at'],
            'set' => [
                'data-type' => 'datetime',
                'is-on-form' => false,
                'is-api-visible' => false,
                'is-on-index' => false,
                'is-on-show' => true,
            ],
        ],
        [
            'match' => ['*_date', 'date_*'],
            'set' => [
                'data-type' => 'date',
                'date-format' => 'j/n/Y',
            ],
        ],
        [
            'match' => ['is_*', 'has_*'],
            'set' => [
                'data-type' => 'boolean',
                'html-type' => 'checkbox',
                'is-nullable' => false,
                'options' => ["No", "Yes"],
            ],
        ],
        [
            'match' => 'created_by',
            'set' => [
                'title' => 'Creator',
                'data-type' => 'integer',
                'foreign-relation' => [
                    'name' => 'creator',
                    'type' => 'belongsTo',
                    'params' => [
                        'App\\User',
                        'created_by',
                    ],
                    'field' => 'name',
                ],
                'on-store' => 'Illuminate\Support\Facades\Auth::Id();',
            ],
        ],
        [
            'match' => ['updated_by', 'modified_by'],
            'set' => [
                'title' => 'Updater',
                'data-type' => 'integer',
                'foreign-relation' => [
                    'name' => 'updater',
                    'type' => 'belongsTo',
                    'params' => [
                        'App\\User',
                        'updated_by',
                    ],
                    'field' => 'name',
                ],
                'on-update' => 'Illuminate\Support\Facades\Auth::Id();',
            ],
        ],
    ],


];
