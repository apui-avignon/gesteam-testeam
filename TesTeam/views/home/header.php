<h1 class="header__child header__title title">
    <span class="title__prefix">Tableau de bord</span>
    <span class="title__suffix">&Eacute;valuation de groupe</span>
</h1>
<div class="header__child header__account">
    <div class="header__name separator__content">
        <?= $user_identity['firstname'] . '<br>' . $user_identity['lastname']; ?>
    </div>
    <div class="separator">
    </div>
    <div class="header__child header__alert">
        <?php
        include('red_card_alert.php');
        ?>
    </div>
</div>