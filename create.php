<?php require "includes/header.php"; ?>

<?php require "config.php"; ?>
<?php 
   if(!isset($_SESSION['username'])) {
    header("location: index.php");
  }

    if(isset($_POST['submit'])) {

      if($_POST['title'] == '' OR $_POST['body'] == '' ) {
        echo "some inputs are empty";
        
      } else {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $username = $_SESSION['username'] ;

        $insert = $conn->prepare("INSERT INTO posts(title, body, username) 
         VALUES (:title, :body, :username)");

         $insert->execute([
          ':title' => $title,
          ':body' => $body,
          ':username' => $username
         ]);
          header("location:index.php");
      }
    }


?>

<main class="form-signin w-50 m-auto">
  <form method="post" action="create.php">
    <h1 class="h3 mt-5 fw-normal text-center">Create Post</h1>
    <div class="form-floating">
      <input name="title" type="text" class="form-control" id="title" placeholder="title">
      <label for="title">Title</label>
    </div>

    <div class="form-floating">
        <textarea rows="9" name="body" placeholder="body" class="form-control" id="body1"></textarea>
      <label for="body1">Body</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary mt-5" type="submit">CreatePost</button>

  </form>
</main>

<?php require "includes/footer.php"; ?>