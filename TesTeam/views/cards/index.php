<h2 class="h1"> Cartons rouges </h2>
<p> Retrouver ici la liste des cartons rouges par élève et par cours. Vous pouvez trier cette liste par cours et pas nom et par statut. Enfin vous pouvez résoudre chaque situation. </p>
<?php

if (count($cards) != 0) {
?>
    <div id="users-alert">
        <table id="table-red-cards">
            <thead>
                <tr>
                    <th class="sort col--large" data-sort="sort--course">
                        Cours
                    </th>
                    <th class="sort" data-sort="sort--group">
                        Groupe
                    </th>
                    <th class="sort col--large" data-sort="sort--name">
                        Nom
                    </th>
                    <th class="sort" data-sort="sort--archive">
                        Archive
                    </th>
                    <th class="sort" data-sort="sort--status">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="list">
                <?php

                foreach ($cards as $card) :
                ?>
                    <tr>
                        <td class="sort--course col--large">
                            <span class="hide reference"><?= $card['id_course'] ?></span>
                            <a href="#" class="link--course button--show" data-id="<?= $card['id_course'] ?>">
                                <?= $card['course'] ?>
                            </a>
                        </td>
                        <td>
                            <span class="hide reference"><?= $card['id_course'] ?></span>

                            <span class="sort--group"><?= $card['id_group'] ?></span>
                            <a href="#" class="group-graphic" data-id="<?= $card['id_group'] ?>">
                                <?= $card['name'] ?>
                            </a>
                        </td>
                        <td class="sort--name col--large" data-name="<?= $card['username'] ?>">
                            <a href="#" class="student-graphic" data-id="<?= $card['username'] ?>">
                                <?= $card['firstname'] . " " . $card['lastname'] ?>
                            </a>
                        </td>
                        <td class="eval eval--old eval--archive">
                            <?php
                            if ($card['total_red_card'] > 1) {
                            ?>
                                <div class="alerts__button sort--archive">

                                    <?= $card['total_red_card'] - 1; ?>
                                </div>
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php if (empty($card['deactivation_date'])) : ?>
                                <button value="<?= $card['id']; ?>" class="button red-card solve button--red sort--status" type="submit"><span>Voir</span></button>
                            <?php else : ?>
                                <button value="<?= $card['id']; ?>" type="submit" class="button red-card solve sort--status"><span>✔ Résolu</span></button>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo '<p class="message message--panel">Pas de données</p>';
} ?>