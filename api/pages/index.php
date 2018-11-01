 <?php 
include("../../wp-config.php");
global $wpdb;
//Defining varables
$data_body = json_decode(file_get_contents("php://input"), true);
$userID = $_GET['userID'];
$pageID = $_GET['pageID'];
$language = $_GET['language'];

/* if ($userID == "") {
    // $json = array("success" => 0, "result" => 0, "error" => "Todos los campos son obligatorios");
    echo "Todos los campos son obligatorios";
} else {
    $user = get_user_by('ID', $userID);
   
    if (empty($user)) {
        echo "Usuario Inválido";
        // $json = array("success" => 0, "result" => 0, "error" => "Usuario Inválido");
    } else { */
        $post = get_post($pageID);
        if(empty($post)) {
            echo "Página no válida";
            // $json = array("success" => 0, "result" => 0, "error" => "Página no válida");
        } else {
            echo $content = apply_filters('translate_text', $post->post_content, $lang = $language, $flags = 0);
            // $json = array("success" => 1, "result" => stripslashes($content), "error" => "No se ha encontrado ningún error");
        }
/*     }
} */
// echo json_encode($json);
?>