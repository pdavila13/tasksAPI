<?php

namespace App\Transformers;

class TagTransformer extends Transformer {
    public function transform($item){
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'tran' => (boolean)$item['tran'],
        ];
    }
}