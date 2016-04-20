<?php

namespace App\Transformers;


class TaskTransformer extends Transformer {
    public function transform($item){
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'priority' => $item['priority'],
            'done' => (boolean)$item['done'],
        ];
    }
}