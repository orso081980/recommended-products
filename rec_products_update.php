
<div class="wrap">
    <h2>Recommended Products</h2>

    <?php if (isset($_POST['delete'])) { ?>
        <div class="updated"><p>Product deleted</p></div>
        <a href="<?php echo admin_url('admin.php?page=rec_products_list') ?>">&laquo; Back to products list</a>

    <?php } else if (isset($_POST['update'])) { ?>
        <div class="updated"><p>Product updated</p></div>
        <a href="<?php echo admin_url('admin.php?page=rec_products_list') ?>">&laquo; Back to products list</a>

    <?php } else { ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Name</th>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">Image</th>
                    <td>
                        <input id="background_image" type="text" name="url" value="<?php echo $url; ?>" />
                        <input id="upload_image_button" type="button" class="button-primary" value="Insert Image" />
                    </td>
                </tr>
                <tr>
                    <th class="ss-th-width">Html</th>
                    <td><input type="text" name="html" value="<?php echo $html; ?>" class="ss-field-width" /></td>
                </tr>
            </table>
            <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
            <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Do you want to delete this element?')">
        </form>
    <?php } ?>
</div>
