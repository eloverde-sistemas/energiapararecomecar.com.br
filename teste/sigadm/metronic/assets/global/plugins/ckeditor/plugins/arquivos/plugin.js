(function(){
	//Section 1 : Code to execute when the toolbar button is pressed
	var a= {
		exec:function(editor){
			alert("opa");
			$('#dialogMidiasArquivos').modal();
		}
	},

	//Section 2 : Create the button and add the functionality to it
	b='arquivos';
	
	CKEDITOR.plugins.add(b,{
		init:function(editor){
			editor.addCommand(b,a);
			editor.ui.addButton('arquivobutton',{
				label:'Adicionar Arquivo',
				icon: this.path + 'midia.png',
				command:b
			});
			
			if(!$('#arquivos_dialogo').length>0){
				$.get( document.location.origin + '/cke_plugins.php', { tipo: "arquivos"}, function( data ) {
					$( "body" ).append( data );
				});
			}
			
			insertArquivo = function(id, titulo){
				var element = CKEDITOR.dom.element.createFromHtml( '<a href="' + window.location.origin + '/baixarDocumento.php?tipo=22&id=' + id + '">' + titulo + '</a>' );
				editor.insertElement( element );
				
				$("#dialogMidiasArquivos").modal('hide');
			}
		}
	});
})();