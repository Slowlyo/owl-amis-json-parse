<?php

namespace Slowlyo\OwlAmisJsonParse\Support;

use Illuminate\Support\Arr;
use Slowlyo\OwlAdmin\Renderers\RendererMap;

class Parse
{
    protected bool $extractNamespace = false;

    protected array $namespaces = [];

    public static function make($extractNamespace = false)
    {
        return new static($extractNamespace);
    }

    public function __construct($extractNamespace = false)
    {
        $this->extractNamespace = $extractNamespace;
    }

    public function transform($json)
    {
        $code    = '';
        $map     = RendererMap::$map;
        $mapKeys = array_keys($map);

        if ($json['type'] ?? null) {
            if (in_array($json['type'], $mapKeys)) {
                // 组件
                if ($this->extractNamespace) {
                    // 提取 namespace
                    $this->namespaces[] = $map[$json['type']];
                    $className          = str_replace('Slowlyo\\OwlAdmin\\Renderers\\', '', $map[$json['type']]);
                    $code               .= sprintf('%s::make()', $className);
                } else {
                    $code .= sprintf('\\%s::make()', $map[$json['type']]);
                }
            } else {
                // 没找到对应的组件
                $code .= sprintf('amis(\'%s\')', $json['type']);
            }

            foreach ($json as $key => $value) {
                if ($key == 'type') {
                    continue;
                }
                // 属性
                if (is_array($value)) {
                    $code .= sprintf('->%s(%s)', $key, $this->transform($value));
                } else {
                    $code .= sprintf('->%s(\'%s\')', $key, $this->escape($value));
                }
            }
        } else {
            // 检查二维数组是否包含 type
            $codes = array_map(function ($value) {
                return Arr::get($value, 'type') ? $this->transform($value) : null;
            }, $json);

            // 过滤掉空值
            $codes = array_filter($codes);

            if ($codes) {
                // 使用 implode 函数来连接数组元素
                $code = '[' . implode(',', $codes) . ']';
            } else {
                // json 转 php 数组
                $code = '[';
                foreach ($json as $key => $value) {
                    if (is_array($value)) {
                        $code .= sprintf('\'%s\' => %s,', $key, $this->transform($value));
                    } else {
                        $code .= sprintf('\'%s\' => \'%s\',', $key, $this->escape($value));
                    }
                }
                $code .= ']';
            }
        }

        return $code;
    }

    public function getNamespaces()
    {
        $arr = array_map(function ($item) {
            return sprintf('use %s;', $item);
        }, $this->namespaces);

        return implode("\n", array_unique($arr));
    }

    /**
     * 转义单引号
     *
     * @param $string
     *
     * @return string|string[]
     */
    public function escape($string)
    {
        return str_replace("'", "\'", $string);
    }

    // 格式化php代码
}
