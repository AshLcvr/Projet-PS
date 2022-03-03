<?php

function getAllPDO($table){
    global $pdo;
    $sql = "SELECT * FROM $table ";
    $query = $pdo->prepare($sql);
    $query -> execute();
    return $query->fetchAll();
}

function urlRemovelast($url) {
    $url = explode('/', $url);
    array_pop($url);
    return implode('/', $url);
}

function debug($tableau) {
    echo '<pre style="height: 200px;overflow-Y: scroll;font-size: 0.7rem;padding: 0.6rem;font-familly: Consolas, Monospace;background-color: black;color: white;text-align: left;">';
        print_r($tableau);
    echo '</pre>';
}

function validateText($errors, $value, $key, $min, $max){
    if(!empty($_POST['submitted'])){
        if(!empty($value)){
            if(mb_strlen($value) < $min || mb_strlen($value) > $max){
                $errors[$key] = 'Ce champ doit comporter '.$min.' à '.$max.' caractères';
            }
        }else{
            $errors[$key] = 'Veuillez renseigner ce champ!';
        }
    }
    return $errors;
}



function validateEmail($errors, $value, $key ){
    if(!empty($value)) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = 'Veuillez renseigner un email valide';
        }
    } else {
        $errors[$key] = 'Veuillez renseigner un email';
    }
    return $errors;
}

function valueNoReset($key){
    if(!empty($_POST[$key])){
        echo $_POST[$key];
    }
}


function errorTexte($erreur, $donnes, $key, $min, $max) {
    if (!empty($donnes)) {
        if (mb_strlen($donnes) > $max) {
            $erreur[$key] = 'le champ doit faire max '.$max.' caract';
        }
        elseif (mb_strlen($donnes) < $min) {
            $erreur[$key] = 'le champ doit faire plus de '.$min.' caract';
        }
    }else{
        $erreur[$key] = 'veuillez renseigner le '.$key.'';
    }
    return $erreur;
}

function errorNumber($erreur, $donnes, $key, $max) {
    if (!empty($donnes)) {
        if (!filter_var($donnes, FILTER_VALIDATE_INT)) {
            $erreur[$key] = 'veuillez renseigner un entier';
        }
        elseif ($donnes < 1) {
            $erreur[$key] = 'veuillez renseigner un '.$key.' d\'au moin 1 ans';
        }
        elseif ($donnes > $max) {
            $erreur[$key] = 'veuillez renseigner un '.$key.' cohérent';
        }
    }else {
        $erreur[$key] = 'veuillez renseigner l\''.$key.'';
    }
    return $erreur;
}

function errorSelect($erreur, $donnes1, $donnes2, $key) {

    if (!array_key_exists($donnes1, $donnes2)) {
        $erreur[$key] = 'Veuillez choisir un '.$key.'';
    }

    return $erreur;
}

function errorRadios($erreur, $donnes, $key, $key2) {
    if (empty($_POST[$key])) {
        $erreur[$key] = 'Veuillez renseigner un '.$key2.'';
    } else {
        $donnes = trim(strip_tags($_POST[$key]));
    }
    return $erreur;
}

