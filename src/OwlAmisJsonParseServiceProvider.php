<?php

namespace Slowlyo\OwlAmisJsonParse;

use Slowlyo\OwlAdmin\Extend\ServiceProvider;

class OwlAmisJsonParseServiceProvider extends ServiceProvider
{
    protected $menu = [
        [
            'title'    => 'amis json 解析',
            'url'      => '/slow-amis-json-parse',
            'url_type' => '1',
            'icon'     => 'fa fa-repeat',
        ],
    ];
}
