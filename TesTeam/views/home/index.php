<div class="dashboard">
    <div class="dashboard__header header">
        <div class="header__child header__menu menu">
            <a href="#menu__nav" class="menu__label" id="menu__label">Menu</a>
            <nav class="menu__nav" id="menu__nav">
                <a href="#" class="menu__close">Close</a>
                <div class="menu__content">
                    <p class="menu__title">Liste des cours</p>
                    <p class="menu__text">Retrouvez ici la liste de vos cours avec la possibilité d’en ajouter, d’en modifier ou d’en supprimer.</p>
                    <?php include('menu.php'); ?>
                </div>
            </nav>
        </div>
        <?php include('header.php'); ?>
    </div>
    <div class="dashboard__body" id="main">
        <?php
        if (empty($teachersCourses)) :
            include(ROOT . '/views/form/add.php');
        ?>
            <div class="hide" id="firstadd"></div>
        <?php
        else :
            include(ROOT . '/views/cards/index.php');
        endif;
        ?>
    </div>
</div>