<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute musí byť akceptovaný.',
    'active_url'           => ':attribute nie je platná URL adresa.',
    'after'                => ':attribute musí byť dátum po :date.',
    'alpha'                => ':attribute môže obsahovať len písmená.',
    'alpha_dash'           => ':attribute môže obsahovať len písmená, čísla a pomlčky.',
    'alpha_num'            => ':attribute môže obsahovať len písmená a čísla.',
    'array'                => ':attribute musí byť pole.',
    'before'               => ':attribute musí byť dátum pred :date.',
    'between'              => [
        'numeric' => ':attribute musí mať rozsah :min až :max.',
        'file'    => ':attribute musí mať rozsah :min až :max kilobajtov.',
        'string'  => ':attribute musí mať rozsah :min až :max znakov.',
        'array'   => ':attribute musí mať rozsah :min až :max prvkov.',
    ],
    'boolean'              => ':attribute musí byť true alebo false.',
    'confirmed'            => ':attribute sa nezhoduje.',
    'date'                 => ':attribute nie je platný dátum.',
    'date_format'          => ':attribute sa nezhoduje s formátom :format.',
    'different'            => ':attribute a :other musia byť odlišné.',
    'digits'               => ':attribute musí mať :digits číslic.',
    'digits_between'       => ':attribute musí mať rozsah :min až :max číslic.',
    'email'                => ':attribute má neplatný formát.',
    'filled'               => ':attribute musí byť vyplnený.',
    'exists'               => 'označený :attribute je neplatný.',
    'image'                => ':attribute musí byť obrázok.',
    'in'                   => 'označený :attribute je neplatný.',
    'integer'              => ':attribute musí byť celé číslo.',
    'ip'                   => ':attribute musí byť platná IP adresa.',
    'max'                  => [
        'numeric' => ':attribute nemôže byť väčší ako :max.',
        'file'    => ':attribute nemôže byť väčší ako :max kilobajtov.',
        'string'  => ':attribute nemôže mať viac ako :max znakov.',
        'array'   => ':attribute nemôže obsahovať viac ako :max prvkov.',
    ],
    'mimes'                => ':attribute musí byť súbor s koncovkou: :values.',
    'min'                  => [
        'numeric' => ':attribute nemôže byť menší ako :min.',
        'file'    => ':attribute musí mať aspoň :min kilobajtov.',
        'string'  => ':attribute musí mať aspoň :min znakov.',
        'array'   => ':attribute musí obsahovať aspoň :min prvkov.',
    ],
    'not_in'               => 'označený :attribute je neplatný.',
    'numeric'              => ':attribute musí byť číslo.',
    'regex'                => ':attribute má neplatný formát.',
    'required'             => ':attribute musí byť vyplnený.',
    'required_if'          => ':attribute musí byť vyplnený, keď :other je :value.',
    'required_with'        => ':attribute musí byť vyplnený, keď :values je vyplnený.',
    'required_with_all'    => ':attribute musí byť vyplnený, keď :values sú vyplnené.',
    'required_without'     => ':attribute musí byť vyplnený, keď :values nie je vyplnený.',
    'required_without_all' => ':attribute musí byť vyplnený, keď ani jeden z :values nie sú vyplnené.',
    'same'                 => ':attribute a :other sa musia zhodovať.',
    'size'                 => [
        'numeric' => ':attribute musí byť :size.',
        'file'    => ':attribute musí mať :size kilobajtov.',
        'string'  => ':attribute musí mať :size znakov.',
        'array'   => ':attribute musí obsahovať :size prvkov.',
    ],
    'string'               => ':attribute musí byť reťazec.',
    'timezone'             => ':attribute musí byť platná časová zóna.',
    'unique'               => ':attribute už existuje.',
    'url'                  => ':attribute má neplatný formát.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'name'    => [
            'unique' => ':attribute už niekto používa',
            'required'     => ':attribute musí byť vyplnené.',
        ],
        'email'   => [
            'required'     => ':attribute musí byť vyplnená.',
            'unique'       => 'E-mailovú adresu už niekto používa.',
        ],
        'password' => [
            'required'     => ':attribute musí byť vyplnené.',
            'confirmed'    => 'Heslá sa nezhodujú.',
        ],
        'role'   => [
            'required'     => ':attribute musí byť vyplnená.',
        ],
        'address'   => [
            'required'     => ':attribute musí byť vyplnená.',
        ],
        'description'   => [
            'required'     => ':attribute musí byť vyplnený.',
        ],
        'rows'   => [
            'required'     => ':attribute musí byť vyplnený.',
        ],
        'cols'   => [
            'required'     => ':attribute musí byť vyplnený.',
        ],
        'show_types'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'tags'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'performers'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'hall'   => [
            'required'     => ':attribute musí byť vybratá.',
        ],
        'show'   => [
            'required'     => ':attribute musí byť vybratá.',
        ],
        'date'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'start_time'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'end_time'   => [
            'required'     => ':attribute musí byť vybratý.',
        ],
        'price'   => [
            'required'     => ':attribute musí byť vyplnená.',
        ],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'             => 'Meno',
        'email'            => 'E-mailová adresa',
        'password'         => 'Heslo',
        'role'             => 'Rola',
        'address'          => 'Adresa',
        'description'      => 'Popis',
        'rows'             => 'Rad',
        'columns'          => 'Stľpec',
        'show_types'       => 'Typ predstavenia',
        'tags'             => 'Žáner',
        'performers'       => 'Účinkujúci',
        'hall'             => 'Sála',
        'show'             => 'Udalosť',
        'date'             => 'Dátum',
        'start_time'       => 'Čas začiatku',
        'end_time'         => 'Čas konca',
        'price'            => 'Cena lístka',
    ],

];