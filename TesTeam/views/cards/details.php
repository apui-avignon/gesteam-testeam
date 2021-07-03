<div class="dashboard__body">
    <div class="body__head">
        <div class="body__intro">
            <?php
            if (!empty($current_red_card['deactivation_date'])) {
            ?>
                <h2 class="h1">Traitement du carton rouge - Statut : <span class="underline">Résolu</span> </h2>
            <?php
            } else {
            ?>
                <h2 class="h1">Traitement du carton rouge - Statut : <span class="underline">&Agrave; résoudre</span> </h2>
            <?php
            }
            ?>
            <p>L'étudiant(e) <strong><?= $user_identity['firstname'] . " " . $user_identity['lastname'] ?></strong> a reçu un <strong class="underline underline--red">carton rouge</strong> dans le cours <strong><?= $course_parameters['course'] ?></strong>. <br>
                Il a accumulé plus de <strong class="underline underline--yellow"><?= $course_parameters['threshold_red_card'] ?> cartons jaunes</strong> au cours des <?= $course_parameters['period'] * 3 ?> dernières période d'évaluation. <br>
                <?php
                if ($archived['COUNT(*)'] != 0) {
                ?>
                    Il a deja recu <strong class="underline underline--red"><?= $archived['COUNT(*)'] ?> cartons rouges</strong> par le passé.
                <?php
                } else {
                ?>
                    Il n'a pour l'instant jamais reçu de carton rouge dans le passé.
                <?php
                }
                ?>
            </p>
        </div>
    </div>
    <div class="body__content">
        <table class="table--blank size--xlarge" id="table-resolve">
            <thead>
                <tr>
                    <th class="col--large">S <?= $weeks[0]; ?></th>
                    <th class="col--large">S <?= $weeks[1]; ?></th>
                    <th class="col--large">S <?= $weeks[2]; ?> - Dernière évaluation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="eval eval--0">
                        <?php
                        if (!empty($yellow_cards_history[0])) {
                        ?>
                            <div class="alerts__button"><?= $yellow_cards_history[0]['COUNT(*)']; ?></div>
                        <?php
                        } else {
                            echo "Pas de données";
                        }
                        ?>
                    </td>
                    <td class="eval eval--0">

                        <?php
                        if (!empty($yellow_cards_history[1])) {
                        ?>
                            <div class="alerts__button"><?= $yellow_cards_history[1]['COUNT(*)']; ?></div>
                        <?php
                        } else {
                            echo "Pas de données";
                        }
                        ?>
                    </td>
                    <td class="eval eval--0 col--large">

                        <?php
                        $yellowCardSecondChance = 0;
                        if (!empty($yellow_cards_history[2])) {
                        ?>
                            <div class="alerts__button"><?= $yellow_cards_history[2]['COUNT(*)']; ?></div>
                        <?php
                        } else {
                            echo "Pas de données";
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>
        <?php
        if (empty($current_red_card['deactivation_date'])) {
        ?>
            <h2><strong>Le carton a-t-il été traité ?</strong></h2>
            <div class="buttons buttons--action">
                <button class="button button--action button--primary" id="card-resolved" value="<?= base64_encode(serialize($yellow_cards_id)); ?>">Oui</button>
                <button class="button button--action button--secondary buttons__secondary save-event">Pas encore</button>
            <?php
        } else {
            ?>
                <h2><strong>Le carton a-t-il été traité ?</strong></h2>
                <div class="buttons buttons--action">
                    <button class="button button--action button--primary" value="<?= base64_encode(serialize($yellow_cards_id)); ?>" id="card-not-resolved">Non</button>
                    <button class="button button--action button--secondary buttons__secondary save-event">Oui, déjà résolu</button>
                <?php
            }
                ?>

                </div>
            </div>