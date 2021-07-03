<?php
class CourseMoodle extends Model
{
    public function __construct()
    {
        $this->table = "mdl_course";
        $this->getConnexionMoodle();
    }


    // Find courses with teacher username in moodle BDD
    public function findByTeacher(string $teacher)
    {
        $sql = "SELECT c.fullname, ra.roleid, c.id 
                FROM " . $this->table . " as c JOIN mdl_context as ct ON c.id = ct.instanceid JOIN mdl_role_assignments as ra ON ra.contextid = ct.id JOIN mdl_user as u ON u.id = ra.userid JOIN mdl_role as r ON r.id = ra.roleid WHERE u.username = '" . $teacher . "' AND (ra.roleid = '3' OR ra.roleid = '4') AND c.id IN (SELECT courseid FROM mdl_groups GROUP BY courseid)";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Find group in moodle course
    public function group(int $id_course)
    {
        $sql =    "	SELECT u.firstname, u.lastname, u.username, g.name, g.id
					FROM " . $this->table . " as c
					JOIN mdl_context as ct
					ON c.id = ct.instanceid
					JOIN mdl_role_assignments as ra
					ON ra.contextid = ct.id
					JOIN mdl_user as u
					ON u.id = ra.userid
					JOIN mdl_role as r
					ON r.id = ra.roleid
					JOIN mdl_groups_members as gm
					ON u.id = gm.userid
					JOIN mdl_groups as g
					ON gm.groupid = g.id
					AND g.courseid = c.id
					WHERE ra.roleid = '5'
					AND c.id = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
