<?php

namespace App\Http\Controllers;

class FormModulesController extends Controller
{
    public function selection($module)
    {
        if(request()->wantsJson()){
            return $this->successfulResponse([
                'selection' => $this->collection(
                    app($module)->selection(),
                    app('Transformer', [
                        'module' => $module,
                        'type' => 'selection'
                    ])
                )
            ]);
        }
    }
}
