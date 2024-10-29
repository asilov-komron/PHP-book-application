<?php
function find_user($data, $username)
{
    foreach ($data as $user) {
        if ($user['username'] == $username) {
            return $user;
        }
    }

    return null;
}


function search_book_title_by_id($books_data, $id)
{
    foreach ($books_data as $book) {
        if ($book['id'] == $id) {
            return $book['title'];
        }
    }
    return null;
}


function find_obj($array, $id)
{
    foreach ($array as $obj) {
        if ($obj['id'] == $id) {
            return $obj;
        }
    }

    return null;
}



?>