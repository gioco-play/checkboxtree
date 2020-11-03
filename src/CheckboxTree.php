<?php

namespace GiocoPlus\CheckboxTree;

use Encore\Admin\Form\Field\MultipleSelect;

class CheckboxTree extends MultipleSelect
{

    protected $group_key = "slug";

    protected $text = "name";

    protected $key = "id";

    protected $slug = "slug";

    protected $_options = [];

    protected $view = 'laravel-admin-checkboxtree::checkboxtree';

    protected static $css = [
        '/vendor/laravel-admin-ext/checkboxtree/dist/bootree.css'
    ];

    protected static $js = [
        '/vendor/laravel-admin-ext/checkboxtree/dist/bootree.js'
    ];

    /**
     * 子選單
     *
     * @param [type] $group_key
     * @param array $options
     * @return void
     */
    protected function optionChildren($group_key, $options = [], $checked) {
        $chlidren = [];
        collect($options)->each(function ($item, $key)use(&$chlidren, $group_key, $checked) {
            if (stripos($item[$this->slug], '.') !== false && stripos($item[$this->slug], "$group_key.") !== false) {
                $group_key = $item[$this->group_key];
                $label = $item[$this->text];
                $key = $item[$this->key];
                $slug = $item[$this->slug];
                $checked = $checked ?? [];
                array_push($chlidren, [
                    'id' => $key,
                    'text' => $label,
                    'slug' => $slug,
                    'checked' => in_array(intval($key), $checked),
                    'children' => [],
                ]);
            }
        });

        return $chlidren;
    }

    /**
     * 選項
     *
     * @param $options
     * @return void
     */
    public function treeOptions($options){
        $this->_options = $options->toArray();
        return $this;
    }

    protected function treeData() {
        $treedata = [];
        $options = $this->_options;
        $checked = $this->value();
        array_map(function($item)use(&$treedata, $options, $checked){
            $group_key = $item[$this->group_key];
            $label = $item[$this->text];
            $key = $item[$this->key];
            $slug = $item[$this->slug];
            if (stripos($group_key, '.') === false) {
                $chlidren = $this->optionChildren($group_key, $options, $checked);
                $checked = $checked ?? [];
                array_push($treedata, [
                    'id' => $key,
                    'text' => $label,
                    'slug' => $slug,
                    'checked' => in_array(intval($key), $checked),
                    'children' => $chlidren
                ]);
            }
        }, $options);

        return $treedata;
    }

    public function render()
    {
        $this->addVariables([
            'options' => $this->options,
        ]);

        $data = json_encode($this->treeData());
        $this->script = <<<EOT
var myData = $data;
const tree{$this->id} = $('#{$this->id}_tree').tree({
    primaryKey: 'id',
    dataSource: myData,
    checkboxes: true
});

tree{$this->id}.change(function(e){
    var elements = document.getElementById("{$this->id}").options;
    for(var i = 0; i < elements.length; i++){
        elements[i].selected = false;
    }
    tree{$this->id}.getCheckedNodes().forEach(function(selIdx){
        $('select[name="{$this->id}[]"]').find("[value="+selIdx+"]")[0].selected = 'selected';
    });
});

EOT;
        return parent::render();
    }
}
