<?php
add_action( 'admin_menu', 'plugin_menu' );

function plugin_menu() {
	add_options_page( 'Google Analytics POP Posts', 'Google Analytics POP Posts', 'manage_options', 'my-unique-identifier', 'plugin_options' );
}

function plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo <<< EOF
<div class="wrap">
    <h2>Google Analytics POP posts</h2>
    <form action="">
      <table>
        <tr>
            <th>Private Key File</th>
            <td>:</td>
            <td><input type="file" name="keyfile"/></td>
        </tr>
        <tr>
            <th>View ID</th>
            <td>:</td>
            <td><input type="text" name="view_id"/></td>
        </tr>
        <tr>
            <th>Show List Count</th>
            <td>:</td>
            <td><input type="number" /></td>
        </tr>
        <tr>
            <th>Exclude URL</th>
            <td>:</td>
            <td><input type="text" /></td>
        </tr>
      </table>
      <input type="submit" class="button button-primary"/>
    </form>
</div>
EOF;
}
