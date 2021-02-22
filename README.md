# Title: OOP: Blackjack

For my training at BeCode, I had to make the game Blackjack in PHP. This is the result.

## Learning objectives
- A first dive into OOP (object oriented programming)

## The Mission
Let's make a game in PHP: Blackjack! A game of chance and luck!

To keep the code structured for this complicated game, we are going to use classes and objects.

Your coach has provided you with some starter classes that you can use for the game, to help you out on your first OOP challenge. First spent some time reading these classes and really understand what they are doing. If something in the syntax is unclear, google it first and then ask your coach.

If this is still an unclear subject for you don't feel bad to google some basic OOP articles, or ask your coach. it is normal this feels difficult, because object oriented programming is a really complex subject!

### Blackjack Rules
- Cards are between 1-11 points.
    - Faces are worth 10
    - Ace is always worth 11
- Getting more than 21 points, means that you lose.
- To win, you need to have more points than the dealer, but not more than 21.
- The dealer is obligated to keep taking cards until they have at least 15 points.
- We are not playing with blackjack rules on the first turn (having 21 on first turn) - we leave this up to you as a nice to have.

#### Flow
  - A new deck is shuffled
  - Player and dealer get 2 random cards
  - Dealer shows first card he drew to player
  - Player either keeps getting hit (asks for more cards), or stands down.
  - If the player at any point goes above 21, he automatically loses.
  - Once the player is done the dealer keeps taking cards until he has at least 15 points. If he hits above 21 he automatically loses.
  - At the end display the winner

## Nice to have
- Implement a betting system
    - Every new player (new session) starts with 100 chips
    - After the player gets his 2 first cards every round ask how much he wants to bet. He needs to bet at least 5 chips. 
     - If the player wins the bet he gains double the amount of chips.
- Implement the blackjack first turn rule: if the player draws 21 the first turn: he directly wins. If the dealer draws 21 the first turn, he wins. If both draw it, it is a tie. 
    - When you implement both nice to have features, a blackjack means an auto win of 10 chips, a blackjack of the dealer a loss of 5 chips for the player.
    
## The result

Insert img of my result