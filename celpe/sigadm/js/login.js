var Login = function () {

	var handleLogin = function() {
		
		$('.login-form').submit(function(event){
			//Não deixa submitar o form
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			
			var login 	= $('[name="username"]');
			var senha 	= $('[name="password"]');
			var tipo 	= $('[name="user_type"]:checked');
			
			if( $('.login-form').valid() ){
			
				$.ajax({
					url: 'inc/genericoJSON.php',
					type: 'post',
					data: {
							acao: 	'login',
							login: 	login.val(),
							senha: 	senha.val(),
							tipo: 	tipo.val()
					},
					cache: false,
					success: function(data) {					
						
						if(data.status==true) {
							location.reload();
						
						}else{									
							alert(data.message);
							//console.dir(data.message);
							return false;
						}
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
						//console.dir(XMLHttpRequest.responseText);
					},
					dataType: 'json'
				});
				
			}
			
		});
		
		$('.login-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				username: {
					required: true
				},
				password: {
					required: true
				},
				'user_type': {
					required: false
				}
			},

			messages: {
				username: {
					required: "Por gentileza, informar seu cadastro."
				},
				password: {
					required: "Por gentileza, informar a senha."
				},
				'user_type': {
					required: "Selecione o tipo de usuário."
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit   
				$('.alert-danger', $('.login-form')).show();
			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function (label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			errorPlacement: function (error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function (form) {
		//		form.submit(); // form validation success, call ajax form submit
			}
		});

		$('.login-form input').keypress(function (e) {
			if (e.which == 13) {
				if ($('.login-form').validate().form()) {
					$('.login-form').submit(); //form validation success, call ajax form submit
				}
				return false;
			}
		});
	}
	
	var handleForgetPassword = function () {
		
		$('.forget-form').submit(function(event){
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			
			var email = $('[name="emailES"]');
			var login = $('[name="cimES"]');
			
			if( $('.forget-form').valid() ){
				
				$.ajax({
					url: 'inc/genericoJSON.php',
					type: 'post',
					data: { 
						acao :    'esqueceuSenha',
						login: 	  login.val(),
						email: 	  email.val()
					},
					cache: false,
					success: function(data) {
						
						jQuery('.forget-form').hide();
						jQuery('.login-form').show();
						
						var alerta = $('.login-form').find(".alert");
						var mensagem = alerta.find('span');
						
						mensagem.html(data.message);
						
						if(data.status==true){
							alerta.addClass("alert-success").removeClass("alert-danger").removeClass("display-hide");
							
						}else{
							alerta.addClass("alert-danger").removeClass("alert-success").removeClass("display-hide");
						}
						
						
						
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
						$(waitId).fadeOut('fast');
					},
					dataType: 'json'
				});	
				return false;	
				
			}
		});
		
		$('.forget-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",
			rules: {
				cimES: 'required',
				emailES: {
					required: true,
					email: true
				}
			},

			messages: {
				cimES: {
					required: "Por gentileza, informar seu cadastro."
				},
				emailES: {
					required: "Por gentileza, informar o email."
				},
				user_typeES: {
					required: "Selecione o tipo de usuário."
				}
			},

			invalidHandler: function (event, validator) { //display error alert on form submit   

			},

			highlight: function (element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function (label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			errorPlacement: function (error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function (form) {
//				form.submit();
			}
		});
		
		$('.forget-form input').keypress(function (e) {
			if (e.which == 13) {
				if ($('.forget-form').validate().form()) {
					$('.forget-form').submit();
				}
				return false;
			}
		});

		jQuery('#forget-password').click(function () {
			jQuery('.login-form').hide();
			jQuery('.forget-form').show();
		});

		jQuery('#primeiro-acesso').click(function () {
			jQuery('.login-form').hide();
			jQuery('.primeiro-acesso').show();
		});

		jQuery('.back-btn').click(function () {
			jQuery('.login-form').show();
			jQuery('.forget-form').hide();
		});

	}

	var handleRegister = function () {
		
		function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='metronic/assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        }


		$("#select2_sample4").select2({
		  	placeholder: '<i class="fa fa-map-marker"></i>&nbsp;Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


			$('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });



         $('.register-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                
	                fullname: {
	                    required: true
	                },
	                email: {
	                    required: true,
	                    email: true
	                },
	                address: {
	                    required: true
	                },
	                city: {
	                    required: true
	                },
	                country: {
	                    required: true
	                },

	                username: {
	                    required: true
	                },
	                password: {
	                    required: true
	                },
	                rpassword: {
	                    equalTo: "#register_password"
	                },

	                tnc: {
	                    required: true
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                tnc: {
	                    required: "Please accept TNC first."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
	                    error.insertAfter($('#register_tnc_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    $('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.register-form').hide();
	        });
	}
    
	var handlePrimeiroAcesso = function () {
		
		var step = 1;
		
		$('.primeiro-acesso').submit(function(event){
						
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			
			if($(this).valid() == true){
				
				if(step==1){
					$.ajax({
						url: 'inc/genericoJSON.php',
						type: 'post',
						data: {
								acao: 		'verificaCimPA',
								cim: 		$('#cimPA').val()
						},
						cache: false,
						success: function(data) {					
						
							if(data.status==true) {
								step = 2;
								$('#cimPA').attr('disabled', true);
								$('.step2, .step2-' + data.campo).removeClass('hide');
								$('#emailPA').focus();
								$("#btnSubPA").html('Enviar <i class="m-icon-swapright m-icon-white"></i>');
								
							}else{									
								var alerta = $('.login-form').find(".alert");
								var mensagem = alerta.find('span');
								mensagem.html(data.message);
								alerta.addClass("alert-danger").removeClass("alert-success").removeClass("display-hide");
							}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							alert(XMLHttpRequest.responseText);
							//console.dir(XMLHttpRequest.responseText);
						},
						dataType: 'json'
					});
				
				}else{
					
					$.ajax({
						url: 'inc/genericoJSON.php',
						type: 'post',
						data: {
								acao: 		'verificaCimPA2',
								cim: 		$('#cimPA').val(),
								email: 		$('#emailPA').val(),
								cpf: 		$('#cpfPA').val(),
								idPergunta: $('#idPergunta').val(),
								resposta:	$('#respostaPA').val()
						},
						cache: false,
						success: function(data) {
						
							jQuery('.primeiro-acesso').hide();
							jQuery('.login-form').show();
							$('.step2').addClass('hide');
							$('#cimPA').attr('disabled', false).val("");
							$("#btnSubPA").html('Continuar <i class="m-icon-swapright m-icon-white"></i>');
							
							var alerta = $('.login-form').find(".alert");
							var mensagem = alerta.find('span');
							
							mensagem.html(data.message);

							if(data.status==true){
								alerta.addClass("alert-success").removeClass("alert-danger").removeClass("display-hide");
								
							}else{
								alerta.addClass("alert-danger").removeClass("alert-success").removeClass("display-hide");
							}
							
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							alert(XMLHttpRequest.responseText);
							console.dir(XMLHttpRequest.responseText);
						},
						dataType: 'json'
					});
				
				}
				
			}
			
		}).validate({
			rules: {
				cimPA: {
					required:true
				},
				emailPA: {
					required:true,
					email : true
				},
				cpfPA: "required",
				respostaPA: "required"
			}
		});
		
		jQuery('.back-btn').click(function () {
			jQuery('.login-form').show();
			jQuery('.primeiro-acesso').hide();
		});
		
	}
	
    return {
        //main function to initiate the module
        init: function () {

        	
            handleLogin();
            handleForgetPassword();
            handleRegister();
			handlePrimeiroAcesso();        
	       
        }

    };

}();