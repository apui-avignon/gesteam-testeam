<?php
include('header.php');
?>
<div class="alerts">
    <?php

    if ($red_card == 1) {
    ?>
        <div class="alerts__item alerts__flag">
            <p class="alerts__button alerts__button--red"><?= $red_card ?></p>
            <p class="alerts__message"><strong class="color--red">Attention&nbsp;!</strong><br> Vous avez reçu un carton rouge</p>
        </div>
    <?php
    } else if ($yellow_cards > 0) {
    ?>
        <div class="alerts__item alerts__flag">
            <p class="alerts__button alerts__button--warn"><?= $yellow_cards ?>

            </p>
            <p class="alerts__message">
                <strong class="color--yellow">Oops&nbsp;!</strong>
                <br>
                Vous avez reçu des avertissements
            </p>

        </div>
    <?php
    }
    ?>
    <hr>
    <div id="details">
        <?php include('details.php'); ?>
    </div>
</div>
<div id="graphic">
    <?php include('graphic.php'); ?>
</div>