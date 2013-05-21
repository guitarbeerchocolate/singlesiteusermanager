<?php
if(isset($messagesession->messagename) && (strtolower($messagesession->messagename) == 'general') && !empty($messagesession->message))
{
?>
<div class="alert">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $messagesession->message; ?>
</div>
<?php
$messagesession->destroyMessageSession();
}
?>