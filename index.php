<form method="post">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title"><br>
    <label for="from">From :</label><br>
    <input type="text" id="from" name="from"><br>
    <label for="to">To :</label><br>
    <input type="text" id="to" name="to"><br>
    <label for="desc">Description :</label><br>
    <input type="text" id="desc" name="desc"><br>
    <label for="address">Address :</label><br>
    <input type="text" id="address" name="address"><br>
    <input type="radio" id="google" name="google" value="Google">
    <label for="google">Google</label><br>
    <input type="radio" id="yahoo" name="yahoo" value="Yahoo">
    <label for="yahoo">Yahoo</label><br>
    <input type="radio" id="outlook" name="outlook" value="Outlook">
    <label for="outlook">Outlook</label><br>
    <input type="radio" id="ics" name="ics" value="ICS">
    <label for="ics">ICS</label><br>
    <input type="submit" value="Submit" name="submit">
</form>

<?php

require_once "vendor/autoload.php";
use Spatie\CalendarLinks\Link;

function validateDate($date, $format = 'Y-m-d H:i'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if (isset($_POST['submit'])) {
    if ($_POST['title'] === "") {
        echo "Title must be set! ";
    } elseif ($_POST['from'] === "") {
        echo "'From' time must be set!";
    } elseif ($_POST['to'] === "") {
        echo "'To' time must be set!";
    } elseif (validateDate($_POST['from']) === false) {
        echo "Invalid 'from' date format or date! Use YYYY-MM-DD HH:MM or check input date";
    } elseif (validateDate($_POST['to']) === false) {
        echo "Invalid 'to' date format or date! Use YYYY-MM-DD HH:MM or check input date";
    } elseif (!isset($_POST['google']) && !isset($_POST['yahoo']) && !isset($_POST['outlook']) && !isset($_POST['ics']))
    {
      echo "You need to choose an event calendar type! " . PHP_EOL;
    } else {
        $title = $_POST["title"];
        $from = DateTime::createFromFormat('Y-m-d H:i', $_POST["from"]);
        $to = DateTime::createFromFormat('Y-m-d H:i', $_POST["to"]);

        $link = Link::create($title, $from, $to)
            ->description($_POST['desc'])
            ->address($_POST['address']);

        if (isset($_POST['google'])) {
            echo "<a href='" . $link->google() . "' target='_blank'>Your link to Google Calendar</a>";
        } elseif (isset($_POST['yahoo'])) {
            echo "<a href='" . $link->yahoo() . "' target='_blank'>Your link to Yahoo! calendar</a>";
        } elseif (isset($_POST['outlook'])) {
            echo "<a href='" . $link->webOutlook() . "' target='_blank'>Your link to OutLook</a>";
        } elseif (isset($_POST['ics'])) {
            echo "<a href='" . $link->ics() . "' target='_blank'>Press this button to download ICS</a>";
        }
    }
}
?>