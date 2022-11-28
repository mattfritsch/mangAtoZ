<?php
$formErrors = [];

$rules = [
    'password' => [
        [
          'rule' => 'required',
        ],

        [
          'rule' => 'length',
          'args' => [
            'max' => 50,
          ]
        ],
    ] ,
    'password2' => [
        [
          'rule' => 'sameAs',
          'args' => [
            'field' => 'password',
            'errorMessage' => 'Le mot de passe doit être identique !',
          ]
        ],
    ] ,
    'email' => [
        [
          'rule' => 'required',
        ],
        [
          'rule' => 'length',
          'args' => [
            'max' => 100,
          ]
        ],
        [
            'rule' => 'uniq',
            'args' => [
                'callback' => 'isUserUniq',
                'errorMessage' => 'Cette adresse mail est déjà utilisé !',
            ],
        ]
    ] ,
    'nb_street' => [
        [
            'rule' => 'required',
        ],
    ] ,
    'street' => [
        [
          'rule' => 'required',
        ],

        [
          'rule' => 'length',
          'args' => [
            'max' => 50,
          ]
        ],
    ] ,
    'city' => [
        [
            'rule' => 'required',
        ],

        [
            'rule' => 'length',
            'args' => [
                'max' => 50,
            ]
        ],
    ] ,
    'postcode' => [
        [
            'rule' => 'required',
        ],

        [
            'rule' => 'length',
            'args' => [
                'max' => 5,
            ]
        ],
    ] ,
    'tos' => [
        [
          'rule' => 'checkbox',
          'args' => [
            'checked' => true,
            'errorMessage' => 'Merci d\'accepter les CGU !'
          ]
        ],
    ] ,
];

if (count($_POST) > 0) {
  $formErrors = validate($_POST, $rules);

  // the form is valid
  if (empty($formErrors)) {
    $user = $_POST;
    unset($user['tos'], $user['password2']);
    $registered = registerUser($user);

    if ($registered) {
      header('Location: /login.php');
      die;
    }
  }
}


