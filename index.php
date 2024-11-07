<?php
    session_start();
    include('database.php');
    include('tasks.php');
    $obj = new Tasks;
    
    if(isset($_POST['submit'])) {
        // para ma insert ang data
        $task = $_POST['task'];
        $id = $_POST['id'];
        $created_at = $updated_at = date("Y-m-d H:i:s");

        //para ma update
        if(!empty($id)) {
            $sql = "UPDATE todolists set task = '".$task."', updated_at = '".$updated_at."' where id = ".$id;
            $res = $obj->executeQuery($sql);
            if($res) {
                $_SESSION['success'] = "Task has been update successfully";
            }
            else {
                $_SESSION['error'] = "Something went wrong, please try again later";
            }
        }   
        else {
            $sql = "INSERT INTO todolists (task, created_at, updated_at) VALUES ('".$task."', '".$created_at."', '".$updated_at."')";
            $res = $obj->executeQuery($sql);

            if($res) {
                $_SESSION['success'] = "Task has been created successfully";
            }
            else {
                $_SESSION['error'] = "Something went wrong, please try again later";
            }
        }
        
        session_write_close();
        header("LOCATION:index.php");
    }

    //para makuha tanan task
    $tasks = $obj->getAllTasks();

    //para makuha ang task
    $editing = false;
    if(isset($_GET['action']) && $_GET['action']  === 'edit') {
        $taskData = $obj->getTask($_GET['id']);
        $editing = true;
    }

    //para ma delete ang task
    if(isset($_GET['action']) && $_GET['action']  === 'delete') {
        $sql = "DELETE FROM todolists WHERE id = ".$_GET['id'];
        $res = $obj->executeQuery($sql);
        if($res) {
            $_SESSION['success'] = "Task has been deleted successfully";
        }
        else {
            $_SESSION['error'] = "Something went wrong, please try again later";
        }

        session_write_close();
        header("LOCATION:index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/des.css">
    <title>Todo List Project</title>
</head>
<body>
    <!--structure sa todo list-->
    <div class="container">
      <!--input and buttons-->
      <div id="newtask">
        <?php include('alert.php') ?>

        <div class="todo-text">TO DO LIST</div>
        
        <form action="index.php" method="post" id="taskform">
            <input type="hidden" name="id" value="<?php if($editing) { echo $taskData['id']; } ?>"  >
            <input type="text" name="task" id="task" placeholder="Tasks to do..." value="<?php if($editing) { echo $taskData['task']; } ?>" />
            <button type="submit" name="submit" id="add"><?php if($editing) { echo "Update"; } else { echo "Add" ; } ?></button>
        </form>
      </div>

      <div id="tasks">
        <?php
            if(!empty($tasks)) {
                foreach($tasks as $t) {
        ?>
        <div class="task">
            <span><?php echo $t['task'] ?></span>
            <a href="index.php?action=edit&id=<?php echo $t['id'] ?>" class="edit button"><img src="images/update_pic.png" class="update-img"><i class="fa fa-edit"></i></a>
            <a onclick="return confirm('Do you want to delete this record?')" href="index.php?action=delete&id=<?php echo $t['id'] ?>" class="delete button"><img src="images/delete_pic.png" class="delete-img"><i class="fa fa-trash-alt"></i></a>
        </div>
        <?php }} ?>
      </div>
    </div>

    <script src="app.js"></script>
</body>
</html>