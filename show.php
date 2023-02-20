<?php require "includes/header.php"; ?>
<?php require "config.php"; ?>


<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $onePost = $conn->query("SELECT * FROM posts WHERE id='$id'");
    $onePost->execute();
    $posts = $onePost->fetch(PDO::FETCH_OBJ); 
}



$comments = $conn->query("SELECT * FROM comments WHERE post_id='$id'");
$comments->execute();
$comment = $comments->fetchAll(PDO::FETCH_OBJ);


$ratings = $conn->query("SELECT * FROM rates WHERE post_id='$id' AND user_id='$_SESSION[user_id]'");
$ratings->execute();
$rating = $ratings->fetch(PDO::FETCH_OBJ);





?>
<div class="row">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title"><?php echo $posts->title; ?></h5>
            <p class="card-text"><?php echo $posts->body; ?></p>
            <form id="form-data" method="POST">
                <div class="my-rating"></div>
                <input id="rating" type="hidden" name="rating" value="">
                <input id="post_id" type="hidden" name="post_id" value="<?php echo $posts->id; ?>">
                <input id="user_id" type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            </form>
        </div>
    </div>
</div>

<div class="row mt-3">
    <form method="POST" id="comment_data">
        <div class="form-floating">
            <input name="username" type="hidden" value="<?php echo $_SESSION['username']; ?>" class="form-control" id="username">
        </div>

        <div class="form-floating">
            <input name="post_id" type="hidden" value="<?php echo $posts->id; ?>" class="form-control" id="post_id">
        </div>

        <div class="form-floating">
            <textarea rows="9" name="comment" class="form-control" id="comment"></textarea>
            <label for="body1">Comment</label>
        </div>

        <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-5" type="submit">Create Comment</button>

        <div class="nothing" id="msg"></div>
        <div class="nothing" id="delete-msg"></div>

    </form>

</div>

<div class="row">
    <?php foreach ($comment as $singleComment) : ?>
        <div class="card mt-3">  
            <div class="card-body">
                <h5 class="card-title"><?php echo $singleComment->username; ?></h5>
                <p class="card-text"><?php echo $singleComment->comment; ?></p>
                <?php if (isset($_SESSION['username']) AND $_SESSION['username'] == $singleComment->username) : ?>
                    <button id="delete-btn" value="<?php echo $singleComment->id; ?>" class="btn btn-danger mt-3" type="submit">Delete Comment</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php require "includes/footer.php"; ?>
<script>
    $(document).ready(function() {


        $(document).on('submit', function(e) {
            // alert("form submitted!");
            e.preventDefault();
            var formdata = $("#comment_data").serialize() + '&submit=submit';

            $.ajax({
                type: 'post',
                url: 'insert-comments.php',
                data: formdata,
                success: function() {

                    $('#comment').val(null);
                    $('#username').val(null);
                    $('#post_id').val(null);

                    $("#msg").html("Added successfully!!").toggleClass("alert alert-success bg-success text-white mt-3")
                    fetch();
                }
            });

        });

        $("#delete-btn").on('click', function(e) {
            // alert("form submitted!");
            e.preventDefault();
            var id = $(this).val();

            $.ajax({
                type: 'post',
                url: 'delete-comments.php',
                data: {
                    delete: 'delete',
                    id: id
                },
                success: function() {

                    $("#delete-msg").html("Deleted successfully!!").toggleClass("alert alert-success bg-success text-white mt-3")
                    fetch();
                }



            });
        });



        function fetch() {
            setInterval(function() {
                $("body").load("show.php?id=<?php echo $_GET['id']; ?>")
            }, 4000);
        }

        // rating system
        $(".my-rating").starRating({
            starSize: 25,
            initialRating:<?php            
            if(isset($rating->rating) AND isset($rating->user_id) AND $rating->user_id==$_SESSION['user_id']){
                echo $rating->rating;
            } else {
                echo "0";
            }
            ?>,
            callback:function(currentRating, $el) {
                $("#rating").val(currentRating);

                $(".my-rating").click(function(e){
                    e.preventDefault();
                    var formdata=$("#form-data").serialize()+'&insert=insert';

                    $.ajax({
                        type:"POST",
                        url:'insert-rating.php',
                        data:formdata,
                        success: function(){
                                // alert(formdata);
                        },
                    });

                });
            },
        });
    });
</script>