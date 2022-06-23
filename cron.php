<?php 

require_once(__DIR__ . '/include.php');

if(isset($_GET['action']) && $_GET['action'] == "shutterCamera") {

    $cameras = ['7gfXZn7qOAfNa9cqbYfq4zgk4mmpHmUa', 'oD4VSwslLfKupssM4y6UskJN4DxjAPEG'];

    foreach($cameras as $camera) {

        if(isset($_GET['type'])) {
            $data = array(
                    "action" => $_GET['type']
                );
    
            $data_json = json_encode($data);
    
            $response = generateCurl("https://api.myfox.io/v3/site/".$site_id."/device/". $_GET['id']."/action?access_token=".$access_token, $data_json, 'POST');
    
        }

    }

    

}