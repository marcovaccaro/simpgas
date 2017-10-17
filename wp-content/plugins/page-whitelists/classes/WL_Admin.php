<?php

/**
 * creates settings page
 */
class WL_Admin {
	private $data;
	
	function __construct($data,$file) {
		$this->template_path = plugin_dir_path($file)."templates/";
		$this->template_url = plugin_dir_url($file)."templates/";
		$this->data = $data;
	}
	
	public function admin_setup() {
		add_action('add_meta_boxes', array($this, 'add_metabox'));
		add_action('save_post', array($this, 'save_metabox'));
		add_action('admin_menu',array($this, 'add_menus'));
		add_action('admin_enqueue_scripts',array($this,'enqueue_assets'));
		add_action('admin_init',array($this,'register_ajax'));
        add_action('admin_init',array($this,'add_settings'));
		add_action('manage_users_columns',array($this,'user_column_header'));
		add_action('manage_users_custom_column',array($this,'user_column_content'),10,3);
		add_action('edit_user_profile', array($this,'profile_field'));
		add_action('edit_user_profile_update', array($this,'profile_field_update'));
	}
	
    function add_settings() {
        register_setting( 'wl_lists', 'wlist_settings', array($this,'validate_settings') );
        add_settings_section(
            'wl_general_settings', //id for the section
            __('General Settings','page-whitelists'), //title of section
            array($this,'render_settings_section'), //callback to render the html
            'wl_lists' //slug of the page
        );
        
        add_settings_field(
            'strict_as_default',
            __('Default whitelist strictness','page-whitelists'),
            array($this,'render_settings_field_strictness'),
            'wl_lists',
            'wl_general_settings'             
        );
        
        add_settings_field(
            'filter_all_listings',
            __('Plugin compatibility','page-whitelists'),
            array($this,'render_settings_field_all_listings'),
            'wl_lists',
            'wl_general_settings'             
        );
    }

    function validate_settings($input) {
        //TODO make this more universal or move it to WL_Data
        
        $output = Array();
        
        if (!isset($input['filter_all_listings']) || $input['filter_all_listings'] == 0) {
            $output['filter_all_listings'] = 0;
        } else if ($input['filter_all_listings'] == 1) {
            $output['filter_all_listings'] = 1;
        };
        
        if(!isset($input['strict_as_default']) || $input['strict_as_default'] == 0) {
            $output['strict_as_default'] = 0;
        } else if ($input['strict_as_default'] == 1) {
            $output['strict_as_default'] = 1;
        };

        return $output;
    }
    
    function render_settings_section() {
        return;
    }
    
    function render_settings_field_strictness() {
        echo "<input id='wl_strict_as_default' name='wlist_settings[strict_as_default]' size='40' type='checkbox' value='1' ".($this->data->settings['strict_as_default'] == 1 ? " checked=\"checked\"" : "")."/>";
        _e('Set new whitelists as "strict" by default.','page-whitelists');
        echo '<p class="description">'.__('New whitelists will not allow assigned users to create new pages.','page-whitelists').'</p>';                
    }
    
    function render_settings_field_all_listings() {
        echo "<input id='wl_filter_all_listings' name='wlist_settings[filter_all_listings]' size='40' type='checkbox' value='1' ".($this->data->settings['filter_all_listings'] == 1 ? " checked=\"checked\"" : "")."/>";
        _e('Filter all page listings in the Admin area.','page-whitelists');
        echo '<p class="description">'.__('Attempts to remove restricted pages also from plugin generated listings (i.e. alternative page management tools). Sometimes "disappears" pages from these. Try disabling this if you suspect plugin conflict.','page-whitelists').'</p>';                
    }
 	
	function add_menus() {
		//$plugin_title = __("Page Whitelists",'page-whitelists');
		$plugin_title = "Page Whitelists";
		
        
        
        add_submenu_page(
            'options-general.php',
            $plugin_title." - ".__('settings','page-whitelists'), //title of the main options page
            $plugin_title,
            'manage_options',
            'wl_lists',
            array($this,'render_admin_page')           
        );
        
		add_submenu_page( 
			'users.php',
			__('Manage whitelists','page-whitelists'), //title of the main options page
			__('Manage whitelists','page-whitelists'), //label of the sidebar link
			'manage_options',
			'wl_lists_manage', //the slug of the options page
			array($this,'render_lists_page')  
		);	
	}
	
    function render_admin_page() {
        if (!current_user_can('manage_options')) { return; }
        require_once $this->template_path."admin_page.php";        
    }
    
