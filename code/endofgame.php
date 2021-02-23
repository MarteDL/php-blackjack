<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


require '../index.php';

$winnerMessage = "The winner of this round of Blackjack is the " . $_SESSION['blackjack']->getWinner() . ".";

if ($_SESSION['winner'] === 'player') {
    $_SESSION['chips'] += ($_SESSION['bet']*2);
    $winnerMessage .= " You win " . ($_SESSION['bet']*2) . " chips!";
    unset($_SESSION['bet']);
}

if (isset($_POST['new-round'])) {
    unset($_SESSION['blackjack']);
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['new-game'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>


<div><?php echo $winnerMessage ?></div></br>
<form action="" method="POST">
    <button type="submit" name="new-round">Same game, new round</button>
    <button type="submit" name="new-game">New game</button>
</form>
