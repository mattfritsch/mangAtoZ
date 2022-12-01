<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Framework\Doctrine\EntityManager;
use function App\validate;

class Register{
    public function __invoke(): void
    {
        $formErrors = [];

        $rules = [
            'password' => [
                [
                    'rule' => 'required',
                ],

                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 1000,
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
                        'callback' => 'isMailUniq',
                        'errorMessage' => 'Cette adresse mail est déjà utilisée !',
                    ],
                ]
            ] ,
            'street_number' => [
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

        function registerUser(array $user)
        {
            $em = EntityManager::getInstance();

            $birthdate = DateTime::createFromFormat('Y-m-j', $_POST['birthdate']);

            $new_user = new User();
            $new_user->setEmail(trim($user["email"]));
            $new_user->setPassword(password_hash($user["password"], PASSWORD_ARGON2I));
            $new_user->setAdmin(false);
            $new_user->setNbStreet(trim($user["street_number"]));
            $new_user->setStreet(trim($user["street"]));
            $new_user->setCity(trim($user["city"]));
            $new_user->setPostcode(trim($user["postcode"]));
            $new_user->setBirthDate($birthdate);

            $em->persist($new_user);
            $em->flush();
        }

        $_SESSION["errors"] = '';

        if (count($_POST) > 0) {
            $formErrors = validate($_POST, $rules);

            // the form is valid
            if (empty($formErrors)) {
                $user = $_POST;
                unset($user['tos'], $user['password2']);
                registerUser($user);
                header('Location: /login');
                die;
            }
            else{
                $_SESSION["errors"] = $formErrors;
                header('Location: /registration');
            }
        }
    }
}
