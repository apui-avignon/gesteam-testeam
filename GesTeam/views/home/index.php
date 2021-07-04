<div id="main">
    <?php
    $configs = include('config.php');
    include('header.php');
    ?>
    <main class="main">
        <form method="POST" id="form" action="index.php?p=save/index">
            <div class="panels">
                <div class="panels__item" data-number="0">
                    <h1 class="h1 h1--big">Bienvenue&nbsp;!</h1>
                    <?php
                    if ($voted) {
                    ?>
                        <p class="message">L’évaluation des membres de votre groupe sur ce cours a bien été envoyée</p>
                        <p class="text">Vous pouvez modifier les évaluations de vos camarades ou consulter vos notes</p>
                        <footer class="footer">
                            <div class="footer__part">
                                <button class="pages__next button button--primary button--start">Modifier</button>
                            </div>
                        </footer>

                    <?php
                    } else {
                    ?>
                        <p class="message">Vous pouvez dès à présent évaluer vos collègues ou consulter vos notes</p>
                        <footer class="footer">
                            <div class="footer__part">
                                <button class="pages__next button button--primary button--start">Commencer</button>
                            </div>
                        </footer>
                    <?php
                    }
                    ?>
                </div>

                <?php for ($i = 1; $i <= 4; $i++) { ?>
                    <div class="panels__item hide" id="page-<?= $i ?>" data-number="<?= $i ?>">
                        <h2 class="h2" data-step="<?= $i ?>">
                            <span class="info">
                                Catégorie <?= $criteria_name[$i - 1] ?>
                            </span>
                        </h2>
                        <div>
                            <?php
                            for ($j = 0; $j < $nb_member; $j++) { ?>

                                <div class="bar" data-firstname=<?= $group[$j]['firstname'] ?> data-lastname=<?= $group[$j]['lastname'] ?>>
                                    <input class="bar__range" id="criterion-<?= $i ?>" name="criteria_<?= $i ?>[]" type="range" min="-2" max="1" step="1" value="<?= $criteria[$i - 1][$j] ?>">
                                    <button class="bar__button" aria-expanded="false"></button>
                                    <img class="bar__item bar__separator" src="../<?= $configs['teacher_app_name'] ?>/images/separator.svg" alt="">
                                    <button class="bar__button" aria-expanded="false"></button>
                                    <img class="bar__item bar__separator" src="../<?= $configs['teacher_app_name'] ?>/images/separator.svg" alt="">
                                    <button class="bar__button" aria-expanded="false"></button>
                                    <img class="bar__item bar__separator" src="../<?= $configs['teacher_app_name'] ?>/images/separator.svg" alt="">
                                    <button class="bar__button" aria-expanded="false"></button>
                                </div>
                            <?php } ?>
                        </div>
                        <footer class="footer">
                            <div class="footer__part">
                                <?php if ($i > 1) { ?>
                                    <button class="pages__previous button" type="button" name="button"><span class="button__prefix">&lt;</span></button>
                                <?php } ?>
                            </div>
                            <div class="footer__part">
                                <?php if ($i < 4) { ?>
                                    <button class="pages__next button" type="button" name="button">
                                        <span class="button__prefix">&gt;</span>
                                        <span class="button__text">Continuer</span>
                                    </button>
                                <?php } else { ?>
                                    <button class="button" type="submit" name="button" id="save">
                                        <span class="button__prefix">&gt;</span>
                                        <span class="button__text">Envoyer</span>
                                    </button>
                                <?php } ?>
                            </div>
                        </footer>
                    </div>
                <?php } ?>
            </div>
        </form>
    </main>
</div>

<script>
    $(document).ready(function() {
        // Navigation
        var pagesItem = document.querySelectorAll('.panels__item');
        var pagesNext;
        var pagesPrevious;
        var thisParent;
        for (var i = 0; i < pagesItem.length; i++) {
            pagesItem[0].classList.remove('hide');
            pagesNext = pagesItem[i].querySelector('.pages__next');
            pagesPrevious = pagesItem[i].querySelector('.pages__previous');
            if (pagesNext) {
                pagesNext.addEventListener('click', function(e) {
                    e.preventDefault();
                    thisParent = this.parentNode.parentNode.parentNode;
                    thisParent.classList.add('hide');
                    var nb = parseInt(thisParent.getAttribute('data-number'));
                    pagesItem[nb + 1].classList.remove('hide');
                });
            }
            if (pagesPrevious) {
                pagesPrevious.addEventListener('click', function(e) {
                    e.preventDefault();
                    thisParent = this.parentNode.parentNode.parentNode;
                    thisParent.classList.add('hide');

                    var nb = parseInt(thisParent.getAttribute('data-number'));
                    pagesItem[nb - 1].classList.remove('hide');
                });
            }
        }

        // Evaluation
        var bars = document.querySelectorAll('.bar');
        var barRange, barRangeVal, barFirstname, barLastname, barButtons, thisParent, thisRange;
        for (var i = 0; i < bars.length; i++) {
            barRange = bars[i].querySelector('.bar__range');
            barRangeVal = barRange.value;
            barRange.parentNode.setAttribute('data-eval', barRangeVal);
            barFirstname = bars[i].getAttribute('data-firstname');
            barLastname = bars[i].getAttribute('data-lastname');
            barButtons = bars[i].querySelectorAll('.bar__button');
            for (var j = 0; j < barButtons.length; j++) {
                barButtons[j].setAttribute('data-tippy-content', barFirstname + ' ' + barLastname)
                barButtons[j].innerText = barFirstname.charAt(0) + barLastname.charAt(0);
                (function() {
                    var n = j - 2;
                    barButtons[j].addEventListener('click', function(e) {
                        e.preventDefault();
                        thisParent = this.parentNode;
                        thisParent.setAttribute('data-eval', n);
                        thisRange = thisParent.querySelector('.bar__range');
                        thisRange.value = n;
                    });
                }());
            };
        };
        // Tooltip
        tippy('.bar__button', {
            animation: 'fade',
            arrow: true,
            interactive: true,
            trigger: 'click',
            aria: null,
            appendTo: 'parent',
            onMount({
                reference
            }) {
                reference.setAttribute('aria-expanded', 'true')
            },
            onHide({
                reference
            }) {
                reference.setAttribute('aria-expanded', 'false')
            },
        });
    });
</script>