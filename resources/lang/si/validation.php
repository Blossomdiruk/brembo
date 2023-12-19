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

    'accepted' => ':attribute පිළිගත යුතුය.',
    'accepted_if' => ':other :value විටදී :attribute පිළිගත යුතුය.',
    'active_url' => ':attribute වලංගු URL නොවේ.',
    'after' => ':date පසුව යෙදෙන :attribute දිනයකි.',
    'after_or_equal' => ':date පසුව හෝ සමානව :attribute දිනයක් විය යුතුය.',
    'alpha' => ':attribute අඩංගු විය යුත්තේ අකුරු පමණි.',
    'alpha_dash' => ':attribute අඩංගු විය යුත්තේ අකුරු, ඉලක්කම්, ඩෑෂ් සහ අන්ඩර්ස්කෝර් පමණි.',
    'alpha_num' => ':attribute අඩංගු විය යුත්තේ අකුරු හා ඉලක්කම් පමණි.',
    'array' => ':attribute පිළිවෙලට තිබිය යුතුය.',
    'before' => ':date පෙර යෙදෙන :attribute  දිනයක් විය යුතුය.',
    'before_or_equal' => ':date හෝ සමාන දෙයකට පෙර යෙදෙන :attribute දිනයක් විය යුතුය.',
    'between' => [
        'numeric' => ':attribute :min සහ :max අතර විය යුතුය.',
        'file' => ':attribute :min සහ :max කිලෝබයිට් අතර විය යුතුය.',
        'string' => ':attribute :min සහ :max අක්ෂර අතර විය යුතුය.',
        'array' => ':attribute :min සහ :max අයිතම අතර විය යුතුය.',
    ],
    'boolean' => ':attribute ක්ෂේත්‍රය සත්‍ය හෝ අසත්‍ය විය යුතුය.',
    'confirmed' => ':attribute තහවුරු කිරීම නොගැලපේ.',
    'current_password' => 'පාස්වර්ඩ් වැරදිය.',
    'date' => ':attribute වලංගු දිනයක් නොවේ.',
    'date_equals' => ':attribute :date හා සමාන දිනයක් විය යුතුය.',
    'date_format' => ':attribute :format ආකෘතිය සමග සැසඳෙන්නේ නැත.',
    'different' => ':attribute සහ :other වෙනස් විය යුතුය.',
    'digits' => ':attribute :digits ඉලක්කම් විය යුතුය.',
    'digits_between' => ':attribute :min සහ :max ඉලක්කම් අතර විය යුතුය.',
    'dimensions' => ':attribute රූප පරිමාණයන් වලංගු නැත.',
    'distinct' => ':attribute ක්ෂේත්‍රයෙහි නැවත යෙදෙන වටිනාකමක් ඇත.',
    'email' => ':attribute වලංගු ඊමේල් ලිපිනයක් විය යුතුය.',
    'ends_with' => ':attribute පහත දැක්වෙන :values එකකින් අවසන් විය යුතුය.',
    'exists' => 'තෝරාගන්නා ලද :attribute වලංගු නැත.',
    'file' => ':attribute ෆයිල් එකක් විය යුතුය.',
    'filled' => ':attribute ක්ෂේත්‍රයෙහි වටිනාකමක් තිබිය යුතුය.',
    'gt' => [
        'numeric' => ':attribute :valueට වඩා වැඩි විය යුතුය.',
        'file' => ':attribute :value කිලෝබයිට් ගණනට වඩා වැඩි විය යුතුය.',
        'string' => ':attribute :value අක්ෂර ගණනට වඩා වැඩි විය යුතුය.',
        'array' => ':attribute සතුව :value අයිතමයන්ට වඩා තිබිය යුතුය.',
    ],
    'gte' => [
        'numeric' => ':attribute :valueට වඩා විශාල හෝ සමාන විය යුතුය.',
        'file' => ':attribute :value කිලෝබයිට් ගණනට වඩා විශාල හෝ සමාන විය යුතුය.',
        'string' => ':attribute :value අක්ෂර ගණනට වඩා විශාල හෝ සමාන විය යුතුය.',
        'array' => ':attribute සතුව :value අයිතම හෝ ඊට වඩා තිබිය යුතුය.',
    ],	

    'image' => ':attribute must be an image.',
    'in' => 'තෝරාගත් :attribute වලංගු නැත.',
    'in_array' => ':attribute ක්ෂේත්‍රය :other හි නැත.',
    'integer' => ':attribute පූර්ණ සංඛ්‍යාවක් විය යුතුය.',
    'ip' => ':attribute වලංගු IP ලිපිනයක් විය යුතුය.',
    'ipv4' => ':attribute වලංගු IPv4 ලිපිනයක් විය යුතුය.',
    'ipv6' => ':attribute වලංගු IPv6 ලිපිනයක් විය යුතුය.',
    'json' => ':attribute වලංගු JSON string විය යුතුය.',
    'lt' => [
        'numeric' => ':attribute :value ට වඩා අඩු විය යුතුය.',
        'file' => ':attribute :value කිලෝබයිට් ගණනට වඩා අඩු විය යුතුය.',
        'string' => ':attribute n :value අක්ෂර ගණනට වඩා අඩු විය යුතුය.',
        'array' => ':attribute සතුව :value අයිතමයන්ට වඩා අඩුවෙන් තිබිය යුතුය.',
    ],
    'lte' => [
        'numeric' => ':attribute :valueට වඩා අඩු හෝ සමාන විය යුතුය.',
        'file' => ':attribute :value කිලෝබයිට් ගණනට වඩා අඩු හෝ සමාන විය යුතුය.',
        'string' => ':attribute :value අක්ෂර ගණනට වඩා අඩු හෝ සමාන විය යුතුය.',
        'array' => ':attribute සතුව :value අයිතමයන්ට වඩා නොතිබිය යුතුය.',
    ],
    'max' => [
        'numeric' => ':attribute :max ට වඩා විශාල නොවිය යුතුය.',
        'file' => ':attribute :max කිලෝබයිට් ගණනට වඩා විශාල නොවිය යුතුය.',
        'string' => ':attribute :max අක්ෂර ගණනට වඩා විශාල නොවිය යුතුය.',
        'array' => ':attribute සතුව :max අයිතමයන්ට වඩා නොතිබිය යුතුය.',
    ],	
    'mimes' => ':attribute type: :values ෆයිල් එකක් විය යුතුය.',
    'mimetypes' => ':attribute type: :values ෆයිල් එකක් විය යුතුය.',
    'min' => [
        'numeric' => ':attribute අඩු වශයෙන් :min විය යුතුය.',
        'file' => ':attribute අඩු වශයෙන් :min කිලෝබයිට් විය යුතුය.',
        'string' => ':attribute අඩු වශයෙන් :min අක්ෂර විය යුතුය.',
        'array' => ':attribute අඩු වශයෙන් :min අයිතම විය යුතුය.',
    ],
    'multiple_of' => ':attribute :valueහි ගුණාකාරයක් විය යුතුය.',
    'not_in' => 'තෝරන ලද :attribute වලංගු නැත.',
    'not_regex' => ':attribute ආකෘතිය වලංගු නැත.',
    'numeric' => ':attribute අංකයක් විය යුතුය.',
    'password' => '	පාස්වර්ඩ් වැරදිය.',
    'present' => ':attribute ක්ෂේත්‍රය තිබිය යුතුය.',
    'regex' => ':attribute ආකෘතිය වලංගු නැත.',
    'required' => ':attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_if' => ':other :value විටදී :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_unless' => ':other :values නොවේ නම් :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_with' => ':values තිබේ නම් :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_with_all' => ':values තිබේ නම් :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_without' => ':values නොමැති නම් :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'required_without_all' => ':values කිසිවක් නොමැති නම් :attribute ක්ෂේත්‍රය අවශ්‍යය.',
    'prohibited' => ':attribute ක්ෂේත්‍රය තහනම්ය.',
    'prohibited_if' => ':other :value වන විටදී :attribute ක්ෂේත්‍රය තහනම්ය.',
    'prohibited_unless' => ':other :values මගින් නොවේ නම් :attribute ක්ෂේත්‍රය තහනම්ය.',
    'prohibits' => ':other පැවැත්ම මගින් වන විට :attribute ක්ෂේත්‍රය තහනම්ය.',
    'same' => ':attribute සහ :other සැසඳිය යුතුය.',
    'size' => [
        'numeric' => ':attribute :size විය යුතුය.',
        'file' => ':attribute :size කිලෝබයිට් විය යුතුය.',
        'string' => ':attribute :size අක්ෂර විය යුතුය.',
        'array' => ':attribute තුළ :size අයිතමයන් අඩංගු විය යුතුය.',
    ],
    'starts_with' => ':attribute පහත දැක්වෙන එක් :values මගින් ආරම්භ විය යුතුය.',
    'string' => ':attribute string එකක් විය යුතුය.',
    'timezone' => ':attribute වලංගු කාල කලාපයක් විය යුතුය.',
    'unique' => ':attribute දැනටමත් ලබාගෙන තිබේ.',
    'uploaded' => ':attribute අප්ලෝඩ් වන්නේ නැත.',
    'url' => ':attribute වලංගු URL එකක් විය යුතුය.',
    'uuid' => ':attribute වලංගු UUID විය යුතුය.',

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