	function render_lists_page() {
	    // check user capabilities
        if (!current_user_can('manage_options')) { return; }
        
	    $lists = $this->data->get_all_whitelists();
        //$filter_all_listings = $this->data->settings['filter_all_listings'];
        //$strict_as_default = $this->data->settings['strict_as_default'];
        require_once $this->template_path."lists_page.php";
	}
	
	function add_metabox() {
		if (!current_user_can('manage_options')) return;
		add_meta_box(
			'wlist-metabox',
			__('Associated Whitelists','page-whitelists'),
			array($this,'render_metabox'),
			'page',
			'side'
		);
	}
	
	function render_metabox($post) {
		wp_nonce_field(-1,'wlist_onpage_edit');
		$all_wlists = $this->data->get_all_whitelists();
		require_once $this->template_path."metabox.php";		
	}
	
	function save_metabox($page_id) {
		if (!isset( $_POST['wlist_onpage_edit'])) {
			return;
		} //nonce not set
		if (!wp_verify_nonce( $_POST['wlist_onpage_edit'])) {
			return;
		}//nonce not validated
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		} //just an autosave
		if (!current_user_can('edit_post',$page_id)) {
			return;
		} //user can't edit post
		
		$wlists = (isset($_POST['wlists']))?$_POST['wlists']:array();
		
