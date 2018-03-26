<?php

    # Phinit
    echo'<div class="headerContainer">';
        echo'<div class="logoTextContainer">Phinit</div>';
    echo'</div>';

    # Menu (left)
    echo'<div class="menuContainer">';
        foreach ($controller->getExamples() as $key) {
            echo'<div class="menuButtonContainer">';
                echo'<a href="#'.$key['headline'].'" class="menuButton">'.$key['headline'].'</a>';
            echo'</div>';
        }
    echo'</div>';

    # The assosiated examples when a category is chosen
    foreach ($controller->getExamples() as $key) {
        echo'<div class="exampleContainer" id="'.$key['headline'].'">';
            echo '<h1>'.$key['headline'].'</h1>';
            echo'<p>';
                # Look for content to evaluate as PHP
                $position = $controller->sh->multiSearch($key['content'], '"""');
                if($position){
                    $position = array_reverse($position);
                    foreach ($position as $pos => $value) {
                        $end = $value[1]-$value[0];
                        echo substr($key['content'],0, $value[0]);
                        eval(substr($key['content'],$value[0]+3, $end-3));
                        echo substr($key['content'], $value[1]+3);
                    }

                }

                # If there's nothing to evaluate, just print out the example
                if(!$position)
                    echo $key['content'];

            echo'</p>';
        echo'</div>';
    }

?>