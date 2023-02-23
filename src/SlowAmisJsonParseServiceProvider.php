<?php

namespace Slowlyo\SlowAmisJsonParse;

use Slowlyo\SlowAdmin\Renderers\TextControl;
use Slowlyo\SlowAdmin\Extend\ServiceProvider;

class SlowAmisJsonParseServiceProvider extends ServiceProvider
{
    protected $menu = [
        [
            'parent'   => '',
            'title'    => 'amis json 解析',
            'url'      => '/slow-amis-json-parse',
            'url_type' => '1',
            'icon'     => 'mdi:code-json',
        ],
    ];

    public function register()
    {
        //
    }

    public function init()
    {
        parent::init();

        //

    }
}
