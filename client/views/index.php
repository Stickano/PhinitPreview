<?php

    # PHPinit
    echo'<div class="headerContainer">';
        echo'<div class="logoTextContainer">PHPinit</div>';
    echo'</div>';

    # Menu (left)
    echo'<div class="menuContainer">';
        foreach ($controller->getExamples() as $key) {
            echo'<div class="menuButtonContainer">';
                echo'<a href="#'.$controller->replaceSpaces($key['headline']).'"
                        class="menuButton">'.$key['headline'].'</a>';
            echo'</div>';
        }
    echo'</div>';

    # Fetch the welcome message
    echo'<div class="exampleContainer" style="margin-top:50px; text-align:justify; min-height:100%;">';
        echo $controller->getAnnouncement();
    echo'</div>';

    # Fetch the examples
    foreach ($controller->getExamples() as $key) {
        echo'<div id="'.$controller->replaceSpaces($key['headline']).'"
                style="height:30px;"></div>';
        echo'<div class="exampleContainer">';
            echo '<h1>'.$key['headline'].'
                    <a href="'.$key['file_location'].'" style="outline:none;" target="_blank">
                         <img src="media/dl.png" style="width:16px;">
                    </a>
                </h1>';
            echo'<p>';

                # Look for content to evaluate as PHP
                # ..what a fucking mess. TODO
                $position = $controller->sh->multiSearch($key['content'], '"""');
                if($position){
                    $example = $controller->splitExample($key['content'], $position);
                    foreach ($example as $sub) {
                        $subPos = $controller->sh->multiSearch($sub, '"""');
                        if (!empty($subPos)){
                            $end = $subPos[0][1] - $subPos[0][0];
                            echo nl2br(substr($sub, 0, $subPos[0][0]));
                            eval(substr($sub, $subPos[0][0]+3, $end-3));
                        }

                        # End of the example
                        if (empty($subPos))
                            echo nl2br($sub);
                    }
                }

                # If there's nothing to evaluate, just print out the example
                if(!$position)
                    echo nl2br($key['content']);

            echo'</p>';
        echo'</div>';
    }

?>