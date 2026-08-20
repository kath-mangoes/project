<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event details
    $query = "SELECT * FROM tbl_event WHERE event_id = $event_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Fetch comments related to the event
    $comment_query = "SELECT * FROM tbl_event_comments WHERE event_id = $event_id ORDER BY comment_date DESC";
    $comments_result = mysqli_query($conn, $comment_query);
} else {
    echo "No event selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details | Barangay 400</title>
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #EEEEEE;
            margin: 0;
            padding: 0;
            color: #041562;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        h1 {
            color: #DA1212;
        }

        .event-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 15px;
        }

        .meta {
            margin: 10px 0;
            color: #555;
            font-size: 14px;
        }

        .description {
            margin-top: 20px;
            line-height: 1.6;
        }

        .comments {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f8f8;
            border-left: 5px solid #004080;
            border-radius: 8px;
        }

        .comments h3 {
            margin-top: 0;
            color: #11468F;
        }

        .comment {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }

        .comment .position {
            font-weight: bold;
            color: #041562;
        }

        .comment .comment-date {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }

        .login-note {
            margin-top: 20px;
            font-style: italic;
            font-size: 14px;
            color: #555;
            text-align: center;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            color: #ff3e3e;
            text-decoration: none;
            font-weight: bold;
        }

        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?= htmlspecialchars($row['title']) ?></h1>
    <div class="meta">Posted on <?= date("F d, Y", strtotime($row['dateCreated'])) ?></div>
    <img src="../dist/assets/images/uploads/events/<?= htmlspecialchars($row['image']) ?>" class="event-image" alt="<?= htmlspecialchars($row['title']) ?>">
    
    <div class="description">
        <?= nl2br(htmlspecialchars($row['description'])) ?>
    </div>

    <div class="comments">
        <h3>📌 Comments</h3>
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <div class="comment">
                    <p class="position"><?= htmlspecialchars($comment['position']) ?></p>
                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    <div class="comment-date">Commented on <?= date("F d, Y", strtotime($comment['comment_date'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>

        <div class="login-note">Log in to comment</div>
    </div>

    <a class="back-link" href="https://barangay400.com/WEBSITE/a&e.php">← Back to Events</a>
</div>

</body>
</html>
