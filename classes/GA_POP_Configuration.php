<?php

// require_once __DIR__ . '/GA_POP_Cache.php';
class GA_POP_Configuration
{
    /** 設定値 */
    private $options;
	/** whether keyfile is uploaded or not */
    private $is_uploaded;
    /** 成功メッセージ */
    private $success;
    /** エラーメッセージ */
    private $errors;

    /**
     * 初期化処理です。
     */
    public function __construct()
    {
        // メニューを追加します。
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        // ページの初期化を行います。
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * メニューを追加します。
     */
    public function add_plugin_page()
    {
        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        //   $page_title: 設定ページの<title>部分
        //   $menu_title: メニュー名
        //   $capability: 権限 ( 'manage_options' や 'administrator' など)
        //   $menu_slug : メニューのslug
        //   $function  : 設定ページの出力を行う関数
        //   $icon_url  : メニューに表示するアイコン
        //   $position  : メニューの位置 ( 1 や 99 など )
        add_options_page( 'Google Analytics POP Posts', 'Google Analytics POP Posts', 'manage_options', 'ga_pop_setting', [ $this, 'create_admin_page' ] );
    }

    /**
     *
     */
    public function page_init()
    {
		global $GA_POP_KEY_FILE_LOCATION;
		global $GA_POP_CACHE_FILE_LOCATION;

        $this->options = get_option( 'ga_pop_setting' );
		$this->is_uploaded = file_get_contents($GA_POP_KEY_FILE_LOCATION);

        $this->errors = array();
        $this->success = "";

        // process where post params is set.
        if ( isset($_POST['ga_pop_setting']) ) {
            $input = $_POST['ga_pop_setting'];
			$file = $_FILES['keyfile'];

			GA_POP_Cache::clear_cache();
			if (is_uploaded_file($file['tmp_name'])){
				if(!move_uploaded_file($file['tmp_name'],$GA_POP_KEY_FILE_LOCATION)){
					$this->errors['keyfile'] = "Failed to upload";
				}
			}else{
                if(!$this->is_uploaded){
					$this->errors['keyfile'] = "Please chose keyfile";
				}
			}

            $this->options['view_id'] = $input['view_id'];
            if( ! isset( $this->options['view_id'] ) || $this->options['view_id'] === '' ) {
                $this->errors['view_id'] = "Please input VIEW ID";
            }

            $this->options['show_list'] = $input['show_list'];
            if( ! isset( $this->options['show_list'] ) || $this->options['show_list'] === '' || $this->options['show_list'] < 1 ) {
                $this->errors['show_list'] = "Please input Show List";
            }

			$this->options['date_from'] = $input['date_from'];
            if( ! isset( $this->options['date_from'] ) || $this->options['show_list'] === '' || $this->options['show_list'] < 1 ) {
                $this->errors['date_from'] = "Please input date_from";
            }

            $this->options['exclude_url'] = $input['exclude_url'];

            // エラーがない場合保存処理
            if ( ! $this->errors ) {
                update_option( 'ga_pop_setting', $this->options );
                $this->success = "Save succesfully";
            }
			$this->is_uploaded = file_get_contents($GA_POP_KEY_FILE_LOCATION);
        }
    }

    /**
     * for admin page
     */
    public function create_admin_page()
    {

		global $GA_POP_DEFAULT_DISPLAY_COUNT;
		global $GA_POP_DEFAULT_DATE_FROM_NUM;

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$show_list = isset($this->options['show_list']) ? $this->options['show_list'] : $GA_POP_DEFAULT_DISPLAY_COUNT ;
		$date_from = isset($this->options['date_from']) ? $this->options['date_from'] : $GA_POP_DEFAULT_DATE_FROM_NUM ;
        ?>
        <div class="ga_pop_setting">
            <h2>Google Analytics POP Posts | Setting</h2>
            <form  enctype="multipart/form-data" method="post">
                <?php
                // display success page
                if ( $this->success ) { ?>
                    <div class="updated"><p><strong><?php esc_html_e($this->success) ?></strong></p></div>
                <?php } ?>

                <?php
                // display error messages
                if ( $this->errors ) { ?>
                    <?php foreach ($this->errors as $err) { ?>
                        <div class="error"><p><strong><?php esc_html_e($err) ?></strong></p></div>
                    <?php } ?>
                <?php } ?>

                <table class="form-table">
					<table>
			          <tr>
			              <th>
							  Private Key File
							  <?php if($this->is_uploaded){
								  echo '<br><span style="color:#28a745"> Already uploaded</span>';
							  }else{
								  echo '<br><span style="color:#dc3545"> need to upload keyfile</span>';
							  }?>
						  </th>
			              <td>:</td>
			              <td>
							  <input type="file" name="keyfile"/>
						  </td>
			          </tr>
			          <tr>
			              <th>View ID</th>
			              <td>:</td>
			              <td><input type="text" name="ga_pop_setting[view_id]" value="<?php echo $this->options['view_id']?>" required/></td>
			          </tr>
			          <tr>
			              <th>Show List Count</th>
			              <td>:</td>
			              <td><input type="number" name="ga_pop_setting[show_list]" value="<?php echo $show_list?>" required/></td>
			          </tr>
					  <tr>
			              <th>Date From</th>
			              <td>:</td>
			              <td><input type="number" name="ga_pop_setting[date_from]" value="<?php echo $date_from;?>" required/></td>
			          </tr>
			          <tr class="urls">
			              <th style="">Exclude URL<br><span style="font-weight:normal;">please input urls separated by comas</span></th>
			              <td>:</td>
			              <td>
							  <textarea name="ga_pop_setting[exclude_url]" placeholder="/,/profile,/links ..."><?php echo $this->options['exclude_url']?></textarea>
						  </td>
			          </tr>
			        </table>
                </table>
                <?php
                // 送信ボタンを出力します。
                submit_button();
                ?>
            </form>
        </div>
		<style>
		   .ga_pop_setting td{
		   	 padding: 5%;
		   }
		   .ga_pop_setting th {
		   	text-align: left;
		   }
		   .ga_pop_setting textarea {
		   	 width: 500px;
			 height: 300px;
		   }
		</style>
        <?php
    }
}

if( is_admin() ) {
    $ga_pop_configuration = new GA_POP_Configuration();
}
