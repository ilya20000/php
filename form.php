<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^E_NOTICE);

$searchtext = '';
$posts = array();

if(isset($_POST['searchtext'])){

    $searchtext = trim ( $_POST['searchtext'] );
    $serchtext = addslashes($searchtext);
}

?>

<html>
<head>
    <title>HTML-форма поиска</title>
</head>
<body>
<form aсtion="form.php" method="post">
    <table>
        <tr><td>Комментарий:</td><td><input name="searchtext" minlength=3 maxlength=13 size=13 value="<?php echo $searchtext; ?>"></td></tr>
        <tr><td colspan=1><input type="submit" value="Найти"></td></tr>
    </table>
</form>

<table border='1'>
<tr>
<th>Заголовок</th>
<th>Коментарий</th>    
</tr>

<?php

if ( strlen($searchtext) > 2 ){

    $db = mysqli_connect("localhost", "root", "123", "test");
    if ( !$db ){
        die ("Невозможно подключение к MySQL");
    }
    $query = "SELECT posts.title as ptitle, comments.body as cbody FROM comments INNER JOIN posts ON posts.id = comments.postId WHERE comments.body like '%".$searchtext."%';";

    if ($result = $db->query($query)) {
        while($obj = $result->fetch_object()){
?>
<tr>
    <td>
    <?php echo $obj->ptitle; ?>
    </td>
    <td>
    <?php echo $obj->cbody; ?>
    </td>
</tr>

<?php
        }
    }
    $result->close();
    unset($obj);
    unset($query); 
    
}

?>
</table>
</body>
</html>