<?php
if (isset($_SESSION["status"]) && $_SESSION["status"]=="admin") {
    echo '<h4><a href="../" target="_blank">Web site News portal</a>';
    echo ' &#187 <a href="./">Start admin</a>';
    echo ' &#187 <a href="categoryAdmin">News categories</a>';
    echo ' &#187 <a href="newsAdmin">News List</a>';
    echo '</h4>';
} else {
    echo '<h4> у вас нет прав!</h4>';
}
?>