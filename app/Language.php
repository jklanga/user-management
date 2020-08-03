<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Language extends Model
{
    public $validator;

    /**
     * @param array $input
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(array $input)
    {
        $rules = [
            'language' 	=> 'required'
        ];

        return Validator::make($input, $rules);
    }
}
