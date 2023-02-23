<?php

namespace Slowlyo\SlowAmisJsonParse\Http\Controllers;

use Illuminate\Http\Request;
use Slowlyo\SlowAdmin\Renderers\Card;
use Slowlyo\SlowAdmin\Renderers\Form;
use Slowlyo\SlowAdmin\Renderers\Flex;
use Slowlyo\SlowAdmin\Renderers\Html;
use Slowlyo\SlowAdmin\Renderers\UrlAction;
use Slowlyo\SlowAmisJsonParse\Support\Parse;
use Slowlyo\SlowAdmin\Renderers\EditorControl;
use Slowlyo\SlowAdmin\Renderers\VanillaAction;
use Slowlyo\SlowAdmin\Renderers\SwitchControl;
use Slowlyo\SlowAdmin\Controllers\AdminController;

class SlowAmisJsonParseController extends AdminController
{
    protected string $pageTitle = 'amis json 解析';

    public function index()
    {
        $page = $this->basePage()->body([
            Card::make()->body(
                Form::make()->api(admin_url('/slow-amis-json-parse'))->wrapWithPanel(false)->body([
                    Flex::make()->className('pb-4')->justify('end')->items([
                        UrlAction::make()
                            ->className('mr-5')
                            ->label('amis 可视化编辑器')
                            ->url('https://aisuda.github.io/amis-editor-demo/#/edit/0')
                            ->blank(true),
                        Html::make()->html('&emsp;'),
                        VanillaAction::make()->label('解析')->type('submit')->level('primary')->actionType('ajax'),
                    ]),
                    SwitchControl::make()->name('extract_namespace')->label('提取 namespace')->value(false),
                    EditorControl::make()
                        ->name('json')
                        ->label('amis json schema')
                        ->language('json')
                        ->allowFullscreen(false)
                        ->required(true),
                    EditorControl::make()->name('php')->label('php')->language('php')->allowFullscreen(false),
                    EditorControl::make()
                        ->name('namespace')
                        ->label('namespace')
                        ->language('php')
                        ->visibleOn('${namespace}')
                        ->allowFullscreen(false),
                ])
            ),
        ]);

        return $this->response()->success($page);
    }

    public function parse(Request $request)
    {
        $json = $request->input('json');

        if (!$this->isJson($json)) {
            return $this->response()->fail('无法解析，请检查 json 格式是否正确');
        }

        $parse     = Parse::make($request->input('extract_namespace'));
        $php       = $parse->transform(json_decode($json, true));
        $namespace = $parse->getNamespaces();

        return $this->response()->success(compact('php', 'namespace'));
    }

    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
