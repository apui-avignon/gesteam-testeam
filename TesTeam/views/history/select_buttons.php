<form class="chart__filter">
    <select id="group">
        <?php if (empty($group)) { ?>
            <option value="" selected>Tous les groupes </option>
        <?php } else {
            $groups_id = array_column($groups, 'id');
            $key = array_search($group, $groups_id);
        ?>
            <option value="">Tous les groupes </option>
            <option value="<?= $group; ?>" selected> Groupe <?= $groups[$key]['name'] ?> </option>
        <?php } ?>
        <?php
        foreach ($groups as $groups) :
            if ($group != $groups['id']) {
        ?>
                <option value="<?= $groups['id']; ?>"> Groupe <?= $groups['name']; ?> </option>
        <?php
            }
        endforeach;
        ?>
    </select>

    <select id="student">

        <?php if (empty($student)) { ?>
            <option value="" selected>Tous les étudiants </option>
        <?php } else {
            $usernames = array_column($course_groups_more_informations, 'username');
            $key = array_search($student, $usernames);
        ?>
            <option value="" selected>Tous les étudiants </option>
            <option value="<?= $student; ?>" selected> <?= $course_groups_more_informations[$key]['firstname'] . ' ' . $course_groups_more_informations[$key]['lastname'] ?> </option>
        <?php } ?>
        <?php
        foreach ($course_groups_more_informations as $course_group_more_informations) :
            if ($student != $course_group_more_informations['username']) {
                if (!empty($group) && $group == $course_group_more_informations['id_group']) {
        ?>
                    <option value="<?= $course_group_more_informations['username'] ?>"> <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'] ?> </option>
                <?php } else if (empty($group)) { ?>
                    <option value="<?= $course_group_more_informations['username'] ?>"> <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'] ?> </option>
        <?php
                }
            }
        endforeach;
        ?>
    </select>
    <input id="course_id" name="course_id" type="hidden" value="<?= $course_parameters['id_course']; ?>">
    <button type="submit" id="filter" class="button button--action">GO</button>
</form>


<script>
    scriptGraphShowData = <?= json_encode($course_groups_more_informations); ?>;
</script>