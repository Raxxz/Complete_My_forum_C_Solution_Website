<?php
require '_dbconnect.php';
if (isset($_GET['email']) && isset($_GET['v_code'])) {
    $sql = "SELECT * FROM user WHERE `email` = '$_GET[email]' And `verification_code` = '$_GET[v_code]'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result)==1) {
            $result_fetch = mysqli_fetch_assoc($result); 
            if ($result_fetch['is_verified']==0) {
                $update = "UPDATE `user` SET `is_verified`='1' WHERE `email` = '$result_fetch[email]'";
                if (mysqli_query($conn, $update)) {
                    echo '
                        <script> 
                        alert("Email verified sucessfully");
                        window.location.href = "_login.php";
                        </script>
                    ';
                }
                else {
                    echo '
                        <script> 
                        alert("Cannot run query");
                        window.location.href = "_login.php";
                        </script>
                    ';  
                }
            }
            else {
                echo '
                    <script> 
                    alert("Email already verified");
                    window.location.href = "_login.php";
                    </script>
                ';
            }
        }
    }
    else {
        echo '
            <script> 
            alert("Cannot run query");
            window.location.href = "_login.php";
            </script>
        ';
    }
}
?>