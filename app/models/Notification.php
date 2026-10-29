<?php

require_once __DIR__ . "/../Helpers/database_helper.php";

class Notification
{

    private $conn;

    public function __construct()
    {
        $this->conn = db();
    }


    // Return all notifications
    public function all()
    {

        $stmt = $this->conn->prepare("

        SELECT

            notifications.*,

            batches.batch_number,

            drugs.drug_name

        FROM notifications

        LEFT JOIN batches

        ON notifications.batch_id = batches.batch_id

        LEFT JOIN drugs

        ON batches.drug_id = drugs.drug_id

        ORDER BY notifications.created_at DESC

        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }



    // Count unread notifications
    public function unreadCount()
    {

        $stmt = $this->conn->query("

        SELECT COUNT(*)

        FROM notifications

        WHERE is_read = 0

        ");

        return $stmt->fetchColumn();

    }



    // Mark all notifications as read
    public function markAllRead()
    {

        $stmt = $this->conn->prepare("

        UPDATE notifications

        SET is_read = 1

        WHERE is_read = 0

        ");

        return $stmt->execute();

    }
public function generateNotifications()
{

    // ===============================
    // EXPIRING SOON (30 DAYS)
    // ===============================

    $stmt = $this->conn->query("

        SELECT

            batches.batch_id,
            batches.batch_number,
            batches.expiry_date,
            drugs.drug_name

        FROM batches

        INNER JOIN drugs

        ON batches.drug_id = drugs.drug_id

        WHERE batches.expiry_date
        BETWEEN CURDATE()
        AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)

    ");

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
    {

        $check = $this->conn->prepare("

            SELECT notification_id

            FROM notifications

            WHERE batch_id = ?

            AND notification_type = 'Expiring Soon'

        ");

        $check->execute([$row['batch_id']]);

        if(!$check->fetch())
        {

            $insert = $this->conn->prepare("

                INSERT INTO notifications

                (
                    batch_id,
                    notification_type,
                    message,
                    is_read
                )

                VALUES(?,?,?,0)

            ");

            $message =
            $row['drug_name'] .
            " batch " .
            $row['batch_number'] .
            " expires on " .
            $row['expiry_date'];

            $insert->execute([

                $row['batch_id'],
                "Expiring Soon",
                $message

            ]);

        }

    }
// ===============================
// EXPIRED MEDICINES
// ===============================

$stmt = $this->conn->query("

SELECT

    batches.batch_id,
    batches.batch_number,
    batches.expiry_date,
    drugs.drug_name

FROM batches

INNER JOIN drugs

ON batches.drug_id = drugs.drug_id

WHERE batches.expiry_date < CURDATE()

");

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
{

    $check = $this->conn->prepare("

    SELECT notification_id

    FROM notifications

    WHERE batch_id=?

    AND notification_type='Expired'

    ");

    $check->execute([$row['batch_id']]);

    if(!$check->fetch())
    {

        $insert = $this->conn->prepare("

        INSERT INTO notifications

        (
            batch_id,
            notification_type,
            message,
            is_read
        )

        VALUES(?,?,?,0)

        ");

        $insert->execute([

            $row['batch_id'],

            "Expired",

            $row['drug_name'] .
            " batch " .
            $row['batch_number'] .
            " expired on " .
            $row['expiry_date']

        ]);

    }
// LOW STOCK MEDICINES

$stmt=$this->conn->query("

SELECT

    drugs.drug_name,

    SUM(batches.quantity_remaining) AS stock


FROM batches


INNER JOIN drugs

ON batches.drug_id = drugs.drug_id


GROUP BY drugs.drug_id


HAVING stock < 10


");

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
{

    $check = $this->conn->prepare("

    SELECT notification_id

    FROM notifications

    WHERE batch_id=?

    AND notification_type='Low Stock'

    ");

    $check->execute([$row['batch_id']]);

    if(!$check->fetch())
    {

        $insert = $this->conn->prepare("

        INSERT INTO notifications

        (
            batch_id,
            notification_type,
            message,
            is_read
        )

        VALUES(?,?,?,0)

        ");

        $insert->execute([

            $row['batch_id'],

            "Low Stock",

            $row['drug_name'] .
            " has only " .
            $row['quantity_remaining'] .
            " items remaining."

        ]);

    }
// ===============================
// OUT OF STOCK
// ===============================

$stmt = $this->conn->query("

SELECT

batches.batch_id,

batches.batch_number,

drugs.drug_name

FROM batches

INNER JOIN drugs

ON batches.drug_id = drugs.drug_id

WHERE batches.quantity_remaining=0

");

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row)
{

    $check = $this->conn->prepare("

    SELECT notification_id

    FROM notifications

    WHERE batch_id=?

    AND notification_type='Out of Stock'

    ");

    $check->execute([$row['batch_id']]);

    if(!$check->fetch())
    {

        $insert = $this->conn->prepare("

        INSERT INTO notifications

        (
            batch_id,
            notification_type,
            message,
            is_read
        )

        VALUES(?,?,?,0)

        ");

        $insert->execute([

            $row['batch_id'],

            "Out of Stock",

            $row['drug_name'] .
            " is now out of stock."

        ]);

    }

}
}
}
}

}