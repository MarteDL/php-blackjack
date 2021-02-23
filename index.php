<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'code/Card.php';
require 'code/Deck.php';
require 'code/Suit.php';
require 'code/Blackjack.php';
require 'code/Player.php';
require 'code/Dealer.php';

session_start();

const MIN_CHIPS = 5;

function gameOver(): void
{
    $_SESSION['hidden'] = 'd-none';
    header("Location: http://becode.local/php-blackjack/code/endofgame.php");
}

function checkForWinner() : void
{
    if ($_SESSION['dealer']->hasLost()) {
        $_SESSION['winner'] = 'player';
        gameOver();
    } else if ($_SESSION['player']->hasLost()) {
        $_SESSION['winner'] = 'dealer';
        gameOver();
    }
}

// two if statements in regards to betting the chips
if (!isset($_SESSION['chips'])) {
    $_SESSION['chips'] = 100;
}

if (isset ($_POST['chips']) && !empty($_POST['chips'])) {
    $_SESSION['bet'] = (int)$_POST['chips'];
    $_SESSION['chips'] -= (int)$_POST['chips'];
    unset($_POST['chips']);
}

// starting our new blackjack session
if (!isset($_SESSION['blackjack'])) {
    $_SESSION['blackjack'] = new Blackjack();
    $_SESSION['player'] = $_SESSION['blackjack']->getPlayer();
    $_SESSION['dealer'] = $_SESSION['blackjack']->getDealer();
    $_SESSION['deck'] = $_SESSION['blackjack']->getDeck();
}

// hit button function
if (isset($_POST['hit'])) {
    $_SESSION['player']->hit($_SESSION['deck']);
    checkForWinner();
}

// stand button function
if (isset($_POST['stand'])) {

    $_SESSION['dealer']->hit($_SESSION['blackjack']->getDeck());

    if ($_SESSION['dealer']->getScore() < $_SESSION['player']->getScore()) {
        $_SESSION['dealer']->setLost();
    } else {
        $_SESSION['player']->setLost();
    }
    checkForWinner();
}

// surrender button function
if (isset($_POST['surrender'])) {
    $_SESSION['player']->surrender();
    $_SESSION['winner'] = 'dealer';
    gameOver();
}

// if statement to hide/display our 'how many chips do you want to bet' input field
if (count($_SESSION['player']->getCards()) === 2) {
    $_SESSION['hidden'] = "";
} else {
    $_SESSION['hidden'] = "d-none";
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Blackjack</title>
</head>

<body class="text-center">
<h1 class="m-5">Get ready for an awesome game of blackjack!</h1>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-3">
            <div id="player">
                <h3 class="mb-5">The Table</h3>
                <h5>Player cards</h5>
                <h4>
                    <?php
                    $_SESSION['player']->displayCards();
                    ?>
                </h4>
            </div>
            </br>
            <div id="dealer">
                <h5>Dealer cards</h5>
                <h4>
                    <?php
                    $_SESSION['dealer']->displayCards();
                    ?>
                </h4>
            </div>
        </div>
        <div class="col-3">
            <h3 class="mb-5">The Score</h3>
            <h5>Player: <?php echo $_SESSION['player']->getScore() ?></h5>
            <h5>Dealer: <?php echo $_SESSION['dealer']->getScore() ?></h5>
            </br>
            <h5>Your chips: <?php echo $_SESSION['chips'] ?></h5>
        </div>
    </div>
    <div class="row justify-content-center m-5">
        <form action="" method="POST">
            <fieldset>
                <button type="submit" name="hit">Hit</button>
                <button type="submit" name="stand">Stand</button>
                <button type="submit" name="surrender">Surrender</button>
            </fieldset>
            <fieldset class="mt-5 <?php echo $_SESSION['hidden']; ?>">
                <label for="chips">How many chips do you want to bet?</label>
                <input class="ml-2" type="number" id="chips" name="chips" min="<?php echo MIN_CHIPS ?>"
                       max="<?php echo $_SESSION['chips'] ?>">
            </fieldset>
        </form>
    </div>
</div>

</body>
</html>
