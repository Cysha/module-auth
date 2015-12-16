<?php

namespace Cms\Modules\Auth\Datatables;

use Illuminate\Support\Collection;

class ApiKeyManager
{
    public function boot()
    {
        return [
            /*
             * Page Decoration Values
             */
            'page' => [
                'title'  => '<i class="fa fa-fw fa-puzzle-piece"></i> Api Manager',
                'header' => [
                    [
                        'btn-text'      => 'Add Key',
                        'btn-route'     => 'admin.apikey.create',
                        'btn-class'     => 'btn btn-info btn-labeled',
                        'btn-icon'      => 'fa fa-fw fa-refresh',
                        'hasPermission' => 'apikey.add@auth_user',
                    ],
                ],
            ],

            /*
             * Set up some table options, these will be passed back to the view
             */
            'options' => [
                'pagination'  => false,
                'searching'   => false,
                'ordering'    => false,
                'sort_column' => 'order',
                'sort_order'  => 'desc',
                'source'      => 'admin.apikey.manager',
                'collection'  => function () {
                    $model = 'Cms\Modules\Auth\Models\ApiKey';

                    return $model::all();
                },
            ],

            /*
             * Lists the tables columns
             */
            'columns' => [
                'user' => [
                    'th' => 'User',
                    'tr' => function ($model) {
                        return $model->user->screenname;
                    },
                    'orderable'  => true,
                    'searchable' => true,
                    'width'      => '10%',
                ],

                'active' => [
                    'th' => 'Active',
                    'tr' => function ($model) {
                        return (\Carbon\Carbon::now()->lte($model->expires_at))
                            ? '<div class="label label-success">Active</div>'
                            : '<div class="label label-danger">Not Active</div>';
                    },
                    'width' => '10%',
                ],

                'key' => [
                    'th' => 'Key',
                    'tr' => function ($model) {
                        return $model->key;
                    },
                    'width' => '10%',
                ],

                'description' => [
                    'th' => 'Description',
                    'tr' => function ($model) {
                        return $model->description;
                    },
                    'width' => '25%',
                ],

                'created_at' => [
                    'th' => 'Created',
                    'tr' => function ($model) {
                        return date_fuzzy($model->created_at);
                    },
                    'th-class' => 'hidden-xs hidden-sm',
                    'tr-class' => 'hidden-xs hidden-sm',
                    'width'    => '15%',
                ],

                'expires_at' => [
                    'th' => 'Expiry Date',
                    'tr' => function ($model) {
                        return date_fuzzy($model->expires_at);
                    },
                    'th-class' => 'hidden-xs hidden-sm',
                    'tr-class' => 'hidden-xs hidden-sm',
                    'width'    => '15%',
                ],

                'actions' => [
                    'th' => 'Actions',
                    'tr' => function ($model) {
                        return [
                            [
                                'btn-title'     => 'Remove Key',
                                'btn-link'      => route('admin.apikey.remove', $model->id),
                                'btn-class'     => 'btn btn-danger btn-xs btn-labeled',
                                'btn-icon'      => 'fa fa-times',
                                'btn-method'    => 'delete',
                                'btn-extras'    => 'data-remote="true" data-confirm="Are you sure you want to delete entry #'.$model->id.'?" data-disable-with="<i class=\'fa fa-refresh fa-spin\'></i>"',
                                'hasPermission' => 'apikey.delete@auth_user',
                            ],
                        ];
                    },
                    'width' => '5%',
                ],
            ],
        ];
    }
}
