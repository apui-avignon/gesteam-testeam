<?php
$date = date('Y-m-d');

$positive = array("Bravo", "Excellent", "Bon travail", "Youhou", "Bien", "Une belle journée");
$positiveRand = array_rand($positive, 1);

$negative = array("Attention", "Oops", "Oulà mince", "Aïe aïe aïe", "Courage", "Encore du travail");
$negativeRand = array_rand($negative, 1);

if ($red_card_activated['COUNT(*)'] == 0) : ?>
    <button class="button bar__button button--account button--red-cards" type="submit"> <?= $red_card_activated['COUNT(*)'] ?> </button>
    <div class="header__message"><strong><?= $positive[$positiveRand]; ?>&nbsp;!</strong> <br>Pas&nbsp;de carton rouge</div>

<?php
else :
?>
    <button class="button bar__button button--account button--red button--red-cards" type="submit"> <?= $red_card_activated['COUNT(*)'] ?> </button>
    <div class="header__message"><strong><?= $negative[$negativeRand]; ?>&nbsp;!</strong> <br>Des&nbsp;cartons rouges</div>

<?php
endif;
?>
<script>
    dynamicFavicon(<?= $red_card_activated['COUNT(*)'] ?>);
</script>