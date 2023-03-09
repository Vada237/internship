<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;

class BoardTemplateGetAllAction {
    public function handle($params) {
        return BoardTemplate::offset($params['offset'])->limit($params['limit'])->get();
    }
}
