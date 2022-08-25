<?php
//
// Скрипт скачивает json посты и коментарии и добавляет их в базу mysql
//

$url_posts="https://jsonplaceholder.typicode.com/posts";
$url_comments="https://jsonplaceholder.typicode.com/comments";

$posts_count = 0;
$comments_count = 0;

$db = mysqli_connect("localhost", "root", "123", "test");

if ( !$db ){
    die ("Невозможно подключение к MySQL");
}


function get_url_content($url){
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


$posts_json = get_url_content($url_posts);
$posts_obj = json_decode($posts_json);

foreach ($posts_obj as $post) {

    $userId = htmlspecialchars($post->userId);
    $title = htmlspecialchars($post->title);
    $body = htmlspecialchars($post->body);
    $query = "INSERT INTO posts (userId, title, body) VALUES ($userId, '$title', '$body');";
    if($db->query($query)){
        $posts_count++;
    }
}


$comments_json = get_url_content($url_comments);
$comments_obj = json_decode($comments_json);

foreach ($comments_obj as $comment) {

    $postId = htmlspecialchars($comment->postId);
    $name = htmlspecialchars($comment->name);
    $email = htmlspecialchars($comment->email);
    $body = htmlspecialchars($comment->body);
    $query = "INSERT INTO comments (postId, name, email, body) VALUES ($postId, '$name', '$email', '$body');";
    if($db->query($query)){
        $comments_count++;
    }
}



echo "Загружено $posts_count записей и $comments_count комментариев"
?>