
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.3.3/ace.js" type="text/javascript" charset="utf-8"></script>

<div class="container"><br>
	<p class="problem problem-name"><?php ucfirst($problem_name);?></p><br>
    <p ><strong>Problem Description</strong></p>
    <p class="problem problem-desc"><?php echo $desc;?></p><br>
    <p ><strong>Sample Input</strong></p>
    <p class="problem problem-sample_input"><?php echo $sample_input;?></p><br>
    <p ><strong>Sample Output</strong> </p>
    <p class="problem problem-sample_output"><?php echo $sample_output;?></p><br>
</div>
<div class="container language-select">
	<select onchange="change_session()" id="select_language" class="styled-select semi-square gray">
		<option value="cpp">cpp</option>
		<option value="javascript">javascript</option>
		<option value="python">python</option>
		<option value="java">java</option>
		<option value="c">c</option>
	</select>	
</div><br>

<div class="container">
    <div id="editor" class="container" style="height: 300px; padding-right: 50px;"></div>
    <button type="button" class="btn btn-primary" style="margin-top: 20px;" onclick=submit()>Submit</button>
</div>

<script>
	var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    document.getElementById('editor').style.fontSize='17px';
    code = "#include<iostream>\nusing namespace std;\nint main() {\n\tint a, b;\n\tcin >> a >> b;\n\tcout << a + b << endl;\n\treturn 0;\n}\n"
    editor.insert(code);
	var language = 'cpp';
	editor.session.setMode("ace/mode/"+language);
	function change_session(){
		var language = document.getElementById('select_language').value;
		editor.session.setMode("ace/mode/"+language);
	}
	console.log(language);

    function submit() {
        var code = editor.getValue();
        $.ajax({
            type: "POST",
            url: "<?=base_url('submission/submit')?>",
            data: {
                problem: "<?=$problem_name?>",
                code: code,
                type: language
            },
            success: function(){  
                window.location='/submission';
                // $("form#updatejob").hide(function(){$("div.success").fadeIn();});  
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            } 
        });
    }
</script>