function getArticleById($id){
    global $pdo;
    $sql = "SELECT * FROM blog_articles WHERE ID = :id";
    $query = $pdo->prepare($sql);
    // proctection injection sql
    $query->bindValue(':id',$id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch();
}

function label($for, $content) {
    $html = '';
    $html = '<label for="'.$for.'">'.$content.'</label>';
    return $html;
}

function spanError($key, $erreur) {
    $html = '';
    $html .= '<span class="error" style="color:red;font-sizr:0.8rem;">';
        if (!empty($erreur[$key])) { 
            $html.= $erreur[$key];
        }
    $html .= '</span>';
    return $html;
}

function inputTextAdd($key, $donnes) {
    $html = '';
    $html.= '<input  type="text" name="'.$key.'" id="'.$key.'" value="';
    if (!empty($donnes)) {
        $html.= $donnes;  
        }
    $html.= '">';
    return $html;
}

function inputTextEdit($key, $donnes, $newDonnes) {
    $html = '';
    $html.= '<input type="text" name="'.$key.'" id="'.$key.'" value="';
    if (!empty($donnes)) {
        $html .= $donnes;  
    }else{
        $html .= $newDonnes[$key];
    }
        $html.= '">';
    return $html;
}

function inputRadioAdd($donnes, $clef, $tableau, $textP) {
    $html = '';  
    $html.= '<p>Selectionner un '.$textP.'</p>';
         foreach ($tableau as $key => $elemtableau) {
                $html.= '<div class="minibloc">';
                    $html.= '<input type="radio" id="'.$clef.'" name="'.$clef.'" value="'.$key.'"';
                    if (!empty($donnes) && $donnes == $key ) {
                        $html.= 'checked'; 
                    }
                    $html .= '>';
                    $html.= '<label for="'.$clef.'">'.$elemtableau.'</label>';
                $html.= '</div>';
            }
    return $html;
}

function inputRadioEdit($donnes, $newDonnes, $clef, $tableau, $textP) {
    $html = '';  
    $html.= '<p>Selectionner un '.$textP.'</p>';
         foreach ($tableau as $key => $elemtableau) {
                $html.= '<div class="minibloc">';
                    $html.= '<input type="radio" id="'.$clef.'" name="'.$clef.'" value="'.$key.'"';
                     if (!empty($donnes) && $donnes == $key ) {
                        $html.= 'checked>'; 
                    }elseif (!empty($newDonnes) && $newDonnes[$clef] == $elemtableau) {
                        $html .= 'checked';
                    }
                    $html .= '>';
                    $html.= '<label for="'.$clef.'">'.$elemtableau.'</label>';
                $html.= '</div>';
            }
    return $html;
}

function inputSelectAdd($donnes, $clef, $tableau) {
    $html = '';
            $html .= '<select name="'.$clef.'" id="'.$clef.'">';
                $html .= '<option value="">--Choisir un '.$clef.'--</option>';
                foreach ($tableau as $key => $elemtableau) {
                    $html .= '<option value="'.$key.'"'; if (!empty($donnes) && $donnes == $key ) { $html .='selected'; }
                    $html .= '>'.$elemtableau.'</option>';
                }
            $html .= '</select>';
    return $html;
}

function inputSelectEdit($donnes, $newDonnes, $clef, $tableau) {
    $html = '';
            $html .= '<select name="'.$clef.'" id="'.$clef.'">';
                $html .= '<option value="">--Choisir un '.$clef.'--</option>';
                foreach ($tableau as $key => $elemtableau) {
                    $html .= '<option value="'.$key.'"';
                    if (!empty($donnes) && $donnes == $key ) { 
                        $html .= 'selected'; 
                    }elseif (!empty($newDonnes) && $newDonnes[$clef] == $elemtableau) {
                        $html .= 'selected';
                    }
                    $html .= '>'.$elemtableau.'</option>';
                }
            $html .= '</select>';
    return $html;
}

function inputNumberAdd($donnes, $clef) {
    $html = '';
        $html .= '<input type="number" min="1" name="'.$clef.'" id="'.$clef.'" value="';
        if (!empty($donnes)) {  $html .= $donnes;  }
        $html .= '">';
    return $html;
}

function inputNumberEdit($donnes, $newDonnes, $clef) {
    $html = '';
        $html .= '<input type="number" min="1" name="'.$clef.'" id="'.$clef.'" value="';
        if (!empty($donnes)) {  $html .= $donnes;  }else{ $html .= $newDonnes[$clef]; }
        $html .= '">';
    return $html;
}



function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function islogged() {  
    if (!empty($_SESSION['user'])) {
        if (!empty($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
            if (!empty($_SESSION['user']['pseudo'])) {
                if (!empty($_SESSION['user']['email'])) {
                    if (!empty($_SESSION['user']['role'])) {
                        if (!empty($_SESSION['user']['ip']) && $_SESSION['user']['ip'] === $_SERVER['REMOTE_ADDR']) {
                            return true;
                        }
                    }
                }
            }
        }
    }
    return false;
}

function isloggedAdmin() {
    if (islogged()) {
        if ($_SESSION['user']['role'] === 'admin') {
            return true;
        }
    }
    return false;
}

function pagination($page,$num,$count) {
    echo '<ul class="pagination">';
    if($page > 1) {
        echo '<li><a href="index.php?page='. ($page - 1 ) . '"> <- </a></li>';
    }
    if($page*$num < $count) {
        echo '<li><a href="index.php?page='. ($page + 1 ) . '"> -> </a></li>';
    }
    echo '</ul>';
}