		$all_whitelists = $this->data->get_all_whitelists();
		foreach ($all_whitelists as $list) {
			if (!in_array($list->get_id(), $wlists)) {
				$list->remove_page($page_id);
			} else {
				$list->add_page($page_id);
			}
		}
	}

	function user_column_header($column_headers) {
		$column_headers['whitelists'] = _x('Assigned Whitelists','user table column','page-whitelists');
  		return $column_headers;
	}
	
	function user_column_content($value,$column_name,$user_id) {
		if ($column_name == 'whitelists') {
			$whitelists = $this->data->get_user_whitelists($user_id);
			$listnames = array();
			$output = "";
			foreach ($whitelists as $whitelist) {
				$listnames[] = $whitelist->get_name();
			}
			if (sizeof($listnames)!=0) {
				$output = implode(", ", $listnames); 
			}
			return $output;
		}
		return $value;
	}
	
	function profile_field($user) {
		if (!current_user_can("manage_options")) return;
		$whitelists = $this->data->get_all_whitelists();
		require_once $this->template_path."profile_field.php";
	}
	
	function profile_field_update($user_id) {
		$whitelists = $this->data->get_all_whitelists();
		$assigned_wlists = $_POST['wl_assigned_whitelists'];
        if (!is_array($assigned_wlists)) return;
		foreach ($whitelists as $wlist) {
			if (in_array($wlist->get_id(),$assigned_wlists)) {
				$wlist->add_user($user_id);
			} else {
				$wlist->remove_user($user_id);
			}
		}
	}
	
	/***************** SCRIPTS AND STYLES **********************/
	
	function enqueue_assets($hook) {
		$screen = get_current_screen();
        $admin_pages = Array('page-whitelists_page_wl_lists_manage','toplevel_page_wl_lists','users_page_wl_lists_manage');
		if(!in_array($screen->id, $admin_pages)) {
			return;
		}
		//enqueue main script & style
		$script_path = $this->template_url. 'js/wl_lists.js';
		$style_path = $this->template_url. 'css/wl_lists.css';
		wp_enqueue_style('wl_lists_style', $style_path,false,'1.1.0');
		wp_enqueue_script('wl_lists_js', $script_path, array('jquery'),'1.1.0',true);
        
        //enqueue jstree script & style
        $jstree_script_path = $this->template_url. 'jstree/jstree.min.js';
        $jstree_style_path = $this->template_url. 'jstree/style.min.css';
        wp_enqueue_style('wl_lists_jstree_style', $jstree_style_path);
        wp_enqueue_script('wl_lists_jstree_js', $jstree_script_path, array('jquery'),'1.0.0',true);
        
        
		wp_localize_script( 'wl_lists_js', 'jsi18n', array(
		    'no' => __("no",'page-whitelists'),
		    'yes' => __("yes",'page-whitelists'),
			'del' => __( 'Delete', 'page-whitelists' ),
			'title' => __( 'Title', 'page-whitelists' ),
			'allowNew' => __('Allow creation of new pages','page-whitelists'),
			'wlistedPages' => __('Whitelisted pages','page-whitelists'),
			'assignedTo' => __('Assigned to users','page-whitelists'),
			'asToUsers' => __('Assigned to users','page-whitelists'),
			'asToRoles' => __('Assigned to roles','page-whitelists'),
			'cancel' => _x('Cancel','cancel editing/creating whitelist','page-whitelists'),
			'save' => __('Save','page-whitelists'),
			'createNew' => __('Create new...','page-whitelists'),
			'edit' => __('Edit','page-whitelists'),
			'saveWNameErr' => __('Cannot save a whitelist without a name.','page-whitelists'),
			'createdSuccess' => __('Whitelist successfully created.','page-whitelists'),
			'editedSuccess' => __('Whitelist successfully edited.','page-whitelists'),
			'deletedSuccess' => __('Whitelist successfully deleted.','page-whitelists'),
			'err' => __('Error.','page-whitelists'),
			'confirmLeave' => __('You have unsaved changes. Do you want to continue?','page-whitelists'),
			'confirmDelete' => __('Are you sure you want to delete whitelist {listName}?','page-whitelists'),
			'selectAll' => __('select all','page-whitelists'),
			'selectNone' => __('select none','page-whitelists'),
			'missingParent' => __('missing parent page','page-whitelists'),
			'moreLink' => _x('more','more link in whitelist editor','page-whitelists'),
			'jstreeLoading' => __('Loading...','page-whitelists'),
            
		) );
	}
	
	function register_ajax() {
		add_action('wp_ajax_wl_delete', array($this,'ajax_delete'));
		add_action('wp_ajax_wl_load', array($this,'ajax_load'));
		add_action('wp_ajax_wl_save', array($this,'ajax_save'));
	}
	
	/***************** AJAX **********************/
	
	function ajax_delete() {
		if (!current_user_can("manage_options")) die('user not allowed to edit settings');
		$id = $_POST['id'];
		$passed = check_ajax_referer( 'delete-wlist-'.$id, 'nonce', false);
		if (!$passed) die('nonce failed');
		$result = $this->data->delete_whitelist($id);
		if ($result[0]) {
			$reply = 'success';
		} else {
			$reply = $result[1];
		}
		die($reply);
	}
	
	function ajax_load() {
		//FIRST STEP: build a "fresh" data array
		$data = array();
		$data['pages'] = array();
		$query = new WP_Query('post_type=page&posts_per_page=-1');
		
        $found_ids = Array();
        
		while ($query->have_posts()) {
			$query->the_post();
			$data['pages'][] = array(
				'title'=> $query->post->post_title,
				'id' => $query->post->ID,
				'parent' => $query->post->post_parent,
				'url' => get_permalink($query->post->ID),				
				'assigned'=>false
			);
            $found_ids[] = $query->post->ID;
		}
        
        wp_reset_postdata();
        
        foreach ($data['pages'] as $key=>$page) {
            if ($page['parent'] == 0) {
                continue;
            }
            if (!in_array($page['parent'],$found_ids)) {
                $data['pages'][$key]['parent']=-1;
            }            
        }
        
        //check if all pages have parents
        
        //e
        
		
		$data['users'] = array();
		$query_args = array(
			'orderby'=>'ID'
		);
		$user_query = new WP_User_Query($query_args); 
		$users = $user_query->results;
		foreach($users as $user) {
			if (!user_can($user,"manage_options") && user_can($user,'edit_pages')) {
				$data['users'][] = array(
					'login' => $user->user_login,
					'id' => $user->ID,
					'assigned' => false
				);
			}	 
		}
		
		$all_roles = get_editable_roles();
		$data['roles'] = array();
		foreach ($all_roles as $rolename=>$roledata) {
			if (!isset($roledata['capabilities']['manage_options']) && isset($roledata['capabilities']['edit_pages'])) {
				$data['roles'][$rolename] = false;	
			}
		}
		
		//SECOND STEP: mark assigned pages/roles/users
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			$list = $this->data->get_whitelist_by('id',$id);
			$assigned_pages = $list->get_page_ids();
			foreach ($data['pages'] as $key=>$page) {
				if (in_array($page['id'],$assigned_pages)) {
					$data['pages'][$key]['assigned']=true;
				}
			} 
			$assigned_users = $list->get_user_logins();
			foreach ($data['users'] as $key=>$user) {
				if (in_array($user['login'],$assigned_users)) {
					$data['users'][$key]['assigned']=true;
				}
			}
			 
			$assigned_roles = $list->get_role_names();
			foreach ($data['roles'] as $key=>$role) {
				if (in_array($key,$assigned_roles)) {
					$data['roles'][$key] = true;
				}
			} 
			$data['strict'] = $list->is_strict();
			$data['name'] = $list->get_name();
			$data['id'] = $list->get_id();
			$data['time'] = $list->get_time();
			$data['nonce'] = wp_create_nonce("edit-wlist".$list->get_id());
		} else {
			$data['strict'] = $this->data->settings['strict_as_default'];
			$data['id'] = '';
			$data['nonce'] = wp_create_nonce("create-wlist");
		}
		die(json_encode($data)); 		
	}
	
	function ajax_save() {
		try {
			if (!current_user_can("manage_options")) {
				throw new Exception("insufficient capabilities");
			}	
			if ($_POST['name']=='') {
				throw new Exception("name-missing");
			} else {
			    $name = stripslashes($_POST['name']);
			};
            
			if ($_POST['id']=='') {
				$passed = check_ajax_referer( 'create-wlist', 'nonce', false);
				if (!$passed) throw new Exception("nonce-failed");	
				$list = $this->data->create_whitelist($name,$_POST['strict']);
				if (!$list) {
					throw new Exception("unknown");				
				} elseif (get_class($list)!='WL_List') {
					throw new Exception("name-in-use");
				} else {
					$list_status = "created";
				}		
			} else {
				$passed = check_ajax_referer( 'edit-wlist'.$_POST['id'], 'nonce', false);
				if (!$passed) throw new Exception("nonce-failed");	
				$list = $this->data->get_whitelist_by('id',$_POST['id']);
				if (!$list) {
					throw new Exception("not-found");
				} else {
					$list_status = "edited";
				}
			}
			
			if ($name != $list->get_name()) {
				$renamed = $list->rename($name);
				if (!$renamed) {
					throw new Exception("could-not-rename");
				}
			}
			
			$assigned_pages = $list->get_page_ids();
			//TODO rethink. this is vulnerable to badly coded frontend and can lead to unassigning all pages of a list accidentally.
			if ($_POST['pages']=='') {
			    
				foreach ($assigned_pages as $page) {
					$success = $list->remove_page($page);
				}				
			} else {
				$pages = explode(",",$_POST['pages']);
				foreach ($pages as $page_id) {
					$success = $list->add_page($page_id);
				}
				foreach ($assigned_pages as $page_id) {
					if (!in_array($page_id,$pages)) {
						$list->remove_page($page_id);
					}
				}
			}
			
			$assigned_users = $list->get_users();
			if ($_POST['users']=='') {
				foreach ($assigned_users as $user) {
					$success = $list->remove_user($user);
				}
			} else {
				$users = explode(",",$_POST['users']);
					foreach ($users as $user_id) {
					$success = $list->add_user($user_id);
				}				
				foreach ($assigned_users as $user) {
					if (!in_array($user->ID,$users)) {
						$list->remove_user($user);
					}
				}
			} 
			$assigned_roles = $list->get_roles();
			if ($_POST['roles']=='') {
				foreach ($assigned_roles as $role) {
					$success = $list->remove_role($role);
				}
			} else {
				$roles = explode(",",$_POST['roles']);
					foreach ($roles as $role_name) {
					$success = $list->add_role($role_name);
				}
				
				foreach ($assigned_roles as $role) {
					if (!in_array($role->name,$roles)) {
						$success = $list->remove_role($role);
					}
				}	
			}
			if ($_POST['strict']=='false') {$list->set_strict(false);
			} else {
				$list->set_strict();
			}
            
            $result = array(
				"success"=>true,
				"id"=>$list->get_id(),
				"name"=>$list->get_name(),
				"message"=>$list_status,
				"pages"=>array(),				
				"users"=>$list->get_user_logins(),
				"roles"=>$list->get_role_names(),
				'strict'=>$list->is_strict(),
			);
            
            $assigned_pages = $list->get_pages(); //the current list
            
            foreach($assigned_pages as $page) {
                $result["pages"][] = array(
                    'title'=> $page->post_title,
                    'id' => $page->ID,
                    'parent' => $page->post_parent,
                    'url' => get_permalink($page->ID),               
                    'assigned'=>false
                );
            }
            
            $result['deleteNonce'] = ($list_status=="created")?wp_create_nonce("delete-wlist-".$list->get_id()):null;
			if (!$success) {
				$result['success']=false;
				$result['message']='addition-errors';
			}
			die(json_encode($result));
		} catch (Exception $e) {
			$result = array(
				"success"=>false,
				"message" => $e->getMessage()
			);
			die(json_encode($result));
		}		
	}
}