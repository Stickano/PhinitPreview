
$(document).ready(function(){

    // Auto hide elements
    function hideElements(){
        $("#contentGuide").hide();
    };
    hideElements();

    // Open/close editor panel
    $("#openEditorButton").click(function(){
        $("#addForm").slideToggle(200, function(){
            $("#contentGuide").hide();
            $("#fileText").text("Select a file");
        });

        // Change the value of the add url button (close)
        var open = $("#openEditorButton");
        if(open.text() === 'Add'){
          open.text('Close');
          open.prop('title','Close');
        } else {
          open.text('Add');
          open.prop('title','New');
        }
        return false;
    });

    // For file input, show the value of the file
    $("#fileInput").change(function (){
        var newVal = this.value.replace(/^.*\\/, "");
        $("#fileText").text(newVal);
    });

    // Open/Close content guide (help)
    $("#contentGuideButton").click(function (){
        $("#contentGuide").toggle();
        var open = $("#contentGuideButton");
        if(open.text() === 'Help'){
          open.text('Close');
          open.prop('title','Close');
        } else {
          open.text('Help');
          open.prop('title','');
        }
        return false;
    });
});
