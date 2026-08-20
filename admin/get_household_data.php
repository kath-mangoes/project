<?php
include '../connection/config.php';

if (isset($_POST['household_head_id'])) {
    $hhID = intval($_POST['household_head_id']);

    // ✅ Fetch household head with resident info
    $sql = "SELECT thh.*, tr.*
            FROM tbl_household_head thh
            LEFT JOIN tbl_residents tr ON thh.user_id = tr.user_id
            WHERE thh.household_head_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hhID);
    $stmt->execute();
    $result = $stmt->get_result();
    $household = $result->fetch_assoc();

    // ✅ Fetch household members
    $sqlMembers = "SELECT 
                        r.res_id,
                        r.first_name, 
                        r.middle_name, 
                        r.last_name, 
                        r.birthday, 
                        r.is_registered_voter,
                        thr.thr_relationship
                   FROM tbl_household_relation thr
                   LEFT JOIN tbl_residents r 
                        ON thr.thr_user_id = r.user_id
                   WHERE thr.thr_head_id = ?";
    $stmt2 = $conn->prepare($sqlMembers);
    $stmt2->bind_param("i", $hhID);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $members = [];
    $total_members = 0;
    $total_voters = 0;
    $total_adults = 0;
    $total_minors = 0;

    while ($row = $result2->fetch_assoc()) {
        $age = '';
        if (!empty($row['birthday'])) {
            $birthDate = new DateTime($row['birthday']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
        }

        // ✅ Increment counters
        $total_members++;
        if ($row['is_registered_voter'] === 'Yes') {
            $total_voters++;
        }
        if ($age !== '') {
            if ($age >= 18) {
                $total_adults++;
            } else {
                $total_minors++;
            }
        }

        $members[] = [
            'fullname' => ucfirst($row['first_name']) . ' ' . 
                         ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . 
                         ucfirst($row['last_name']),
            'age' => $age,
            'relationship' => $row['thr_relationship']
        ];
    }

    // ✅ Return JSON including stats
    echo json_encode([
        'household'      => $household,
        'members'        => $members,
        'no_members'     => empty($members) ? true : false,
        'total_members'  => $total_members,
        'total_voters'   => $total_voters,
        'total_adults'   => $total_adults,
        'total_minors'   => $total_minors
    ]);
}
?>
