        <!-- for delete threads -->
<?php
    include '_dbconnect.php';
        $name = $_GET['name'];
        if ($name == 'thread') {
            # code...
            $idt = $_GET['threadid'];
            $id = $_GET['catid'];
            $sql = "DELETE FROM `threads` WHERE `threads_id` = $idt";
            mysqli_query($conn, $sql);
            header ("location: /FORUM/threadlist.php?catid= $id");
        }

        //  for delete comments
        else if ($name == 'comment') {
            # code...
            $id = $_GET['commentid'];
            $idt = $_GET['threadid'];
            $sql = "DELETE FROM `comments` WHERE `comment_id` = $id";
            mysqli_query($conn, $sql);
            header ("location: /FORUM/comments.php?threadid= $idt");
        }
    ?>