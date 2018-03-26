<?php

if($controller->getError())
    echo $controller->getError();

if(isset($_POST['addExample']))
    $controller->addExample();

echo'<div class="container">';

    # Menu (header)
    echo'<div class="menuContainer">';
        echo'Phinit CMS';
        if (!isset($_GET['edit']) && !isset($_GET['add']))
            echo'<a href="index.php?add" class="addButton">Add</a>';
        else
            echo'<a href="index.php" class="addButton">Close</a>';
    echo'</div>';

    # Add/Edit container
    if (isset($_GET['edit']) || isset($_GET['add'])){
        echo'<div class="editorContainer">';
            echo'<form method="post" id="addForm" enctype="multipart/form-data">';

                # Headline
                echo'<span class="contentTxt">Headline</span>';
                echo'<input type="text" class="inputs" value="'.$controller->getViewInputs()['headline'].'" name="headline" id="headline" />';

                # Model upload
                echo'<div id="fileInputContainer">';
                    echo'<span class="contentTxt">Model</span>';
                    echo'<div class="input-file"><span id="fileText">Select a file</span> <input type="file" id="fileInput" name="file"/></div>';
                echo'</div>';

                # Content
                echo'<span class="contentTxt">Content</span>';
                echo'<button class="addButton" id="contentGuideButton">Help</button>';
                echo'<div id="contentGuide">';
                    echo'<p class="contentTxt">';
                        echo'You have a few additional options when adding new content. Use one of the following commands for your desired effect;';
                        echo'<table class="contentExample"><tr>';
                        echo'<td>';
                            echo"'''<br>Code example<br>'''";
                        echo'</td><td>';
                            echo"Encapsulate using 3 single-quotes will generate a codebox - This is for showing how to invoke your feature. Completly fucking useless considering HTML is allowed.. Also, you need to format/encode the examples. A &lt;div&gt; has to be escaped so it won't be an actual div.. Fucking stupid, useless peace of shit.";
                        echo'</td>';
                        echo'</tr><tr>';
                        echo'<td>';
                            echo'"""<br>Method to execute<br>"""';
                        echo'</td><td>';
                            echo"Encapsulate using 3 double-quotes will run a specific method (PHP). Just type in the name of the method - leave out the ();";
                        echo'</td>';
                        echo'</tr><tr>';
                        echo'<td>';
                            echo'`Highlight message`';
                        echo'</td><td>';
                            echo"Encapsulate with prime to highlight/mark out text.";
                        echo'</td>';
                        echo'</tr><tr>';
                        echo'<td>';
                            echo'HTML/CSS';
                        echo'</td><td>';
                            echo"You are able to write HTML/CSS, so you can create CSS previews.";
                            echo'</tr><tr>';
                        echo'<td>';
                            echo'Headlines';
                        echo'</td><td>';
                            echo"H1 is used as the main headline. Use H3 for sub-headlines.";
                        echo'</td>';
                        echo'</tr></table>';
                    echo'</p>';
                echo'</div>';

                echo'<textarea rows="30" class="inputs" id="contentInput" name="content">'.$controller->getViewInputs()['content'].'</textarea>';
                echo'<input type="submit" class="inputSubmit" name="addExample"/>';

            echo'</form>';
        echo'</div>';
    }

    # All examples (list)
    $br = 0;
    echo'<table style="width:100%;">';
    echo'<tr>';
    foreach ($controller->getExamples() as $key) {
        $bg = "white";
        $br++;
        if ($br == 2){
            $bg = "#f1f1f1";
            $br = 0;
        }
        echo'<td class="examplesTd" style="background-color:'.$bg.';">';
            echo "<a href='index.php?edit=".$key['id']."' title='Edit Example'>".$key['headline']."</a>";
        echo'</td>';
        echo'</tr><tr>';
    }
    echo'</tr>';
    echo'</table>';

echo'</div>';

?>
