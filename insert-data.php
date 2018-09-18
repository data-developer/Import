<?php

function upload_data(){	
	
	?>
	
	<div class="wrap">
		<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

		<h3>Import CSV Data</h3>
			File: <input name="uploaded" type="file" id="csvfile"/>
			<input type="submit" name="upfile" value="Upload File">
		</form>
		<p>Seo projects CSV file format: <a href="<?php echo home_url()?>/Book2.csv">Download</a></p>
		<p>Other projects CSV file format: <a href="<?php echo home_url()?>/Book1.csv">Download</a></p>
		<p><b>Note:</b> Don't add the COMA (,) in between the content in any column. </br>
		For Keywords and Ranking add DOT and space (. ) between two keywords Like: first keyword. second keyword</br>
		Same as in Ranking.</p>
		</div>
	
	<?php
		global $wpdb;
		if(isset($_POST['upfile']))
		{
		if ($_FILES['uploaded'][size] > 0) { 

			//get the csv file 
			$fext=$file = $_FILES['uploaded']['name']; 
			$file = $_FILES['uploaded']['tmp_name']; 
			$ext = pathinfo($fext, PATHINFO_EXTENSION);
			if($ext=='csv')
			{
			$handle = fopen($file,"r"); 
			
			 fgetcsv($handle);
			 //loop through the csv file and insert into database 
			do { 
				if ($data[0]) { 	 
					
						$cate = explode(';', $data[13]);
					//print_r($cate);
					$cat = array();
					foreach($cate as $cate){
					 $cat[] = get_cat_ID($cate); 
					}
							 
					 $term = get_term_by('name', $data[14], 'industries'); 
					 $name = $term->name; 
					 $id = $term->term_id;			 
					
					$my_post = array(
					'post_title'    => $data[0],
					'post_content'  => $data[2],
					'post_status'   => $data[1],
					'post_author'   => 1,
					'post_type'     => $data[3],
					'post_parent'   => $data[4],
					'post_category' => $cat
					);
		 
					// Insert the post into the database.
					//$post_id = wp_insert_post( $my_post );
					
					
					if (!get_page_by_title( $my_post['post_title'], 'OBJECT', $data[3])) {
						$post_id = wp_insert_post($my_post);
					} else {
						$old_post = get_page_by_title( $my_post['post_title'], 'OBJECT', $data[3] );
						$my_post['ID'] = $old_post->ID;
						$post_id = wp_update_post($my_post);
					}
					
					wp_set_post_terms( $post_id, $id, 'industries' );
					if($data[5] != ''){
					
					// Add Featured Image to Post				
						$image_url        = $data[5]; // Define the image URL here
						$image_name       = $data[6];			
						$upload_dir       = wp_upload_dir(); // Set upload folder				
						$image_data       = file_get_contents($image_url); // Get image data				
						$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once(ABSPATH . 'wp-admin/includes/image.php');

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						$image_data  = wp_update_attachment_metadata( $attach_id, $attach_data );
						
						if($data[5]){			
							// And finally assign featured image to post
							set_post_thumbnail( $post_id, $attach_id );
						}
					}
					 
					
					if($data[3] == 'post') {
					
					if($data[7] != ''){
					
					// Add Featured Image to Post
						$firstimage_url        = $data[7]; // Define the image URL here
						$firstimage_name       = $data[8];
						$upload_dir       	   = wp_upload_dir(); // Set upload folder
						$image_data            = file_get_contents($firstimage_url); // Get image data
						$unique_file_name      = wp_unique_filename( $upload_dir['path'], $firstimage_name ); // Generate unique name
						$filename              = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once(ABSPATH . 'wp-admin/includes/image.php');

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						wp_update_attachment_metadata( $attach_id, $attach_data );

						// And finally assign featured image to post
						$first_image_url = wp_get_attachment_url($attach_id);
						
							if ( ! add_post_meta( $post_id, 'wpcf-first-image', $first_image_url, true ) ) { 
							   update_post_meta ( $post_id, 'wpcf-first-image', $first_image_url );
							}
									
					}
					
					if($data[9] != ''){
					
					// Add Featured Image to Post
						$secondimage_url        = $data[9]; // Define the image URL here
						$secondimage_name       = $data[10];
						$upload_dir       = wp_upload_dir(); // Set upload folder
						$image_data       = file_get_contents($secondimage_url); // Get image data
						$unique_file_name = wp_unique_filename( $upload_dir['path'], $secondimage_name ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once(ABSPATH . 'wp-admin/includes/image.php');

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						wp_update_attachment_metadata( $attach_id, $attach_data );

						// And finally assign featured image to post
						$second_image_url = wp_get_attachment_url($attach_id);
						
							if ( ! add_post_meta( $post_id, 'wpcf-second-image', $second_image_url, true ) ) { 
							   update_post_meta ( $post_id, 'wpcf-second-image', $second_image_url );
							}
									
					}
					
					if($data[11] != ''){
					
					// Add Featured Image to Post
						$thirdimage_url        = $data[11]; // Define the image URL here
						$thirdimage_name       = $data[12];
						$upload_dir       = wp_upload_dir(); // Set upload folder
						$image_data       = file_get_contents($thirdimage_url); // Get image data
						$unique_file_name = wp_unique_filename( $upload_dir['path'], $thirdimage_name ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once(ABSPATH . 'wp-admin/includes/image.php');

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						wp_update_attachment_metadata( $attach_id, $attach_data );

						// And finally assign featured image to post
						$third_image_url = wp_get_attachment_url($attach_id);
						
							if ( ! add_post_meta( $post_id, 'wpcf-third-image', $third_image_url, true ) ) { 
							   update_post_meta ( $post_id, 'wpcf-third-image', $third_image_url );
							}
									
					}
					
					} else if($data[3] == 'seo'){
						
						if ( ! add_post_meta( $post_id, 'wpcf-site-url', $data[7], true ) ) { 
							   update_post_meta ( $post_id, 'wpcf-site-url', $data[7] );
						}
						
						if ( ! add_post_meta( $post_id, 'wpcf-ranking-on-google', $data[8], true ) ) { 
							   update_post_meta ( $post_id, 'wpcf-ranking-on-google', $data[8] );
						}
						
						$keywords = $data[9];
						$keywords_arrs = explode(". " , $keywords);
							foreach($keywords_arrs as $keywords_arr){				
								   add_post_meta( $post_id, 'wpcf-locations', $keywords_arr);
						
							}
							
						$ranking = $data[10];
						$ranking_arrs = explode(". " , $ranking);
							foreach($ranking_arrs as $ranking_arr){
									   add_post_meta( $post_id, 'wpcf-globally', $ranking_arr );
							}
						
						if($data[6] == '' || $data[7] == '' || $data[8] == '' || $data[9] == '' || $data[10] == '' || $data[0] == '' || $data[1] == '' || $data[4] == ''){
						
								$poststatus = array('ID' => $post_id,'post_status' => 'Draft');
								wp_update_post($poststatus);
							}				
					}
					echo '<div class="result-format" style="margin-bottom:20px;">';
					 if($data[0]){
							echo 'Title = '.$data[0].'</br>';
						} else{
							echo 'Title = missing </br>';
						}
						if($data[2]){
							echo 'Post content = '.$data[2].'</br>';
						} else{
							echo 'Post content = missing </br>';
						}
						if($data[3]){
							echo 'Post type = '.$data[3].'</br>';
						} else{
							echo 'Post type = missing </br>';
						} 
						if($data[5]){
							echo 'Featured image = '.$data[5].'</br>';
						} else{
							echo 'Featured image = missing </br>';
						} 
						if($data[6]){
							echo 'Featured image name = '.$data[6].'</br>';
						} else{
							echo 'Featured image name = missing </br>';
						} 
					
						if($data[3] == 'post') {
							
							
							
							if($data[6] == '' || $data[7] == '' || $data[8] == '' || $data[9] == '' || $data[10] == '' || $data[11] == '' || $data[12] == '' || $data[13] == '' || $data[14] == '' || $data[0] == '' || $data[1] == '' || $data[2] == '' || $data[4] == ''){
						
								$poststatus = array('ID' => $post_id,'post_status' => 'Draft');
								$newpost_id = wp_update_post($poststatus);
							}
					
								if($data[7]){
										echo 'First image = '.$data[7].'</br>';
								} else{
									echo 'First image = missing </br>';
								} 
								if($data[8]){
										echo 'First image name = '.$data[8].'</br>';
								} else{
									echo 'First image name = missing </br>';
								} 
								if($data[9]){
										echo 'Second image = '.$data[9].'</br>';
								} else{
									echo 'Second image = missing </br>';
								} 
								if($data[10]){
										echo 'Second image name = '.$data[10].'</br>';
								} else{
									echo 'Second image name = missing </br>';
								} 
								if($data[11]){
										echo 'Third image = '.$data[11].'</br>';
								} else{
									echo 'Third image = missing </br>';
								} 
								if($data[12]){
										echo 'Third image name = '.$data[12].'</br>';
								} else{
									echo 'Third image name = missing </br>';
								} 
								if($data[13]){
										echo 'Technology = '.$data[13].'</br>';
								} else{
									echo 'Technology = missing </br>';
								} 
								if($data[14]){
										echo 'Industry = '.$data[14].'</br>';
								} else{
									echo 'Industry = missing </br>';
								} 					
						}
						
						echo '<b> Post Status: '. get_post_status ( $post_id ). '</b>';
						
						
						echo '</div>';		
					
				} 
					
					} while ($data = fgetcsv($handle,1000000,",","'")); 
				?>
				<div> <h1>Data Uploaded Successfully</h1></div>	
				<?php
				}
				else
				{
				?>
				<h1>You Must Upload Only CSV with .CSV Extension</h1>
				<?php
				}
				}
				else
				{
				?><h1>Oops! Something Went Wrong!</h1>Try again if problem persists contact Developer <?php
				}
				}
			}
			add_action('upload_data', 'upload_data');
			?>
