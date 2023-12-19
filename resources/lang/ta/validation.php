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

    'accepted' => ':attribute ஏற்றுக்கொள்ளப்பட வேண்டும்.',
    'accepted_if' => ':attribute ஏற்றுக்கொள்ளப்பட வேண்டும் :other is :value.',
    'active_url' => ':attribute சரியான URL அல்ல.',
    'after' => ':attribute பின்னரான திகதியாக அமைய வேண்டும் :date.',
    'array' => ':attribute ஒரு வரிசையாக இருக்க வேண்டும்.',
    'after_or_equal' => ':attribute என்பது பின்னரான அல்லது சமமான திகதியாக இருக்க வேண்டும் :date.',
    'alpha' => ':attribute எழுத்துக்களை மட்டும் உள்ளடக்க வேண்டும்.',
    'alpha_dash' => ':attribute எழுத்துக்கள், எண்கள், கோடுகள் மற்றும் அடிக்கோடுகளை மட்டும் உள்ளடக்க வேண்டும்.',
    'arralpha_numay' => ':attribute எழுத்துக்களையும் எண்களையும் மட்டும் உள்ளடக்க வேண்டும்.',
    'before' => ':attribute முந்தைய திகதியாக அமைய வேண்டும் : date.',
    'before_or_equal' => ':attribute என்பது முந்தைய அல்லது சமமான திகதியாக இருக்க வேண்டும் :date.',
    'between' => [
        'numeric' => ':attribute : mini மற்றும் :max இற்கு இடையில் இருக்க வேண்டும்.',
        'file' => ':attribute : mini மற்றும் :max கிலோபைற்றிற்கு இடையில் இருக்க வேண்டும்.',
        'string' => ':attribute : mini மற்றும் :max எழுத்துக்களுக்கு இடையில் இருக்க வேண்டும்.',
        'array' => ':attribute : mini மற்றும் :max விடயங்களுக்கு இடையில் இருக்க வேண்டும்.',
    ],
    'boolean' => ':attribute பகுதி உண்மையாகவோ பொய்யாகவோ இருக்க வேண்டும்.',
    'confirmed' => 'T:attribute உறுதிப்படுத்தல் பொருந்தவில்லை.',
    'current_password' => 'கடவுச்சொல் பிழையானது.',
    'date' => ':attribute சரியான திகதி அல்ல.',
    'date_equals' => ':attribute  :date மற்றும் அதற்கு சமமாக இருக்க வேண்டும்.',
    'date_format' => ':attribute வடிவமைப்புடன் பொருந்தவில்லை  :format.',
    'different' => ':attribute மற்றும் :other வித்தியாசமானதாக இருக்க வேண்டும்.',
    'digits' => ':attribute :digits இலக்கமாக இருக்க வேண்டும்.',
    'digits_between' => ':attribute : mini மற்றும் :max இலக்கங்களுக்கு இடையில் இருக்க வேண்டும்.',
    'dimensions' => ':attribute தவறான பட பரிமாணங்களைக் கொண்டுள்ளது.',
    'distinct' => ':attribute இடம் நகல் பெறுமதியைக் கொண்டது.',
    'email' => ':attribute சரியான மின்னஞ்சல் முகவரியாக இருக்க வேண்டும்.',
    'ends_with' => ':attribute பின்வருவனவற்றில் ஒன்றில் முடிவடைய வேண்டும்: values.',
    'exists' => 'தெரிவுசெய்யப்பட்ட :attribute தவறானது.',
    'file' => ':attribute ஒரு கோப்பாக இருக்க வேண்டும்.',
    'filled' => ':attribute பகுதிக்கு பெறுமதி காணப்பட வேண்டும்.',
    'gt' => [
        'numeric' => ':attribute :valueவை விட பெரிதாக இருக்க வேண்டும்.',
        'file' => ':attribute :value கிலோபைற்றை விட  பெரிதாக இருக்க வேண்டும்.',
        'string' => ':attribute :value எழுத்துக்களை விட  பெரிதாக இருக்க வேண்டும்.',
        'array' => ' :attribut :value விடயங்களுக்கு அதிகமாக இருக்க வேண்டும்.',
    ],
    'gte' => [
        'numeric' => ':attribute :valueவை விட  பெரிதாக அல்லது சமமாக இருக்க வேண்டும்.',
        'file' => ':attribute :value கிலோபைற்றை விட  பெரிதாகவோ சமமாகவோ இருக்க வேண்டும்.',
        'string' => ':attribute :value எழுத்துக்களை விட  பெரிதாகவோ சமமாகவோ இருக்க வேண்டும்.',
        'array' => ':attribute :value விடயங்கள் அல்லது அதற்கு மேல் இருக்க வேண்டும்.',
    ],
    'image' => ':attribute ஒரு படமாக இருக்க வேண்டும்.',
    'in' => 'தெரிவுசெய்யப்பட்ட :attribute தவறானது.',
    'in_array' => ':attribute பகுதி :other இல் இல்லை.',
    'integer' => ':attribute முழுமையான எண்ணாக அமைய வேண்டும்.',
    'ip' => ':attribute செல்லுபடியாகும் IP முகவரியாக இருக்க  வேண்டும்.',
    'ipv4' => ':attribute செல்லுபடியாகும் IPv4 முகவரியாக இருக்க  வேண்டும்.',
    'ipv6' => ':attribute செல்லுபடியாகும் IPv6 முகவரியாக இருக்க வேண்டும்.',
    'json' => ':attribute செல்லுபடியாகும் IPv6 JSON stringஆக இருக்க வேண்டும்.',
    'lt' => [
        'numeric' => ':attribute :valueஇற்கு குறைவாக இருக்க வேண்டும்.',
        'file' => ':attribute :value கிலோபைற்றிற்கு குறைவாக இருக்க வேண்டும்.',
        'string' => ':attribute :value எழுத்துக்களுக்கு குறைவாக இருக்க வேண்டும்.',
        'array' => ':attribute :value விடயங்களுக்கு குறைவாக இருக்க வேண்டும்.',
    ],
    'lte' => [
        'numeric' => ':attribute :value இற்கு குறைவாக அல்லது சமமாக இருக்க வேண்டும்.',
        'file' => ':attribute :value கிலோபைற்றிற்கு குறைவாக அல்லது சமமாக இருக்க வேண்டும்.',
        'string' => ':attribut :value எழுத்துக்களுக்கு குறைவாக அல்லது சமமாக இருக்க வேண்டும்.',
        'array' => ':attribut :value விடயங்களுக்கு அதிகமாக இருக்கக் கூடாது.',
    ],
    'max' => [
        'numeric' => ':attribute :maxஐ விட பெரிதாக இருக்கக் கூடாது.',
        'file' => ':attribute :max கிலோபைற்றிற்றை விட பெரிதாக இருக்கக்கூடாது.',
        'string' => ':attribute :max எழுத்துக்களை விட பெரிதாக இருக்கக்கூடாது.',
        'array' => ':attribute :max விடயங்களை விட அதிகமாக இருக்கக் கூடாது.',
    ],
    'mimes' => ':attribute என்பது :values கோப்பாக இருக்க வேண்டும்.',
    'mimetypes' => ':attribute என்பது :values கோப்பாக இருக்க வேண்டும்.',
    'min' => [
        'numeric' => ':attribute குறைந்தபட்சம் :min ஆக இருக்க வேண்டும்.',
        'file' => ':attribute குறைந்தபட்சம் :min கிலோபைற்றாக இருக்க வேண்டும்.',
        'string' => ':attribute குறைந்தபட்சம்  :min எழுத்துக்களாக இருக்க வேண்டும்.',
        'array' => ':attribute குறைந்தபட்சம் :min விடயங்களைக் கொண்டிருக்க வேண்டும்.',
    ],
    'multiple_of' => ':attribute என்பது :valueஇன் பெருக்கமாக இருக்க வேண்டும்.',
    'not_in' => 'தெரிவுசெய்யப்பட்ட :attribute தவறானது.',
    'not_regex' => ':attribute வடிவம் தவறானது.',
    'numeric' => ':attribute இலக்கமாக இருக்க வேண்டும்.',
    'password' => 'கடவுச்சொல் பிழையானது.',
    'present' => ':attribute பகுதி காணப்பட வேண்டும்.',
    'regex' => ':attribute வடிவம் தவறானது.',
    'required' => ':attribute பகுதி அவசியமானது.',
    'required_if' => ':other :value எப்போதும் :attribute பகுதி அவசியமானது.',
    'required_unless' => ':other :values இல்லையென்றால் :attribute பகுதி அவசியமானது.',
    'required_with' => ':values இருந்தால் :attribute  பகுதி அவசியமானது.',
    'required_with_all' => ':values இருந்தால் :attribute  பகுதி அவசியமானது.',
    'required_without' => ':values இல்லாவிட்டால் :attribute  பகுதி அவசியமானது.',
    'required_without_all' => ':values எதுவும் இல்லையென்றால் :attribute  பகுதி அவசியமானது.',
    'prohibited' => ':attribute பகுதி தடைசெய்யப்பட்டுள்ளது.',
    'prohibited_if' => ':other :value இருக்கும்போது :attribute பகுதி தடைசெய்யப்பட்டுள்ளது.',
    'prohibited_unless' => ':other :values இல்லையென்றால் :attribute பகுதி தடைசெய்யப்பட்டுள்ளது.',
    'prohibits' => ':other இருக்கும்போது :attribute பகுதி தடைசெய்யப்பட்டுள்ளது.',
    'same' => ':attribute மற்றும் :other பொருந்தவேண்டும்.',
    'size' => [
        'numeric' => ':attribute :sizeஆக இருக்க வேண்டும்.',
        'file' => ':attribute :size கிலோபைற்றாக இருக்க வேண்டும்.',
        'string' => ':attribute :size எழுத்துக்களாக இருக்க வேண்டும்.',
        'array' => ':attribute இற்குள் :size விடயங்கள் இருக்க வேண்டும்.',
    ],
    'starts_with' => ':attribute கீழே காணப்படும் ஒரு :values இல் இருந்து தொடங்க வேண்டும்.',
    'string' => ':attribute string ஆக இருக்க வேண்டும்.',
    'timezone' => ':attribute சரியான நேர மண்டலமாக இருக்க வேண்டும்.',
    'unique' => ':attribute ஏற்கனவே பெறப்பட்டுள்ளது.',
    'uploaded' => ':attribute பதிவேற்றம் செய்ய முடியவில்லை.',
    'url' => ':attribute சரியான URL ஆக இருக்க வேண்டும்.',
    'uuid' => ':attribute சரியான UUID ஆக இருக்க வேண்டும்.',
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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
