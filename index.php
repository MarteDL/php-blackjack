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
$display = "";

// starting our new blackjack session
if (!isset($_SESSION['blackjack'])) {
    $_SESSION['blackjack'] = new Blackjack();
    $_SESSION['player'] = $_SESSION['blackjack']->getPlayer();
    $_SESSION['dealer'] = $_SESSION['blackjack']->getDealer();
}

// setting the chips at the start of a new game
if (!isset($_SESSION['chips'])) {
    $_SESSION['chips'] = 100;
}

// betting the chips
if (isset ($_POST['chips']) && !empty($_POST['chips'])) {
    $_SESSION['bet'] = (int)$_POST['chips'];
    $_SESSION['chips'] -= (int)$_POST['chips'];
    unset($_POST['chips']);
}

// blackjack first turn
if(count($_SESSION['player']->getCards()) === 2 && count($_SESSION['dealer']->getCards()) === 2) {
    if ($_SESSION['player']->getScore() === 21) {
        $_SESSION['dealer']->setLost();
        $_SESSION['bet'] = 5;
        $display = "You have BLACKJACK, yay! Sooo... ". gameOver();
    }
    if ($_SESSION['dealer']->getScore() === 21) {
        $_SESSION['player']->setLost();
        $_SESSION['bet'] = 5;
        $_SESSION['chips'] -= $_SESSION['bet'];
        $display = "The dealer has BLACKJACK! Sooo... ". gameOver();
    }
    if ($_SESSION['player']->getScore() === 21 && $_SESSION['dealer']->getScore() === 21) {
        $display = "You and the dealer both have BLACKJACK. Sooo... ".gameOver();
    }
}

// hit button
if (isset($_POST['hit'])) {
    $_SESSION['player']->hit($_SESSION['blackjack']->getDeck());
    if($_SESSION['blackjack']->checkForWinner()) {
        $display = gameOver();
    }
}

// stand button
if (isset($_POST['stand'])) {

    if($_SESSION['dealer']->getScore() < $_SESSION['player']->getScore()) {
        $_SESSION['dealer']->hit($_SESSION['blackjack']->getDeck());
    }

    if ($_SESSION['dealer']->getScore() < $_SESSION['player']->getScore()) {
        $_SESSION['dealer']->setLost();
    } else {
        $_SESSION['player']->setLost();
    }

    if($_SESSION['blackjack']->checkForWinner()) {
        $display = gameOver();
    }
}

// surrender button
if (isset($_POST['surrender'])) {
    $_SESSION['player']->surrender();
    $display = gameOver();
}

// if statement to hide/display our 'how many chips do you want to bet' input field
if (count($_SESSION['player']->getCards()) === 2) {
    $_SESSION['hidden'] = "";
} else {
    $_SESSION['hidden'] = "d-none";
}

// when the game is over
function gameOver(): string
{
    $_SESSION['hidden'] = 'd-none';
    $winnerMessage = "The winner of this round ";

    if ($_SESSION['blackjack']->getWinner() === 'you') {
        $_SESSION['chips'] += ($_SESSION['bet']*2);
        $winnerMessage .= $_SESSION['blackjack']->getWinner() . ". You win " . ($_SESSION['bet']*2) . " chips this round! Buy yourself something pretty ;-)";
        unset($_SESSION['bet']);
        return $winnerMessage;
    }
    if ($_SESSION['blackjack']->getWinner() === 'the dealer'){
        $winnerMessage .= $_SESSION['blackjack']->getWinner() . ". You've lost " . $_SESSION['bet'] . " chips this round! Better luck next time... (or not)";
        unset($_SESSION['bet']);
        return $winnerMessage;
    }

    $winnerMessage .= $_SESSION['blackjack']->getWinner() . ". Your amount of chips stays the same!";
    unset($_SESSION['bet']);
    return $winnerMessage;
}

// new round button
if (isset($_POST['new-round'])) {
    unset($_SESSION['blackjack']);
    header("location: index.php");
    exit;
}

// new game button
if (isset($_POST['new-game'])) {
    session_destroy();
    header("location: index.php");
    exit;
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

<body style="background-image: url(img/blackjack.jpg); background-size: cover; background-repeat: no-repeat;" class="text-center text-light">
<h1 class="m-5">Get ready for an awesome game of blackjack!</h1>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-3">
            <div id="player">
                <h3 class="mb-5">The Table</h3>
                <h5>Player cards</h5>
                <h4 class="bg-light d-inline-block p-3 rounded">
                    <?php
                    echo $_SESSION['player']->displayCards();
                    ?>
                </h4>
            </div>
            </br>
            <div id="dealer">
                <h5>Dealer cards</h5>
                <h4 class="bg-light d-inline-block p-3 rounded">
                    <?php
                    echo $_SESSION['dealer']->displayCards();
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
            <?php
            if($_SESSION['blackjack']->checkForWinner()) :
            ?>
            <div class="m-5 p-3 bg-danger rounded"><?php echo $display ?></div>
            <form action="" method="POST">
                <button type="submit" name="new-round">Same game, new round</button>
                <button type="submit" name="new-game">New game</button>
            </form>
            <?php
            endif;
            ?>
        </form>
    </div>
</div>

</body>
</html>
