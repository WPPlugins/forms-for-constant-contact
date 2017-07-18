<div id="ffccns_form_container" class="ffccns_form_container" style="background-color:<?php echo $background_color; ?>">
	<div id="ffccns_headline" class="ffccns_headline"><?php echo $headline; ?></div>
	<?php
		if($image){
	?>
	<div id="ffccns_image" class="ffccns_image"><img src="<?php echo $image ?>"/></div>
	<?php
		} //end if($image)
	?>
	<div id="ffccns_message" class="ffccns_message"><?php echo wpautop($message); ?></div>
	<div id="ffccns_form_box">
		<form action="https://visitor2.constantcontact.com/api/signup" id="ffccns_form" accept-charset="utf-8" method="post">
			<div id="ffccns_name_field">
				<input type="text" name="first_name" placeholder="Enter Your Name" class="ffccns_name">
			</div>
			<div id="ffccns_email_field">
				<input type="text" name="email" placeholder="Enter Your Email" class="ffccns_email">
			</div>
			<input type="hidden" name="ca" value="<?php echo $cc_ca ?>">
			<input type="hidden" name="list" value="<?php echo $cc_list ?>">
			<input type="hidden" name="url" value="<?php echo $cc_url ?>">
		</form>
	</div>
	<div id="ffccns_button" class="ffccns_button">
		<a class="btn" href="#" style="background:<?php echo $button_color; ?>"><?php echo $button_text; ?></a>
	</div>
</div>
