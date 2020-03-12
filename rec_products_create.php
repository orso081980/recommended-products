
<div class="wrap">
    <h2>Add New Product</h2>
    <?php 
    if (isset($message)): 
        ?>
        <div class="updated"><p><?php echo $message; ?></p></div>
        <a href="<?php echo admin_url('admin.php?page=rec_products_list') ?>">&laquo; Back to locations list</a>
        <?php 
    elseif (isset($exists)) :
        ?>
        <div class="updated"><p>The record already exists</p></div>
        <a href="<?php echo admin_url('admin.php?page=rec_products_list') ?>">&laquo; Back to locations list</a>
        <?php 
    else: 
        ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <p>Three capital letters for the ID</p>
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Name</th>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">Image</th>
                    <td>
                        <input id="background_image" type="text" name="url" value="<?php echo get_option('url'); ?>" />
                        <input id="upload_image_button" type="button" class="button-primary" value="Insert Image" />
                    </td>
                </tr>
                <tr>
                    <th class="ss-th-width">Html</th>
                    <td><input type="text" name="html" value="<?php echo $html; ?>" class="ss-field-width" /></td>
                </tr>
            </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>

        <?php 
    endif; 
    ?>
</div>
