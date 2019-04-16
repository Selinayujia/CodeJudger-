<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loader.css">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <div class="d-flex flex-column"><br>
                <div class="p-2 justify-content-start d-md-inline-flex problem problem-name"><?php echo ucfirst($problem_name);?></div><br>
                <div class="p-2 justify-content-start d-md-inline-flex problem "><strong>Problem Description</strong></div><br>
                <div class="p-2 justify-content-start d-md-inline-flex problem problem-desc"><?php echo $desc;?></div><br>
                
                <div class="p-2 justify-content-start d-md-inline-flex problem"><strong>Sample Input</strong></div><br>
                <div class="p-2 justify-content-start d-md-inline-flex problem problem-sample_input"><?php echo $sample_input;?></div><br>

                <div class="p-2 justify-content-start d-md-inline-flex problem"><strong>Sample Output</strong></div><br>
                <div class="p-2 justify-content-start d-md-inline-flex problem problem-sample_output"><?php echo $sample_output;?></div><br>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="language-select" style="margin: 10px">
                <select onchange="change_session()" id="select_language" class="styled-select semi-square gray">
                    <option value="cpp">Cpp</option>
                    <option value="py">Python</option>
                    <option value="java">Java</option>
                </select>
            </div>
            <div>
                <pre id="code" class="ace_editor" style="min-height:500px"><textarea class="ace_text-input">
                </textarea></pre>

                <script>
                editor = ace.edit("code");

                theme = "monokai";
                language = "c_cpp";
                editor.setTheme("ace/theme/" + theme);
                editor.session.setMode("ace/mode/" + language);

                editor.setFontSize(18);

                editor.setReadOnly(false);

                editor.setOption("wrap", "free")

                ace.require("ace/ext/language_tools");
                editor.setOptions({
                    enableBasicAutocompletion: true,
                    enableSnippets: true,
                    enableLiveAutocompletion: true
                });
                </script>
                <button type="button" class="btn btn-primary" style="margin-top: 20px;" onclick=submit()>Submit</button>
            </div>

            <div class="spinner" id="loader">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            <div class="mt-5" id="result"></div>

        </div>
    </div>
</div>

<script>
window.onload=change_session();
function change_session() {
    var language = document.getElementById('select_language').value;
    var lan_mode = language;

    if (language == 'cpp') lan_mode = 'c_cpp';
    if (language == 'py') lan_mode = 'python';

    editor.session.setMode("ace/mode/" + lan_mode);
    console.log("<?=$problem_name?>", language);
    var code;
    $.ajax({
        type: "POST",
        url: "<?=base_url('skeleton/get_skeleton_code')?>",
        crossDomain: true,
        data: {
            problem: "<?=$problem_name?>",
            type: language
        },
        success: function(skeleton_code) {                 
            code = skeleton_code;
            editor.setValue("");
            editor.insert(code);
            console.log(skeleton_code);
        },
        error: function(data) {
            $("#loader").hide();
            console.log(data);
        }
    });
    
}

function html_to_append(alert_type, data){
    if (data["status"] == "Compile Error") {
        return $("#result").html("<div class=\"alert alert-"+alert_type+"\"  role=\"alert\">\
                <p >"+data["status"]+"<p>\
                <pre>"+"Error : "+data["error"]+"</pre>\
                </div>\
                ");
    } else {
        return $("#result").html("<div class=\"alert alert-"+alert_type+"\"  role=\"alert\">\
                <p >"+data["status"]+"<p>\
                <pre>"+"Total cases : "+data["total_case"]+"</pre>\
                <pre>"+"Correct cases : "+data["correct_case"]+"</pre>\
                <pre>"+"Input : "+data["input"]+"</pre>\
                <pre>"+"Your output : "+data["user_output"]+"</pre>\
                <pre>"+"Correct output : "+data["correct_output"]+"</pre>\
                <pre>"+"Error : "+data["error"]+"</pre>\
                </div>\
                ");
    }
}

function submit() {
    $("#result").hide();
    var language = document.getElementById('select_language').value;
    var code = editor.getValue();
    $("#loader").show();
    $.ajax({
        type: "POST",
        url: "<?=base_url('submission/submit')?>",
        crossDomain: true,
        data: {
            problem: "<?=$problem_name?>",
            code: code,
            type: language
        },
        dataType: "json",
        success: function(data, status, xhr) {                 
            $("#loader").hide();
            console.log(data);

            res = data["status"];
            totalcase = data["total_case"];
            correctcase = data["correct_case"];
            input = data["input"];
            output = data["user_output"];
            expected_output = data["correct_output"];
            error = data["error"];

            if (res == "Accepted") {
                html_to_append("success", data);
            } else if (res == "Compile Error") {
                html_to_append("warning", data);
            } else if (res == "Wrong Answer") {
                html_to_append("danger", data);
            } else if (res == "Time Limit Exceed") {
                html_to_append("secondary", data);
            } else if (res == "Runtime Error") {
                html_to_append("danger", data);
            } else {
                html_to_append("light", data);
            }
            $("#result").show();
        },
        error: function(data) {
            $("#loader").hide();
            console.log(data);
        }
    });
}
</script>

<script>
// Show an element
var show = function(elem) {
    elem.css.display = 'block';
};

// Hide an element
var hide = function(elem) {
    elem.css.display = 'none';
};

$("#loader").hide();
</script>
