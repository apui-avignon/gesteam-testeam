<?php
$configs = include('config.php');
define('ROOT', $configs['root']);
define('APP_NAME', $configs['app_name']);

require_once(ROOT . 'app/Controller.php');
require_once(ROOT . 'app/Model.php');

class SaveCard extends Controller
{
    public function cron()
    {
        $configs = include('config.php');
        // Create red card and send a mail to the teacher
        require(ROOT . 'helper/PHPMailer/src/PHPMailer.php');
        require(ROOT . 'helper/PHPMailer/src/SMTP.php');
        $this->loadModel("CourseParameters");
        $courses = $this->CourseParameters->getAll();
        // For all courses, check if the day of evaluation is today (monday)
        foreach ($courses as $course) :
            $this->loadHelper('Date');
            $evaluation_date = $this->Date->getDayOfEvaluation($course['start_date'], $course['period'], date('Y-m-d'));

            //For the test if it's not monday
            $day = "Monday";
            $date = date('Y-m-d');
            if (date('D', strtotime($date)) !== "Mon") {
                $date = date('Y-m-d', strtotime("last " . $day . ' ' . date('Y-m-d')));
            }

            if ($date == $evaluation_date) {
                $days = array();
                for ($i = 3; $i > 0; $i--) {
                    array_push($days,  date('Y-m-d', strtotime('-' . (7 * $course['period'] * ($i - 1)) . 'day', strtotime($evaluation_date))));
                }

                $this->loadModel('Appreciation');
                $yellow_cards_users = $this->Appreciation->findYellowCard($course['id_course'], $evaluation_date);

                // Special count of yellow cards to determine if it triggers a red card
                if (!empty($yellow_cards_users)) {
                    foreach ($yellow_cards_users as $yellow_cards_user) :
                        $this->loadModel('Card');
                        for ($i = 0; $i < $yellow_cards_user['COUNT(*)']; $i++) {
                            $this->Card->insertYellow($course['id_course'], $evaluation_date, $yellow_cards_user['evaluated_student']);
                        }

                        $yellow_cards =  $this->Card->yellowHistory($yellow_cards_user['evaluated_student'], $course['id_course'], $days[0], $evaluation_date);
                        $cpt = 0;
                        $cpt_yellow_card = 0;
                        for ($i = 2; $i >= 0; $i--) {
                            if (isset($yellow_cards[$cpt]) && ($days[$i] == $yellow_cards[$cpt]['date'])) {
                                if (($i == 0 || $i == 2) && empty($yellow_cards[$cpt]['deactivation_date'])) {
                                    $cpt_yellow_card += $yellow_cards[$cpt]['COUNT(*)'];
                                } else if ($i == 1) {
                                    if (!empty($yellow_cards[$cpt]['deactivation_date']) && $yellow_cards[$cpt]['COUNT(*)'] >= $course['threshold_red_card']) {
                                        $cpt_yellow_card += $course['threshold_red_card'] - 1;
                                    } else {
                                        $cpt_yellow_card += $yellow_cards[$cpt]['COUNT(*)'];
                                    }
                                }
                                $cpt++;
                            }
                        }

                        // Create a red card
                        if ($cpt_yellow_card >= $course['threshold_red_card']) {
                            $this->Card->insertRed($course['id_course'], $evaluation_date, $yellow_cards_user['evaluated_student']);
                        }
                    endforeach;
                }
            
                $red_cards_users = $this->Card->redByCourse($course['id_course'], $evaluation_date);

                if (count($red_cards_users) != 0) {
                    $this->loadModel('Course');
                    $course_parameters = $this->Course->findById($course['id_course']);
                    $this->loadModel('User');
                    $teacher_identity = $this->User->identity($course_parameters['username']);

                    $message = '<html><body>';
                    $message .= 'Bonjour,<br> Vous recevez ce message dans le cadre de l\'application TesTeam.<br><br>';
                    $message .= 'Nous vous informons que les étudiants suivant ont reçu un <strong style="color:#FF0000";> carton rouge</strong> dans votre cours "<strong>' . $course_parameters['course'] . '</strong>" :<br>';

                    foreach ($red_cards_users as $red_cards_user) {
                        $message .= '- ' . $red_cards_user['firstname'] . ' ' . $red_cards_user['lastname'] . '<br>';
                    }

                    $message .= '<br><br>Vous pouvez accéder à l\'application en cliquant <a href="https://' . $configs['server_name'] . '/' . $configs['app_name'] . '/index.php">ici</a><br><br> ';
                    $message .= 'Si vous rencontrer un problème, veuillez nous contacter à '.$configs['email_sender'];
                    $message .= '</body></html>';



                    $subject = "Vous avez une nouvelle notification";

                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->Host = $configs['host_email'];
                    $mail->SMTPAuth   = false;
                    $mail->Port = 25; // Par défaut
                    $mail->CharSet = 'UTF-8';

                    // Expéditeur
                    $mail->SetFrom($configs['email_sender'], 'Application TesTeam');
                    // Destinataire
                    $mail->AddAddress($teacher_identity['firstname'] . '.' . $teacher_identity['lastname'] . $configs['email_suffix_teacher'], $teacher_identity['firstname'] . ' ' . $teacher_identity['lastname']);
                    // Objet
                    $mail->Subject = $subject;

                    // Votre message
                    $mail->MsgHTML($message);

                    // Envoi du mail avec gestion des erreurs
                    if (!$mail->Send()) {
                        echo 'Erreur : ' . $mail->ErrorInfo;
                    }
                }
            }
        endforeach;
    }
}
$save = new SaveCard();
$save->cron();
