<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc/codes.php';

/* Mustache include start */
$mustache = new Mustache_Engine(array(
    'template_class_prefix' => '__CallCenterTemplates_',
    'cache' => __DIR__ . '/tmp/cache/CallCenter',
    'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/inc/templates'),
));
/* Mustache include end */

$values = array(
    'firstname' => "",
    'lastname' => "",
    'code' => "",
    'telephone' => "",
    'minutes' => "",
    'agree' => "checked"
);

$error_messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $mustache->render('index', array('codes' => $countries, 'values' => $values, 'error' => false)) . PHP_EOL;
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (verify()) {
        save();
        die('Data byla uložena');
    } else {
        if (isset($_POST['firstname'])){ $values['firstname'] = $_POST['firstname']; }
        if (isset($_POST['lastname'])){ $values['lastname'] = $_POST['lastname']; }
        if (isset($_POST['code'])){ $values['code'] = $_POST['code']; }
        if (isset($_POST['telephone'])){ $values['telephone'] = $_POST['telephone']; }
        if (isset($_POST['minutes'])){ $values['minutes'] = $_POST['minutes']; }
        if (!isset($_POST['agree'])){ $values['agree'] = ""; }

        echo $mustache->render('index', array('codes' => $countries, 'values' => $values, 'error' => true, 'error_messages' => $error_messages)) . PHP_EOL;
    }
} else {
    die('Unsupported request type');
}

function verify() {
    global $error_messages;
    if (!isset($_POST['firstname'])) {
        $error_messages[] = "Jméno nebylo vyplněno!";
    } else {
        if ($_POST['firstname'] == "") {
            $error_messages[] = "Jméno nesmí být prázdné!";
        }
    }

    if (!isset($_POST['lastname'])) {
        $error_messages[] = "Příjmení nebylo vyplněno!";
    } else {
        if ($_POST['lastname'] == "") {
            $error_messages[] = "Příjmení nesmí být prázdné!";
        }
    }

    if (!isset($_POST['code'])) {
        $error_messages[] = "Telefonní předvolba nebyla vyplněna!";
    }

    if (!isset($_POST['telephone'])) {
        $error_messages[] = "Telefon nebyl vyplněn!";
    } else {
        if ($_POST['telephone'] == "") {
            $error_messages[] = "Telefon nesmí být prázdný!";
        } else {
            if (!is_numeric($_POST['telephone'])) {
                $error_messages[] = "Telefon musí být číslo!";
            }
        }
    }

    if (!isset($_POST['minutes'])) {
        $error_messages[] = "Počet minut nebyl vyplněn!";
    } else {
        if ($_POST['minutes'] == "") {
            $error_messages[] = "Počet minut nesmí být prázdný!";
        }
        if (!is_numeric($_POST['minutes'])) {
            $error_messages[] = "Počet minut musí být čísla!";
        } else {
            if ($_POST['minutes'] < 0) {
                $error_messages[] = "Počet minut nesmí být záporný!";
            }
        }
    }

    if (count($error_messages) == 0) {
        return true;
    } else {
        return false;
    }
}

function save() {
    $save_folder = __DIR__ . '/contacts/';

    if (!file_exists($save_folder)){
        mkdir($save_folder, 0777);
    }

    $index = 1;
    while (file_exists($save_folder . $_POST['firstname'] . '-' . $_POST['lastname'] . '-' . $index . '.txt')) {
        $index++;
    }
    $content = $_POST['firstname'] . "\t" . $_POST['lastname'] . "\t" . $_POST['code'] . "\t" . $_POST['telephone'] . "\t" . $_POST['minutes'] . "\t";
    if (isset($_POST['agree'])) {
        $content .= "ano";
    } else {
        $content .= "ne";
    }
    file_put_contents($save_folder . $_POST['firstname'] . '-' . $_POST['lastname'] . '-' . $index . '.txt', $content);
}