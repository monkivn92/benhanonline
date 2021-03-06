<?php defined('_JEXEC') or die('Restricted access'); 
$option = JRequest::getVar('option');
$controller = JRequest::getVar('controller');
$user		= JFactory::getUser();
$doc = JFactory::getDocument();

$doc->addStyleSheet('/components/com_comprofiler/plugin/templates/default/template.css');
$doc->addStyleSheet('/components/com_benhan/asset/benhan.css');
$doc->addScript('/components/com_benhan/asset/jquery.min.js');
$doc->addScript('/components/com_benhan/asset/benhan.js');


?>

<script type="text/javascript" src="/components/com_comprofiler/js/overlib_all_mini.js">
</script>
	<script type="text/javascript"><!--
overlib_pagedefaults(WIDTH,250,VAUTO,RIGHT,AUTOSTATUSCAP, CSSCLASS,TEXTFONTCLASS,'cb-tips-font',FGCLASS,'cb-tips-fg',BGCLASS,'cb-tips-bg',CAPTIONFONTCLASS,'cb-tips-capfont', CLOSEFONTCLASS, 'cb-tips-closefont');
--></script>

<h4 class="jreg_error_status" style="color:red;"><?php echo $this->message; ?></h4>
<form action='<?php echo JRoute::_("index.php?option=com_benhan&view=user")?>' method="post" id="cbcheckedadminForm" name="adminForm" enctype="multipart/form-data" class="cb_form" autocomplete="off">
	<h2>Add New Patient Page</h2>
	<blockquote class="rounded">		
		<?php 
			foreach ($this->CBf as $field) 
			{
				echo '<p>';
				echo $field;
				echo '</p>';
			}


			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onInit','dynamic_recaptcha_1');


		?>
			<div id="captcha_wrap">				
			
			<div id="dynamic_recaptcha_1"></div>
			</div>
	</blockquote>
	<input type="submit" value="Register" name="ba_save" class="button btn btn-primary">
	<input type="hidden" name="task" value="saveuser"/> 
    <input type="hidden" name="option" value="<?php echo $option;?>"/>    
    <input type="hidden" name="controller" value="<?php echo $controller;?>" />
    <?php echo JHTML::_( 'form.token' ); ?>

</form>


<script>

