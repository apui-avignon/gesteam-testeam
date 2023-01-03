<?php
class Weekly_recap extends ComponentController
{
    public function index($send = 0)
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->loadModel('CourseGroup');
            $course_groups = $this->CourseGroup->findById($_POST['course_id']);
            $course_groups_more_informations = $this->CourseGroup->findByIdMoreInformations($_POST['course_id']);

            // Calculation last evaluation date and next evaluation date
            $this->loadHelper('Date');
            $evaluation_date = $this->Date->getDayOfEvaluation($course_parameters['start_date'], $course_parameters['period'], date('Y-m-d'));
            $next_evaluation_date = $this->Date->whatDate($evaluation_date, '+', $course_parameters['period'] * 7);

            // Appreciation for the last evaluation
            $this->loadModel('Appreciation');
            $course_appreciations = $this->Appreciation->findByDate($evaluation_date, $_POST['course_id']);
            $voted = $this->Appreciation->voted($evaluation_date, $_POST['course_id']);
            $current_votes =  $this->Appreciation->voted($next_evaluation_date, $_POST['course_id']);

            $this->loadModel('Card');
            $red_cards = $this->Card->findByColor($_POST['course_id'], $evaluation_date, "red");

            $configs = include('config.php');            

            $this->render('index',  compact('course_parameters', 'course_groups', 'course_appreciations', 'course_groups_more_informations', 'voted', 'red_cards', 'current_votes', 'send', 'configs'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function emailReminder()
    {
        if (isset($_POST['course_id'])) {
            // Send a reminder to all the students who did not vote

            $configs = include('config.php');

            require(ROOT . '/helper/PHPMailer/src/PHPMailer.php');
            require(ROOT . '/helper/PHPMailer/src/SMTP.php');

            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->loadHelper('Date');
            $evaluation_date = $this->Date->getDayOfEvaluation($course_parameters['start_date'], $course_parameters['period'], date('Y-m-d'));
            $next_evaluation_date = $this->Date->whatDate($evaluation_date, '+', $course_parameters['period'] * 7);

            $this->loadModel('Appreciation');
            $not_voted =  $this->Appreciation->notVoted($next_evaluation_date, $_POST['course_id']);

            foreach ($not_voted as $not_voted) :
                $this->loadModel('User');
                $user_identity = $this->User->identity($not_voted['username']);
                $user_email = $this->User->email($not_voted['username']);

                $message = '<html><body>';
                $message .= 'Bonjour,<br> Vous recevez ce message dans le cadre de l\'application TesTeam.<br><br>';
                $message .= 'N\'oubliez pas d\'aller évaluer vos paires cette semaine.<br> Pour rappel, cette application a pour but de faciliter le travail de groupe.';
                $message .= '<br><br>Vous pouvez accéder à l\'application en cliquant <a href="https://'.$configs['server_name'].'/'. $configs['student_app_name'].'/index.php?course_id=' . $_POST['course_id'] . '">ici</a><br><br> ';
                $message .= 'Si vous rencontrer un problème, veuillez contacter votre enseignant ou envoyer un mail à '. $configs['email_sender'];
                $message .= '</body></html>';

                $subject = "Vous avez une nouvelle notification TesTeam";
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->Host = $configs['host_email'];
                $mail->SMTPAuth   = false;
                $mail->Port = 25;
                $mail->CharSet = 'UTF-8';

                $mail->SetFrom($configs['email_sender'], 'Application TesTeam');
                $mail->AddAddress($user_email['email'], $user_identity['firstname'] . ' ' . $user_identity['lastname']);
                $mail->Subject = $subject;
                $mail->MsgHTML($message);

                if (!$mail->Send()) {
                    echo 'Erreur : ' . $mail->ErrorInfo;
                }

            endforeach;

            $this->index(1);
        }
    }
}
