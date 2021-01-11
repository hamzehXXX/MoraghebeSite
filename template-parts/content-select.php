<?php
print_r($args);
    $disabledSelect = $args[0];

$newValue = get_query_var( 'my_var_name' );
?>
<h2><?php echo __( $args['section_title'], 'سلام' ); ?></h2>
<select class="select-css" <?php echo $disabledSelect; ?>>
    <option>0 (انجام ندادم)</option>
    <option>1 (ضعیف)</option>
    <option>2 (متوسط)</option>
    <option>3 (قوی)</option>
</select>
