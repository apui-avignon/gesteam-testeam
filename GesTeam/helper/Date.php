<?php
class Date
{
    // Get the last monday of the week
    public function getDate($date)
    {
        $day = "Monday";
        if (date('D', strtotime($date)) != "Mon") {
            $date = date('Y-m-d', strtotime("last " . $day . ' ' . $date));
        }
        return $date;
    }


    # https://stackoverflow.com/questions/3028491/php-weeks-between-2-dates/16923504
    // Calculate the difference between two days
    function datediffInWeeks($date1, $date2)
    {
        if ($date1 > $date2) return $this->datediffInWeeks($date2, $date1);
        $first = DateTime::createFromFormat('Y-m-d', $date1);
        $second = DateTime::createFromFormat('Y-m-d', $date2);
        return floor($first->diff($second)->days / 7);
    }


    // Gives the day of the last evaluation
    function getDayOfEvaluation($date, $period)
    {
        $startDate = $this->getDate($date);
        $currentDate = $this->getDate(date('Y-m-d'));
        $diffWeek = $this->datediffInWeeks($startDate, $currentDate);
        $diffWeek = $diffWeek % $period;
        return date('Y-m-d', strtotime('-' . $diffWeek . ' week', strtotime($currentDate)));
    }


    // Get the last and the first date for the history
    function dateCalculation($course_parameters, $last_date, $nb_of_week = 15)
    {
        $date = array();
        // Stop the date if the current date is upper the end date
        $today = date('Y-m-d');
        if ($last_date > $course_parameters['end_date']) {
            $last_date = $course_parameters['end_date'];
        }
        if ($today > $course_parameters['end_date']) {
            $today = $course_parameters['end_date'];
        }

        if ($last_date == $today) {
            $last_evaluation_date = $this->getDayOfEvaluation($last_date, $course_parameters['period']);
        } else {
            $last_evaluation_date = $last_date;
        }
        if (isset($_POST['value'])) {
            if (isset($_POST['direction']) && $_POST['direction'] == 'right') {
                $first_date_graphic = $_POST['value'];
            } else {
                $last_date_graphic = $_POST['value'];
            }
        } else {
            $last_date_graphic = $last_evaluation_date;
        }

        // Evaluation frequency in days
        $evaluation_frequency = 7 * intval($course_parameters['period']);

        // Updates the extreme dates of the graph
        if (isset($_POST['direction']) && $_POST['direction'] == 'right') {
            $last_date_graphic = date('Y-m-d', strtotime('+' . $evaluation_frequency * $nb_of_week . 'day', strtotime($first_date_graphic)));
        } else {
            $first_date_graphic = date('Y-m-d', strtotime('-' . $evaluation_frequency * $nb_of_week . 'day', strtotime($last_date_graphic)));
        }

        array_push($date, $first_date_graphic, $last_date_graphic, $last_evaluation_date);
        return $date;
    }


    // Calculation date
    function whatDate($date, $sign, $nb_of_days)
    {
        $new_date = date('Y-m-d', strtotime($sign . $nb_of_days . 'day', strtotime($date)));
        return $new_date;
    }
}
