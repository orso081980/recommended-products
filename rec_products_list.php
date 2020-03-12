
<div class="wrap">
    <h2>Recommended Products</h2>

    <?php if (isset($_POST['delete'])) { ?>
        <div class="updated"><p>Product deleted</p></div>
        <a href="<?php echo admin_url('admin.php?page=rec_products_list') ?>">&laquo; Back to products list</a>
    <?php } else { ?>

        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=rec_products_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>

        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Name</th>
                <th class="manage-column ss-list-width">Image</th>
                <th class="manage-column ss-list-width">Html</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><img src="<?php echo $row->url; ?>" alt="image"></td>
                    <td class="manage-column ss-list-width"><?php echo $row->html; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=rec_products_update&id=' . $row->id); ?>">Update</a></td>
                    <td class="manage-column ss-list-width">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=rec_products_list">
                            <input type='hidden' name="delete" value="<?php echo $row->id; ?>">
                            <input id="deleteproduct" type='submit' value='Delete'>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php } ?>
</div>
