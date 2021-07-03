<?php
class Cards extends ComponentController
{
    public function index()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $this->loadHelper('Date');
            $date = $this->Date->whatDate(date('Y-m-d'), '-', 7);

            // Get current card for display the red card list
            $this->loadModel('Card');
            $cards = $this->Card->currentRed($date, $_SESSION['user']);

            $this->render('index', compact('cards'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function details()
    {

        if ($_POST['id_red_card'] && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            // Get more infomations on a specific red card
            $this->loadModel('Card');
            $current_red_card = $this->Card->findById($_POST['id_red_card']);
            $archived = $this->Card->archived($current_red_card['username'], $current_red_card['id_course'], $_POST['id_red_card']);

            // Couse parameters informations
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($current_red_card['id_course']);

            // User identity firstaname, lastname
            $this->loadModel('User');
            $user_identity = $this->User->identity($current_red_card['username']);

            // Calculation of the days of the first and the last evaluation weeks on 3 weeks * period
            $this->loadHelper('Date');
            $date = $this->Date->dateCalculation($course_parameters, $current_red_card['date'], 3);

            $first_date = $date[0];

            // The history of yellow cards according to the red card
            $this->loadModel("Card");
            $yellow_cards = $this->Card->yellowHistory($current_red_card['username'], $current_red_card['id_course'], $first_date, $current_red_card['date']);

            // Array of week numbers and date on 3 weeks * period
            $weeks = array();
            $days = array();
            for ($i = 3; $i > 0; $i--) {
                array_push($weeks,  date("W", strtotime("- " . (($course_parameters['period']) * $i -1) . " week", strtotime($current_red_card['date']))));
                array_push($days,  date('Y-m-d', strtotime('-' . (7 * $course_parameters['period'] * ($i - 1)) . 'day', strtotime($current_red_card['date']))));
            }

            $yellow_cards_history = array_fill(0, 3, '');
            $yellow_cards_id = array();
            $cpt = 0;
            for ($i = 2; $i >= 0; $i--) {
                if (isset($yellow_cards[$cpt]) && ($days[$i] == $yellow_cards[$cpt]['date'])) {
                    $yellow_cards_id = array_merge($yellow_cards_id,  explode(',', $yellow_cards[$cpt]['GROUP_CONCAT(id)']));
                    $yellow_cards_history[$i] = $yellow_cards[$cpt];
                    $cpt++;
                }
            }
            array_push($yellow_cards_id, $current_red_card['id']);

            $this->render('details', compact('current_red_card', 'course_parameters', 'user_identity', 'archived', 'weeks', 'yellow_cards_history', 'yellow_cards_id'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function resolved()
    {
        if (isset($_POST['id_yellow_card']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Add a resolution date to all cards
            $_POST['id_yellow_card'] = unserialize(base64_decode($_POST['id_yellow_card']));
            $this->loadModel("Card");
            $this->Card->resolved($_POST['id_yellow_card'], date('Y-m-d'));

            $this->index();
        } else {
            $this->render('../layouts/error');
        }
    }


    public function notResolved()
    {
        if (isset($_POST['id_yellow_card']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Add a null date to all cards
            $_POST['id_yellow_card'] = unserialize(base64_decode($_POST['id_yellow_card']));
            $this->loadModel('Card');
            $this->Card->notResolved($_POST['id_yellow_card']);

            $this->index();
        } else {
            $this->render('../layouts/error');
        }
    }


    public function redCardAlert()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Count the number of red card actived
            $this->loadModel('Card');
            $red_card_activated = $this->Card->redActivated($_SESSION['user']);
            $this->render('../home/red_card_alert', compact('red_card_activated'));
        } else {
            $this->render('../layouts/error');
        }
    }
}
