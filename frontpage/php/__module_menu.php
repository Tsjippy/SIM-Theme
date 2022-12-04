<?php
namespace SIM\FRONTPAGE;
use SIM;

const MODULE_VERSION		= '7.0.8';
//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

add_filter('sim_submenu_description', function($description, $moduleSlug){
	//module slug should be the same as the constant
	if($moduleSlug != MODULE_SLUG)	{
		return $description;
	}

	ob_start();

	?>
	<p>
		This module add a news gallery to the homepage as well as an gallery of the last added content.<br>
		It also adds a homepage for logged in users, where users will be redirected on login.
	</p>

	<?php
	$url		= SIM\ADMIN\getDefaultPageLink($moduleSlug, 'home_page');
	if(!empty($url)){
		?>
		<p>
			<strong>Auto created page:</strong><br>
			<a href='<?php echo $url;?>'>Home page for logged in users</a>
		</p>
		<?php
	}

	return ob_get_clean();
}, 10, 2);



	<h4>Highlighted pages gallery</h4>
	<label>
		Hook used in your template before the footer.<br>
		Used to display a galery of pages you want to highlight.<br>
		<input type="text" name="before_footer_hook" value="<?php echo $settings['before_footer_hook'];?>">
	</label>
	<br>
	<label>
		Do you want to display a gallery of highligted pages with static pages or random selected ones?
	</label>
	<br>
	<label>
		<input type='radio' name='galery-type' value='dynamic' <?php if($settings['galery-type'] == 'dynamic'){echo 'checked';}?>>
		Dynamic
	</label>
	<label>
		<input type='radio' name='galery-type' value='static' <?php if($settings['galery-type'] == 'static'){echo 'checked';}?>>
		Static
	</label>

	<div id='dynamic-options' <?php if($settings['galery-type'] != 'dynamic'){echo 'class="hidden"';}?>>
		<label>
			How often should the gallery be refreshed in seconds?<br>
			<input type='number' name='speed' value ='<?php echo $settings['speed'];?>'>
		</label>
		<br>
		<br>
		
		?>
	</div>

	<div id='static-options' <?php if($settings['galery-type'] != 'static'){echo 'class="hidden"';}?>>
		<br>
		<label>
			Select three different pages below. Optionally you can give cutom titles and summaries.<br>
			If these fields are empty the page title and content will be used.
		</label>
		<?php
		for ($x = 1; $x <= 3; $x++) {
			?>
			<h5> Highlight page <?php echo $x;?></h5>
			Select the page you want to show on frontpage.<br>
			<?php
			echo SIM\pageSelect("page$x", $settings["page$x"]);
			?>
			<label>
				Type a short title (optional).<br>
				<input type="text" name="title<?php echo $x;?>" value="<?php echo $settings["title$x"];?>">
			</label>
			<br>
			<label>
				Type a short description (optional).<br>
				<textarea name="description<?php echo $x;?>">
					<?php echo $settings["description$x"];?>
				</textarea>
			</label>
			<br>
			<?php
		}
		?>
	</div>
	<script>
		document.querySelectorAll('[name="galery-type"]').forEach(radio=>radio.addEventListener('click', ev=>{
			if(ev.target.value == 'dynamic'){
				document.getElementById('dynamic-options').classList.remove('hidden');
				document.getElementById('static-options').classList.add('hidden');
			}else{
				document.getElementById('static-options').classList.remove('hidden');
				document.getElementById('dynamic-options').classList.add('hidden');
			}
		}));

		document.querySelectorAll('[name="post_types[]"]').forEach(radio=>radio.addEventListener('click', ev=>{
			let wrapper	= document.querySelector('.category-wrapper.'+ev.target.value)
			if(ev.target.checked){
				wrapper.classList.remove('hidden');
			}else{
				wrapper.classList.add('hidden');
			}
		}));

	</script>
	<br>
	<br>
	<br>
	<label>
		Welcome message on homepage
		<?php
		$tinyMceSettings = array(
			'wpautop' 					=> false,
			'media_buttons' 			=> false,
			'forced_root_block' 		=> true,
			'convert_newlines_to_brs'	=> true,
			'textarea_name' 			=> "welcome_message",
			'textarea_rows' 			=> 10
		);

		echo wp_editor(
			$settings["welcome_message"],
			"welcome_message",
			$tinyMceSettings
		);
		?>
	</label>
	<?php
	return ob_get_clean();
}, 10, 3);

add_filter('sim_module_updated', function($options, $moduleSlug, $oldOptions){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $options;
	}

	if(!SIM\getModuleOption('pagegallery', 'enable')){
		SIM\ADMIN\enableModule('pagegallery');
	}

	// Create frontend posting page
	$content	= 'Hi [displayname],<br><br>I hope you have a great day!<br><br>[logged_home_page]<br><br>[welcome]';
	$options	= SIM\ADMIN\createDefaultPage($options, 'home_page', 'Home', $content, $oldOptions, ['post_name'=>'lhome']);

	return $options;
}, 10, 3);

add_filter('display_post_states', function ( $states, $post ) {

    if(in_array($post->ID, SIM\getModuleOption(MODULE_SLUG, 'home_page', false)) ) {
        $states[] = __('Home page for logged in users');
    }

    return $states;
}, 10, 2);

add_action('sim_module_deactivated', function($moduleSlug, $options){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG)	{
		return;
	}

	foreach($options['home_page'] as $page){
		// Remove the auto created page
		wp_delete_post($page, true);
	}
}, 10, 2);