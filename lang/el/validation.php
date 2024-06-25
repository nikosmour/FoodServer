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

    'accepted' => 'Το :attribute πρέπει να γίνει αποδεκτό.',
    'accepted_if' => 'Το :attribute πρέπει να γίνει αποδεκτό όταν το :other είναι :value.',
    'active_url' => 'Το :attribute δεν είναι έγκυρο URL.',
    'after' => 'Το :attribute πρέπει να είναι ημερομηνία μετά την :date.',
    'after_or_equal' => 'Το :attribute πρέπει να είναι ημερομηνία μετά ή ίση με :date.',
    'alpha' => 'Το :attribute πρέπει να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το :attribute πρέπει να περιέχει μόνο γράμματα, αριθμούς, παύλες και κάτω παύλες.',
    'alpha_num' => 'Το :attribute πρέπει να περιέχει μόνο γράμματα και αριθμούς.',
    'array' => 'Το :attribute πρέπει να είναι ένας πίνακας.',
    'before' => 'Το :attribute πρέπει να είναι ημερομηνία πριν από :date.',
    'before_or_equal' => 'Το :attribute πρέπει να είναι ημερομηνία πριν ή ίση με :date.',
    'between' => [
        'numeric' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max.',
        'file' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρων.',
        'array' => 'Το :attribute πρέπει να έχει μεταξύ :min και :max στοιχείων.',
    ],
    'boolean' => 'Το πεδίο :attribute πρέπει να είναι true ή false.',
    'confirmed' => 'Η επιβεβαίωση :attribute δεν ταιριάζει.',
    'current_password' => 'Ο κωδικός πρόσβασης είναι εσφαλμένος.',
    'date' => 'Το :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_equals' => 'Το :attribute πρέπει να είναι ημερομηνία ίση με :date.',
    'date_format' => 'Το :attribute δεν ταιριάζει με τη μορφή :format.',
    'declined' => 'Το :attribute πρέπει να απορριφθεί.',
    'declined_if' => 'Το :attribute πρέπει να απορριφθεί όταν το :other είναι :value.',
    'different' => 'Το :attribute και το :other πρέπει να είναι διαφορετικά.',
    'digits' => 'Το :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max ψηφίων.',
    'dimensions' => 'Το :attribute έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct' => 'Το πεδίο :attribute έχει διπλότυπη τιμή.',
    'email' => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση email.',
    'ends_with' => 'Το :attribute πρέπει να τελειώνει με ένα από τα ακόλουθα: :values.',
    'enum' => 'Η επιλεγμένη τιμή για το πεδίο :attribute δεν είναι έγκυρη.',
    'exists' => 'Η επιλεγμένη τιμή για το πεδίο :attribute δεν είναι έγκυρη.',
    'file' => 'Το :attribute πρέπει να είναι ένα αρχείο.',
    'filled' => 'Το πεδίο :attribute πρέπει να έχει μια τιμή.',
    'gt' => [
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value.',
        'file' => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μεγαλύτερο από :value χαρακτήρες.',
        'array' => 'Το :attribute πρέπει να έχει περισσότερα από :value στοιχεία.',
    ],
    'gte' => [
        'numeric' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value.',
        'file' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μεγαλύτερο ή ίσο με :value χαρακτήρες.',
        'array' => 'Το :attribute πρέπει να έχει :value στοιχεία ή περισσότερα.',
    ],
    'image' => 'Το :attribute πρέπει να είναι μια εικόνα.',
    'in' => 'Η επιλεγμένη τιμή για το :attribute δεν είναι έγκυρη.',
    'in_array' => 'Το πεδίο :attribute δεν υπάρχει στο :other.',
    'integer' => 'Το :attribute πρέπει να είναι ένας ακέραιος αριθμός.',
    'ip' => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'ipv4' => 'Το :attribute πρέπει να είναι μια έγκυρη IPv4 διεύθυνση.',
    'ipv6' => 'Το :attribute πρέπει να είναι μια έγκυρη IPv6 διεύθυνση.',
    'json' => 'Το :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'lt' => [
        'numeric' => 'Το :attribute πρέπει να είναι μικρότερο από :value.',
        'file' => 'Το :attribute πρέπει να είναι μικρότερο από :value kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μικρότερο από :value χαρακτήρες.',
        'array' => 'Το :attribute πρέπει να έχει λιγότερα από :value στοιχεία.',
    ],
    'lte' => [
        'numeric' => 'Το :attribute πρέπει να είναι μικρότερο ή ίσο με :value.',
        'file' => 'Το :attribute πρέπει να είναι μικρότερο ή ίσο με :value kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι μικρότερο ή ίσο με :value χαρακτήρες.',
        'array' => 'Το :attribute δεν πρέπει να έχει περισσότερα από :value στοιχεία.',
    ],
    'mac_address' => 'Το :attribute πρέπει να είναι μια έγκυρη διεύθυνση MAC.',
    'max' => [
        'numeric' => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max.',
        'file' => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max kilobytes.',
        'string' => 'Το :attribute δεν πρέπει να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array' => 'Το :attribute δεν πρέπει να έχει περισσότερα από :max στοιχεία.',
    ],
    'mimes' => 'Το :attribute πρέπει να είναι ένα αρχείο τύπου: :values.',
    'mimetypes' => 'Το :attribute πρέπει να είναι ένα αρχείο τύπου: :values.',
    'min' => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον :min.',
        'file' => 'Το :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array' => 'Το :attribute πρέπει να έχει τουλάχιστον :min στοιχεία.',
    ],
    'multiple_of' => 'Το :attribute πρέπει να είναι πολλαπλάσιο του :value.',
    'not_in' => 'Η επιλεγμένη τιμή για το :attribute δεν είναι έγκυρη.',
    'not_regex' => 'Η μορφή του :attribute δεν είναι έγκυρη.',
    'numeric' => 'Το :attribute πρέπει να είναι ένας αριθμός.',
    'present' => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'prohibited' => 'Το πεδίο :attribute απαγορεύεται.',
    'prohibited_if' => 'Το πεδίο :attribute απαγορεύεται όταν το :other είναι :value.',
    'prohibited_unless' => 'Το πεδίο :attribute απαγορεύεται εκτός εάν το :other βρίσκεται στις :values.',
    'prohibits' => 'Το πεδίο :attribute απαγορεύει την παρουσία του :other.',
    'regex' => 'Η μορφή του :attribute δεν είναι έγκυρη.',
    'required' => 'Το πεδίο :attribute είναι υποχρεωτικό.',
    'required_array_keys' => 'Το πεδίο :attribute πρέπει να περιέχει καταχωρήσεις για: :values.',
    'required_if' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν το :other είναι :value.',
    'required_unless' => 'Το πεδίο :attribute είναι υποχρεωτικό εκτός εάν το :other βρίσκεται στις :values.',
    'required_with' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν υπάρχει το :values.',
    'required_with_all' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν υπάρχουν όλα τα :values.',
    'required_without' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν δεν υπάρχει το :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι υποχρεωτικό όταν κανένα από τα :values δεν υπάρχει.',
    'same' => 'Το :attribute και το :other πρέπει να ταιριάζουν.',
    'size' => [
        'numeric' => 'Το :attribute πρέπει να είναι :size.',
        'file' => 'Το :attribute πρέπει να είναι :size kilobytes.',
        'string' => 'Το :attribute πρέπει να είναι :size χαρακτήρες.',
        'array' => 'Το :attribute πρέπει να περιέχει :size στοιχεία.',
    ],
    'starts_with' => 'Το :attribute πρέπει να ξεκινά με ένα από τα ακόλουθα: :values.',
    'string' => 'Το :attribute πρέπει να είναι μια συμβολοσειρά.',
    'timezone' => 'Το :attribute πρέπει να είναι μια έγκυρη ζώνη ώρας.',
    'unique' => 'Η τιμή του :attribute έχει ήδη χρησιμοποιηθεί.',
    'uploaded' => 'Η μεταφόρτωση του :attribute απέτυχε.',
    'url' => 'Το :attribute πρέπει να είναι έγκυρο URL.',
    'uuid' => 'Το :attribute πρέπει να είναι έγκυρο UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------e------------------------
    |
    | Here you may specify custom validation messages for custom errors
    |
    */


    'not_active' => 'Η επιλεγμένη τιμή για το πεδίο :attribute  ανήκει σε μη ενεργό χρήστη.',
    'utlist_one_greater_than_zero' => 'Τουλάχιστον μια τιμή πρεπει να είναι μεγαλήτερη του 0',

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

    'attributes' => [
        'academic_id' => 'ακαδημαϊκή ταυτότητα',
        'receiver_id' => 'αναγνωριστικό παραλήπτη',
        'BREAKFAST' => 'πρωινό',
        'LUNCH' => 'μεσημεριανό',
        'DINNER' => 'βραδινό'
    ],

];