cbjQuery( document ).ready( function( $ ) {
var host = location.protocol+'//'+location.hostname; 
var jQuery = $;
jQuery.validator.addMethod("cbusername", function(value, element) {	return this.optional(element) || ! /[\<|\>|\"|\'|\%|\;|\(|\)|\&]/i.test(value);}, "Please enter a valid Username:.  No spaces, more than 2 characters and contain 0-9,a-z,A-Z"); 
$.extend(jQuery.validator.messages, {
		required: "This field is required.",
		remote: "Please fix this field.",
		email: "Please enter a valid email address.",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date (ISO).",
		number: "Please enter a valid number.",
		digits: "Please enter only digits.",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please enter the same value again.",
		accept: "Please enter a value with a valid extension.",
		maxlength: $.validator.format("Please enter no more than {0} characters."),
		minlength: $.validator.format("Please enter at least {0} characters."),
		rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
		range: $.validator.format("Please enter a value between {0} and {1}."),
		max: $.validator.format("Please enter a value less than or equal to {0}."),
		min: $.validator.format("Please enter a value greater than or equal to {0}.")
});

{
	var firstInvalidFieldFound	=	0;
	$('#cbcheckedadminForm').submit( function() {
		var v = $(this).validate();
		v.cbIsFormSubmitting = true;
		var r = $(this).validate().form();
		v.cbIsFormSubmitting = false;
		if ( ! r ) {
			$(this).validate().focusInvalid();
			return r;
		}
		else{
				//return confirm('You are accessing DS-Connect: The Down Syndrome Registry which may contain sensitive information that must be protected under the U.S. Privacy Act.\nUnauthorized or malicious attempts to upload information, change information, or misuse this web site may result in disciplinary action, civil, and/or criminal penalties.\nUnauthorized users of this web site should have no expectation of privacy regarding any communications or data processed by this web site.\nNIH may report criminal activity to law enforcement officials.')		
			}
		
	});
	$('#cbcheckedadminForm').validate( {
		onsubmit : false,
		ignoreTitle : true,
		errorClass: 'cb_result_warning',
		// debug: true,
		cbIsOnKeyUp: false,
		cbIsFormSubmitting: false,
		highlight: function( element, errorClass ) {
			$( element ).parents('.fieldCell').parent().addClass( 'cbValidationError' );		// tables
			$( element ).parents('.cb_field,.cb_form_line').addClass( 'cbValidationError' );	// divs
			$( element ).addClass( 'cbValidationError' + $(element).attr('type') );
			$( element ).parents('.tab-page').addClass('cbValidationErrorTab')
			.each( function() {
				$(this).siblings('.tab-row')
				.find('h2:nth-child(' + $(this).index() + ')')
				.addClass('cbValidationErrorTabTip');
			})
			.filter(':not(:visible)').each( function() {
				if ( ! firstInvalidFieldFound++ ) {
					showCBTab( $(this).attr('id').substr(5) );
				}
			});;
		},
		unhighlight: function( element, errorClass ) {
			if ( this.errorList.length == 0 ) {
				firstInvalidFieldFound = 0;
			}
			$( element ).parents('.fieldCell').parent().removeClass( 'cbValidationError' );		// tables
			$( element ).parents('.cb_field,.cb_form_line').removeClass( 'cbValidationError' );	// divs
			$( element ).removeClass( 'cbValidationError' + $(element).attr('type') );
			$( element ).parents('.tab-page')
			.each( function() {
				if ( $(this).find('.cbValidationError').size() == 0 ) {
					$(this).removeClass('cbValidationErrorTab')
					.siblings('.tab-row')
					.find('h2:nth-child(' + $(this).index() + ')')
					.removeClass('cbValidationErrorTabTip');
				}
			});
		},
		errorElement: 'div',
		errorPlacement: function(error, element) {
			element.closest('.fieldCell, .cb_field').append( error[0] );		// .fieldCell : tables, .cb_field : div
		},
		onkeyup: function(element) {
			if ( element.name in this.submitted || element == this.lastElement ) {
				// avoid remotejhtml rule onkeyup
				this.cbIsOnKeyUp = true;
				this.element(element);
				this.cbIsOnKeyUp = false;
			}
        }
	} );
	$('#cbcheckedadminForm input:checkbox,#cbcheckedadminForm input:radio').click( function() {
		$('#cbcheckedadminForm').validate().element( $(this) );
	} );
	$( '#cbcheckedadminForm .cbDateinputJs select' ).change( function() {
		var datefield	=	$(this).parent().prev('input');
		if ( datefield.length ) {
			$('#cbcheckedadminForm').validate().element( datefield );
		}
	} );
}
jQuery.validator.addMethod("remotejhtml", function(value, element, param) {
			if ( this.optional(element) )
				return "dependency-mismatch";

			var previous = this.previousValue(element);

			if (!this.settings.messages[element.name] )
				this.settings.messages[element.name] = {};
			this.settings.messages[element.name].remote = typeof previous.message == "function" ? previous.message(value) : previous.message;

			param = typeof param == "string" && {url:param} || param;

			var respField = $('#'+$(element).attr('id')+'__Response');
			if ( respField.html() != '&nbsp;' ) {
				if ( previous.old !== value ) {
					respField.fadeOut('medium' );
				} else {
					respField.fadeIn('medium' );
				}
			}

			if ( previous.old !== value && ! this.cbIsOnKeyUp && ! this.cbIsFormSubmitting ) {

				var inputid = $(element).attr('id');
				if ( ! $('#'+inputid+'__Response').size() ) {
					var respField = '<div class=\"cb_result_container\"><div id=\"' + inputid + '__Response\">&nbsp;</div></div>';
					$(element).parent().each( function() {
						if (this.tagName.toLowerCase() == 'td') {
							$(this).append(respField);
						} else {
							$(this).after(respField);
						}
						$(inputid+'__Response').hide();
					} );
				}

				previous.old = value;
				var validator = this;
				// this.startRequest(element);
				var data = {};
				data[element.name] = value;
				$.ajax($.extend(true, {
					type: 'POST',
					url:  '/index.php?option=com_benhan&view=user&task=checkUsername',
					mode: "abort",
					port: "validate" + element.name,
					dataType: "html",	/* """json", */
					data: 'value=' + encodeURIComponent(value),
					/* data: data, */
					success: function(response) {
						// never errors on that one: */
						var submitted = validator.formSubmitted;
						validator.prepareElement(element);
						validator.formSubmitted = submitted;
						validator.successList.push(element);
						validator.showErrors();

						previous.valid = response;
						// validator.stopRequest(element, response);

						var respField = $('#'+$(element).attr('id')+'__Response');
						respField.fadeOut('fast', function() {
							respField.html(response).fadeIn('fast');
						} );
					},
					error: function(jqXHR, textStatus) {
						// validator.stopRequest(element, textStatus);
						var respField = $('#'+$(element).attr('id')+'__Response');
						respField.fadeOut('fast', function() {
							respField.html(textStatus).fadeIn('fast');
						} );
					}
				}, param));
				$('#'+inputid+'__Response').html('<img alt=\"\" src=\"'+host+'/components/com_comprofiler/images/wait.gif\" /> Checking...').fadeIn('fast');
				return true;		// "pending";
			} else if( this.pending[element.name] ) {
				return "pending";
			}
			return true; // previous.valid;
}, 'Ajax Reply Error');

//$("#cbcheckedadminForm input[type!='hidden']:first").filter("[type='text'],textarea,[type='password']").focus();
});
if ( typeof window.cbjqldr_tmpsave$ != 'undefined' ) {
	window.$ = window.cbjqldr_tmpsave$;
}
if ( typeof window.cbjqldr_tmpsavejquery != 'undefined' ) {
	window.jQuery = window.cbjqldr_tmpsavejquery;
}

</script>
