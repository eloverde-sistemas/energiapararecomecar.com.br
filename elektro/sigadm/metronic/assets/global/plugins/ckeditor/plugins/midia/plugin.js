(function(){
	//Section 1 : Code to execute when the toolbar button is pressed
	var a= {
		exec:function(editor){
			$('#dialogMidias').modal();
			
			
			$('.addImage').click(function(){
				
				caminho = $(this).attr('data-caminho');

				$('#dialogMidias').modal("hide");
				$('#cke_32').click();
				
				$('table[class="cke_dialog cke_browser_webkit cke_browser_quirks cke_ltr"]').css("z-index","10010");
				
				setTimeout(function(){
					$('.cke_dialog_body input.cke_dialog_ui_input_text:first').val(caminho);
				}, 500);
			});
		}
		
	},

	//Section 2 : Create the button and add the functionality to it
	b='midia';
	
	CKEDITOR.plugins.add(b,{
		init:function(editor){
			editor.addCommand(b,a);
			editor.ui.addButton('midiabutton',{
				label:'Adicionar Mídia',
				icon: this.path + 'midia.png',
				command:b
			});
		}
	});
})();