<?php
namespace App\Controller;

use App\Entity\User;
use DateTime;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;
use function App\validate;

class Registration{
    public function __invoke()
    {
        startSession();
        $lang = getTextLangue($_SESSION["locale"]);

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
                        'errorMessage' => $lang["REGISTRATION"]["PASSWORD2"],
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
                        'errorMessage' => $lang["REGISTRATION"]["MAILUNIQUE"],
                    ],
                ]
            ] ,
            'first_name' => [
                [
                    'rule' => 'required',
                ],
            ] ,
            'last_name' => [
                [
                    'rule' => 'required',
                ],
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
                        'errorMessage' => $lang["REGISTRATION"]["ERRORTOS"]
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
            $new_user->setFirstName(trim($user["first_name"]));
            $new_user->setLastName(trim($user["last_name"]));
            $new_user->setNbStreet(trim($user["street_number"]));
            $new_user->setStreet(trim($user["street"]));
            $new_user->setCity(trim($user["city"]));
            $new_user->setPostcode(trim($user["postcode"]));
            $new_user->setBirthDate($birthdate);

            $em->persist($new_user);
            $em->flush();
        }

        $errors = [];
        $user = $_POST;

        if (count($_POST) > 0) {
            if(!isset($_POST["tos"])){
                $_POST["tos"] = '';
            }
            $formErrors = validate($_POST, $rules, $language);

            // the form is valid
            if (empty($formErrors)) {
                unset($user["tos"], $user['password2']);
                registerUser($user);
                // unset local variable for new user

                ?>
                <script type="text/javascript">
                    localStorage.removeItem('email')
                    localStorage.removeItem('password')
                    document.cookie="email=";
                    document.cookie="password=";
                    window.location.href = "/login";
                </script>
                <?php
                die;
//                header('Location: /login');
//                die;
            }
            else{
                $errors = $formErrors;
            }
        }

        return new Response('registration.html.twig', ['lang' => $lang, "errors" => $errors, 'user' => $user]);
    }
}

