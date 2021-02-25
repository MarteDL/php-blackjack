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
$display = '';
$_SESSION['hidden'] = "d-none";

// when the game is over, see who has won and display it
function gameOver($blackjack): string
{
    $_SESSION['hidden'] = 'd-none';
    $winnerMessage = "The winner of this round is ";

    if ($blackjack->getWinner() === 'you') {
        $_SESSION['chips'] += ($_SESSION['bet'] * 2);
        $winnerMessage .= $blackjack->getWinner() . ". You win " . ($_SESSION['bet'] * 2) . " chips this round! Buy yourself something pretty ;-)";
    } else if ($blackjack->getWinner() === 'the dealer') {
        $winnerMessage .= $blackjack->getWinner() . ". You've lost " . $_SESSION['bet'] . " chips this round! Better luck next time... (or not)";
    } else {
        $winnerMessage .= $blackjack->getWinner() . ". Your amount of chips stays the same!";
    }

    unset($_SESSION['bet']);
    return $winnerMessage;
}

// setting the chips at the start of a new game (not a new round)
if (!isset($_SESSION['chips'])) {
    $_SESSION['chips'] = 100;
}

// starting our new blackjack session
if (!isset($_SESSION['blackjack'])) {
    $_SESSION['blackjack'] = new Blackjack();

    // check for blackjack on first turn
    if ($_SESSION['blackjack']->checkForWinner()) {
        $_SESSION['bet'] = 5;

        if ($_SESSION['blackjack']->getWinner() === 'the dealer') {
            $_SESSION['chips'] -= $_SESSION['bet'];
        }
        $display = gameOver($_SESSION['blackjack']);
    }
    $_SESSION['hidden'] = "";
}
$blackjack = $_SESSION['blackjack'];

// betting the chips
if (isset ($_POST['chips']) && !empty($_POST['chips'])) {
    $_SESSION['bet'] = (int)$_POST['chips'];
    $_SESSION['chips'] -= (int)$_POST['chips'];
    unset($_POST['chips']);
}

// our button actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'hit':
            $blackjack->getPlayer()->hit($blackjack->getDeck());
            break;
        case 'stand':
            $blackjack->getDealer()->hit($blackjack->getDeck());
            if ($blackjack->checkForWinner()) {
                break;
            }
            if ($blackjack->getDealer()->getScore() < $blackjack->getPlayer()->getScore()) {
                $blackjack->getDealer()->setLost();
            }
            if ($blackjack->getDealer()->getScore() >= $blackjack->getPlayer()->getScore()) {
                $blackjack->getPlayer()->setLost();
            }
            break;
        case 'surrender':
            $blackjack->getPlayer()->setLost();
            break;
        case 'new-round':
            unset($_SESSION['blackjack']);
            header("location: index.php");
            exit;
        case 'new-game':
            session_destroy();
            header("location: index.php");
            exit;
        default:
            die(sprintf('Got unknown action %s', $_POST['action']));
    }
    if ($blackjack->checkForWinner()) {
        $display = gameOver($blackjack);
    }
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

<body style="background-image: url(img/blackjack.jpg); background-size: cover; background-repeat: repeat;"
      class="text-center text-light">

<header>
    <h1 class="m-5">Get ready for an awesome game of blackjack!</h1>
</header>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-3">
            <div id="player">
                <h3 class="mb-5">The Table</h3>
                <h5>Player's cards</h5>
                <h4 class="bg-light d-inline-block p-3 rounded">
                    <?php
                    echo $blackjack->getPlayer()->displayCards();
                    ?>
                </h4>
            </div>
            </br>
            <div id="dealer">
                <h5>Dealer's cards</h5>
                <h4 class="bg-light d-inline-block p-3 rounded">
                    <?php
                    echo $blackjack->checkForWinner() ? $blackjack->getDealer()->displayCards() :
                        $blackjack->getDealer()->getCards()[0]->getUnicodeCharacter($includeColor = true);
                    ?>
                </h4>
            </div>
        </div>
        <div class="col-3">
            <h3 class="mb-5">The Score</h3>
            <h5>Player: <?php echo $blackjack->getPlayer()->getScore() ?></h5>
            <h5>
                Dealer: <?php echo $blackjack->checkForWinner() ? $blackjack->getDealer()->getScore() : 'not known yet' ?></h5>
            </br>
            <h5>Your chips: <?php echo $_SESSION['chips'] ?></h5>
        </div>
    </div>
    <div class="row justify-content-center m-5">
        <form action="" method="POST">
            <fieldset>
                <?php $disabledButton = $blackjack->checkForWinner() ? 'disabled' : '' ?>
                <button type="submit" name="action" value="hit" <?php echo $disabledButton ?>>Hit</button>
                <button type="submit" name="action" value="stand" <?php echo $disabledButton ?>>Stand</button>
                <button type="submit" name="action" value="surrender" <?php echo $disabledButton ?>>Surrender</button>
            </fieldset>
            <fieldset class="mt-5 <?php echo $_SESSION['hidden']; ?>">
                <label for="chips">How many chips do you want to bet?</label>
                <input class="ml-2" type="number" id="chips" name="chips" min="<?php echo MIN_CHIPS ?>"
                       max="<?php echo $_SESSION['chips'] ?>">
            </fieldset>
            <?php
            if ($blackjack->checkForWinner()) :
                ?>
                <div class="m-5 p-3 bg-danger rounded"><?php echo $display ?></div>
                <form action="" method="POST">
                    <button type="submit" name="action" value="new-round">Same game, new round</button>
                    <button type="submit" name="action" value="new-game">New game</button>
                </form>
            <?php
            endif;
            ?>
        </form>
    </div>
</main>
</body>
</html>
