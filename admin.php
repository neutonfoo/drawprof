<?php
require 'requires/core.php';

// Not logged in / Login Page
if(!isset($_SESSION['adminId'])) {
  require 'adminPages/login.php';
} else {

  // Main Admin Page
  if(!isset($_GET['page'])) {
    require 'adminPages/main.php';

  } else {
    // Action is defined
    require 'dbconfig.php';
    $page = $_GET['page'];

    if($page == "approvesubmissions") {
      // Approve Posts
      require 'adminPages/approvesubmissions.php';

    } else if($page == "hallofshame") {
      // Hall of Shame
      require 'adminPages/hallofshame.php';

    } else if($page == "changepassword") {
      // Change Password
      require 'adminPages/changepassword.php';

    } else if($page == "createadminaccount") {
      // Create Admin Account
      require 'adminPages/createadminaccount.php';

    } else if($page == "todo") {
      // Todo list
      require 'adminPages/todo.php';

    } else {
      header("Location: ?");
    }
  }
}
