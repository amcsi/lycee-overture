export default {
  deckAndCardsRulesTitle: "Deck and Cards",
  deckTitle: "Deck",
  deckRules: {
    overview: "The rules of constructing a deck are as follows:",
    rule1: "The numbe" + "" + "r of cards must be exactly 60; no more, no less.",
    rule2: "You can have up to 4 copies of a card with the same card number.",
    rule3:
      'Cards with "Deck Restriction" on them can only be put in decks that consist' +
      " of cards that are purely from the same brand.",
    rule4: 'Only 1 character with the Basic Ability "Leader" can be in the deck.',
  },
  dotNumberN: "Dot number {number}",
  characterCard: {
    title: "Character Card",
    imageAlt: "Picture of a Character Card with numbered dots in different locations",
    overview:
      "The card that represents a character. Character cards are summoned on to the field to do battle.",
    overviewNote:
      '※ During the game, character cards in an upright position are "untapped",  while character cards in a sideways position are "tapped".',
    cardName: "Card Name",
    cardNameDescription: "Name of the card.",
    element: "Element",
    elementDescription:
      "Element of the card. There are five colored elements: [snow] Snow, [moon] Moon, [flower] Flower, [space] Space, and [sun] Sun; and the colorless element: [star] Star",
    ex: "EX",
    exDescription: "The amount of cost this card can pay for other cards.",
    cost: "Cost",
    costDescription: "The cost required to play this card from your hand.",
    positionRestriction: "Position Restriction",
    positionRestrictionDescription:
      "The character can only be summoned on positions indicated by the red dots.",
    apDescription: "The attack power of the character.",
    dmgDescription: "The damage power of the character.",
    dpDescription: "The defense power of the character.",
    spDescription: "The support power of the character.",
    basicAbility: "Basic Ability",
    basicAbilityDescription:
      "The basic abilities of the character. There are various basic abilities.",
    specialAbility: {
      title: "Special Ability",
      description: "The special ability of the character.",
      activate: "You declare when you activate the ability, but only once per turn.",
      trigger: "Activates automatically when the condition is met, but only once per turn.",
      continuous: "The ability is continuously active.",
      cost: "The ability can pay for the cost of another card.",
    },
    specialAbilityDescription:
      "The special ability of the character. Activation: you declare when you activate the ability, but only once per turn. Trigger: activates automatically when the condition is met, but only once per turn. Continuous: the ability is continuously active.",
    cardNumber: "Card Number",
    cardNumberDescription: "The identification of the card",
    cardNumberLettersExplanation:
      "(Any letters following the card numbers represent different variants of the same card. They are considered to have the same card number.)",
    type: "Character Type",
    typeDescription1: "Type of the card.",
    typeDescription2: "Can be relevant for [Assist] and other abilities.",
    brand: "Brand",
    brandDescription1: "Brand of the card.",
    brandDescription2:
      "Some cards can be restricted to only be played in decks purely consisting of cards of the same brand.",
  },
  eventCard: {
    title: "Event Card",
    imageAlt: "Event card with numbered dots in different locations",
    description1:
      "Event cards are used from the hand to activate effects that can change the course of the game.",
    description2: "After use, event cards are discarded (place in discard pile)",
    effect: "Effect",
    effectDescription: "Various effects the player gains by playing this card.",
  },
  itemCard: {
    title: "Item Card",
    imageAlt: "Item card with numbered dots in different locations",
    description1:
      "Item cards are used from the hand, and can be equipped on to characters on the field.",
    description2: "Equipped cards remain active while equipped.",
    point1: "※ Only 1 Item Card can be equipped on to each character.",
    point2: "※ When the equipped character leaves the field, discard the equipped Item Card.",
    effectDescription: "The effect the equipped character gains.",
  },
  boardRulesTitle: "Board",
  field: {
    title: "Field",
    description:
      "This is where characters are summoned to. It consists of an Attack Field (AF) and Defense Field (DF), with each field containing 3 spots. In total there are 6 spots on the field.",
    point1: "Each spot can be occupied by only 1 character.",
    point2:
      "Once summoned, characters cannot be move around or leave the field unless explicitly stated.",
    imageAlt: "Picture of the board of a player's side.",
  },
  graveyard: {
    title: "Discard Pile",
    description1: "This is where discarded cards go.",
    description2:
      "The discard pile is shown face-up; both players can check each others' discard pile at any time.",
  },
  deck: {
    description1: "Your deck.",
    description2:
      "The deck is placed face-down; neither player is allowed to look at its contents.",
  },
  rowsAndColumns: {
    title: "Rows and Columns",
    description: "A spot a character fills is known as a Field.",
    point1_line1:
      'Based on the image above, the horizontal formation make up a "Row" or "Order", while the verticle formation make up a "Column".',
    point1_line2:
      'For example, "Ruler / Jeanne d\'Arc" (at the top right of the board) is on the right Column of the AF Row.',
    point1_line3: "(Your opponent will see her on the left Column of the AF Row.)",
    point2: 'A Field can be referred to as "1 Field" or "1 Spot" in card texts.',
    point3: "The AF is also referred to as the Front Row, and the DF the Back Row",
  },
  hand: {
    title: "Hand",
    description1:
      "At the start of the game, draw 7 cards from your deck. These cards will make your starting hand.",
    description2: "You can look at your hand at any time (but do not show it to your opponent!).",
  },
  costRulesTitle: "Cost",
  cost: {
    title: "Cost",
    description1:
      "To summon characters, or use events and abilities, you must first pay the cost shown on the cards. Cost is paid by discarding cards from your hand.",
    description2:
      "The value of cost payable of the discarded card is represented by its element and EX value.",
    point1: "Cost is paid at the same time the use of a card is declared.",
    point2: "More than 1 card may be used to pay for cost, however any cost left over is lost.",
  },
  costCard: {
    element: "Element",
    cost: "Cost",
  },
  costExample: {
    example1:
      "For example, if you discard a EX1, [flower] character, the cost paid will be equals to 1 of [flower].",
    imageAlt:
      "Image of character card having its element and EX magnified with an arrow pointing to a flower element being put a trash can.",
  },
  howToSummon: {
    title: "How to Summon a Character",
    description:
      "In this example, a cost of [flower][flower] is required to summon this character.",
    imageAlt: "Picture of a character card with its cost of 2 flower magnified.",
    box: {
      title: "To use this card of cost [flower][flower], you can:",
      option1: "Discard 2 EX1 [flower] cards from your hand.",
      or: "Or",
      option2: "Discard 1 EX2 [flower] card from your hand.",
    },
    discard2Box: "Discard 2 EX1 [flower] cards",
    discard1Box: "Discard 1 EX2 [flower] card",
  },
  typesOfCosts: {
    title: "Types of Cost",
    oneCostOfSnow: "1 cost of [snow] element",
    oneCostOfMoon: "1 cost of [moon] element",
    oneCostOfFlower: "1 cost of [flower] element",
    oneCostOfSpace: "1 cost of [space] element",
    oneCostOfSun: "1 cost of [sun] element",
    oneCostOfStar: "1 cost of any element",
    tap: "Tap the character using the ability",
    discardFromDeck: "Discard 1 card from the top of your deck",
    other: {
      title: "Other",
      description: "Any other cost is indicated by the blue text",
    },
    free: "Use for no cost",
  },
  assist: {
    title: "Assist",
    description:
      "Cards with Basic Ability [Assist] can be used to pay for the cost of characters of the same Type. If done so, it pays for ANY element of your choosing (limited to the discarded card's EX).",
  },
  flowOfGameRules: {
    navTitle: "Flow of the Game",
    title: "Purpose and flow of the game (preparation before game start)",
  },
  turnRulesTitle: "Turn",
  battleRulesTitle: "Battle",
  basicAbilityRulesTitle: "Basic Abilities and the Stack",
};
